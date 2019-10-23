<?php

declare(strict_types=1);

namespace App\Model\Utility\UseCase\Clients\Client\Create;

use App\Model\Flusher;
use App\Model\Utility\Entity\Clients\Client\Client;
use App\Model\Utility\Entity\Clients\Client\ClientRepository;
use App\Model\Utility\Entity\Clients\Client\Id;
use App\Model\Utility\Entity\Clients\Client\SecretKey;
use App\Service\ResponseCRM\Api;

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
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    public function handle(Command $command): void
    {
        $clientSecretKey = new SecretKey($command->secretKey);

        $responseCrmApi = new Api($clientSecretKey);
        $testAuthResponse = $responseCrmApi->testAuth();

        $clientId = new Id($testAuthResponse->getClientID());

        if ($this->clients->hasByIdOrSecretKey($clientId, $clientSecretKey)) {
            throw new \DomainException('Client already exists.');
        }

        $project = new Client(
            $clientId,
            $testAuthResponse->getCompanyName(),
            $clientSecretKey,
            new \DateTimeImmutable()
        );

        $this->clients->add($project);

        $this->flusher->flush();
    }
}
