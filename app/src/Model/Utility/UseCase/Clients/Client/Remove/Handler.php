<?php

declare(strict_types=1);

namespace App\Model\Utility\UseCase\Clients\Client\Remove;

use App\Model\Flusher;
use App\Model\Utility\Entity\Clients\Client\ClientRepository;
use App\Model\Utility\Entity\Clients\Client\Id;

class Handler
{
    /**
     * @var ClientRepository
     */
    private $clients;

    /**
     * @var Flusher
     */
    private $flusher;

    /**
     * Handler constructor.
     * @param ClientRepository $clients
     * @param Flusher $flusher
     */
    public function __construct(ClientRepository $clients, Flusher $flusher)
    {
        $this->clients = $clients;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     * @throws \Exception
     */
    public function handle(Command $command): void
    {
        $client = $this->clients->get(new Id($command->id));

        $this->clients->remove($client);

        $this->flusher->flush();
    }
}
