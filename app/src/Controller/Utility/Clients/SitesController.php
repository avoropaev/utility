<?php

declare(strict_types=1);

namespace App\Controller\Utility\Clients;

use App\Model\Utility\Entity\Clients\Site\Site;
use App\ReadModel\Utility\Clients\Site\SiteFetcher;
use App\ReadModel\Utility\Clients\Site\Filter;
use App\Controller\ErrorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/utility/clients/sites", name="utility.clients.sites")
 */
class SitesController extends AbstractController
{
    private const PER_PAGE = 15;

    /**
     * @var ErrorHandler
     */
    private $errors;

    /**
     * ClientsController constructor.
     * @param ErrorHandler $errors
     */
    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @Route("", name="")
     * @param Request $request
     * @param SiteFetcher $fetcher
     * @return Response
     */
    public function index(Request $request, SiteFetcher $fetcher): Response
    {
        $filter = Filter\Filter::all();
        $form = $this->createForm(Filter\Form::class, $filter);
        $form->handleRequest($request);

        $pagination = $fetcher->all(
            $filter,
            $request->query->getInt('page', 1),
            self::PER_PAGE,
            $request->query->get('sort', 'created_at'),
            $request->query->get('direction', 'desc')
        );

        return $this->render('app/utility/clients/sites.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
            'client' => null
        ]);
    }

    /**
     * @Route("/{id}", name=".show", requirements={"id"="\d+"}))
     * @param Site $site
     * @return Response
     */
    public function show(Site $site): Response
    {
        return $this->render('app/utility/clients/sites/show.html.twig', [
            'site' => $site
        ]);
    }
}
