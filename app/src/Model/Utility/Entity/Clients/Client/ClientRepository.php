<?php

declare(strict_types=1);

namespace App\Model\Utility\Entity\Clients\Client;

use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ClientRepository
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
     * ClientRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Client::class);
        $this->em = $em;
    }

    /**
     * @param string $name
     * @return bool
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function hasByName(string $name): bool
    {
        return $this->repo->createQueryBuilder('c')
                ->select('COUNT(c.id)')
                ->orWhere('c.name = :name')
                ->setParameter(':name', $name)
                ->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @param Id $id
     * @param SecretKey $secretKey
     * @return bool
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function hasByIdOrSecretKey(Id $id, SecretKey $secretKey): bool
    {
        return $this->repo->createQueryBuilder('c')
                ->select('COUNT(c.id)')
                ->orWhere('c.id = :id')
                ->orWhere('c.secretKey = :secretKey')
                ->setParameter(':id', $id->getValue())
                ->setParameter(':secretKey', $secretKey->getValue())
                ->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @param Id $id
     * @return Client
     */
    public function get(Id $id): Client
    {
        /** @var Client $client */
        if (null === $client = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Client is not found.');
        }

        return $client;
    }

    /**
     * @param Client $client
     */
    public function add(Client $client): void
    {
        $this->em->persist($client);
    }

    /**
     * @param Client $client
     */
    public function remove(Client $client): void
    {
        $this->em->remove($client);
    }
}
