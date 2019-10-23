<?php

declare(strict_types=1);

namespace App\Controller\Utility\Clients\Client;

use App\Model\Utility\Entity\Clients\Client\Client;
use App\ReadModel\Utility\Clients\Site\Filter;
use App\ReadModel\Utility\Clients\Site\SiteFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/utility/clients/{client_id}/sites", name="utility.clients.client.sites")
 * @ParamConverter("client", options={"id" = "client_id"})
 */
class SitesController extends AbstractController
{
    private const PER_PAGE = 15;

    /**
     * @Route("", name="")
     * @param Client $client
     * @param Request $request
     * @param SiteFetcher $sites
     * @return Response
     */
    public function index(Client $client, Request $request, SiteFetcher $sites): Response
    {
        $filter = Filter\Filter::forClient($client->id()->getValue());
        $form = $this->createForm(Filter\Form::class, $filter);
        $form->handleRequest($request);

        $pagination = $sites->all(
            $filter,
            $request->query->getInt('page', 1),
            self::PER_PAGE,
            $request->query->get('sort', 'created_at'),
            $request->query->get('direction', 'desc')
        );

        return $this->render('app/utility/clients/sites.html.twig', [
            'client' => $client,
            'pagination' => $pagination,
            'form' =>  $form->createView()
        ]);
    }
}
