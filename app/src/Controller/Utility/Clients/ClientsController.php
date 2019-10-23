<?php

declare(strict_types=1);

namespace App\Controller\Utility\Clients;

use App\Model\Utility\UseCase\Clients\Client\Create;
use App\ReadModel\Utility\Clients\Client\ClientFetcher;
use App\ReadModel\Utility\Clients\Client\Filter;
use App\Controller\ErrorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/utility/clients", name="utility.clients")
 */
class ClientsController extends AbstractController
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
     * @param ClientFetcher $fetcher
     * @param Create\Handler $createHandler
     * @return Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function index(Request $request, ClientFetcher $fetcher, Create\Handler $createHandler): Response
    {
        $command = new Create\Command();
        $createForm = $this->createForm(Create\Form::class, $command);
        $createForm->handleRequest($request);
        if ($createForm->isSubmitted() && $createForm->isValid()) {
            try {
                $createHandler->handle($command);
                $this->addFlash('success', 'Client successfully added.');

                return $this->redirectToRoute('utility.clients');
            } catch (\DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }

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

        return $this->render('app/utility/clients/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
            'create_form' => $createForm->createView()
        ]);
    }
}
