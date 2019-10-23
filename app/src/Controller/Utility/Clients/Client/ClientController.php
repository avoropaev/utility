<?php

declare(strict_types=1);

namespace App\Controller\Utility\Clients\Client;

use App\Controller\ErrorHandler;
use App\Model\Utility\Entity\Clients\Client\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\Utility\UseCase\Clients\Client\Remove;
use App\Model\Utility\UseCase\Clients\Client\Edit;
use App\Model\Utility\UseCase\Clients\Client\Sync;

/**
 * @Route("/utility/clients/{id}", name="utility.clients.client")
 */
class ClientController extends AbstractController
{
    /**
     * @var ErrorHandler
     */
    private $errors;

    /**
     * ClientController constructor.
     * @param ErrorHandler $errors
     */
    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @Route("/edit", name=".edit")
     * @param Client $client
     * @param Request $request
     * @param Edit\Handler $handler
     * @return Response
     * @throws \Exception
     */
    public function edit(Client $client, Request $request, Edit\Handler $handler): Response
    {
        $command = Edit\Command::fromClient($client);

        $form = $this->createForm(Edit\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Client successfully edited.');

                return $this->redirectToRoute('utility.clients.client.show', ['id' => $client->id()]);
            } catch (\DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/utility/clients/client/edit.html.twig', [
            'client' => $client,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("", name=".show", requirements={"id"="\d+"})
     * @param Client $client
     * @return Response
     */
    public function show(Client $client): Response
    {
        return $this->render('app/utility/clients/client/show.html.twig', compact('client'));
    }

    /**
     * @Route("/delete", name=".delete", methods={"POST"})
     * @param Client $client
     * @param Request $request
     * @param Remove\Handler $handler
     * @return Response
     * @throws \Exception
     */
    public function delete(Client $client, Request $request, Remove\Handler $handler): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            $this->addFlash('error', 'The CSRF token is invalid. Please try again.');

            return $this->redirectToRoute('utility.clients.client.show', ['id' => $client->id()]);
        }

        $command = new Remove\Command($client->id()->getValue());

        try {
            $handler->handle($command);
            $this->addFlash('success', 'Client successfully removed.');
        } catch (\DomainException $e) {
            $this->errors->handle($e);
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('utility.clients');
    }

    /**
     * @Route("/sync", name=".sync", methods={"POST"})
     * @param Client $client
     * @param Request $request
     * @param Sync\Handler $handler
     * @return Response
     * @throws \Exception
     */
    public function sync(Client $client, Request $request, Sync\Handler $handler): Response
    {
        if (!$this->isCsrfTokenValid('sync', $request->request->get('token'))) {
            $this->addFlash('error', 'The CSRF token is invalid. Please try again.');

            return $this->redirectToRoute('utility.clients.client.show', ['id' => $client->id()]);
        }

        $command = new Sync\Command($client->id()->getValue());

        try {
            $handler->handle($command);
            $this->addFlash('success', 'Client successfully synced.');
        } catch (\DomainException $e) {
            $this->errors->handle($e);
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirect($request->headers->get('referer'));
    }
}
