<?php

declare(strict_types=1);

namespace App\Controller\Utility\Clients;

use App\Model\Utility\Entity\Clients\Client\Client;
use App\Model\Utility\Entity\Clients\ProductGroup\ProductGroup;
use App\ReadModel\Utility\Clients\ProductGroup\ProductGroupFetcher;
use App\ReadModel\Utility\Clients\ProductGroup\Filter;
use App\Controller\ErrorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/utility/clients/product_groups", name="utility.clients.product_groups")
 */
class ProductGroupsController extends AbstractController
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
     * @param ProductGroupFetcher $fetcher
     * @return Response
     */
    public function index(Request $request, ProductGroupFetcher $fetcher): Response
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

        return $this->render('app/utility/clients/product_groups.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
            'client' => null
        ]);
    }

    /**
     * @Route("/{id}", name=".show", requirements={"id"="\d+"}))
     * @param ProductGroup $productGroup
     * @return Response
     */
    public function show(ProductGroup $productGroup): Response
    {
        return $this->render('app/utility/clients/product_groups/show.html.twig', [
            'product_group' => $productGroup
        ]);
    }
}
