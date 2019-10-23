<?php

declare(strict_types=1);

namespace App\Model\Utility\Entity\Clients\Site;

use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Model\Utility\Entity\Clients\Client\Id as ClientId;

class SiteRepository
{
    /**
     * @var EntityRepository
     */
    private $repo;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * SiteRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Site::class);
        $this->em = $em;
    }

    /**
     * @param ClientId $clientId
     * @return array
     */
    public function allByClientId(ClientId $clientId): array
    {
        return $this->repo->findBy(['client' => $clientId->getValue()]);
    }

    /**
     * @param Id $id
     * @return Site
     */
    public function get(Id $id): Site
    {
        /** @var Site $client */
        if (null === $client = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Site is not found.');
        }

        return $client;
    }

    /**
     * @param Site $client
     */
    public function add(Site $client): void
    {
        $this->em->persist($client);
    }

    /**
     * @param Site $client
     */
    public function remove(Site $client): void
    {
        $this->em->remove($client);
    }
}
