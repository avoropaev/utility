<?php

declare(strict_types=1);

namespace App\Command\Utility\Clients;

use App\Model\Utility\UseCase\Clients\Client\Sync;
use App\ReadModel\Utility\Clients\Client\ClientFetcher;
use App\ReadModel\Utility\Clients\Client\FullView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncCommand extends Command
{
    /**
     * @var ClientFetcher
     */
    private $clients;

    /**
     * @var Sync\Handler
     */
    private $handler;

    /**
     * SyncCommand constructor.
     * @param ClientFetcher $clients
     * @param Sync\Handler $handler
     */
    public function __construct(ClientFetcher $clients, Sync\Handler $handler)
    {
        $this->clients = $clients;
        $this->handler = $handler;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('utility:clients:sync')
            ->setDescription('Sync clients, sites, product groups, products from ResponseCRM');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var FullView[] $clients */
        $clients = $this->clients->allList();

        foreach ($clients as $client) {
            $this->handler->handle(new Sync\Command($client->id));
        }
    }
}
