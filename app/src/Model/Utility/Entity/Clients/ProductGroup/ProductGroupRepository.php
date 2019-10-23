<?php

declare(strict_types=1);

namespace App\Model\Utility\Entity\Clients\ProductGroup;

use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Model\Utility\Entity\Clients\Client\Id as ClientId;

class ProductGroupRepository
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
     * ProductGroupRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(ProductGroup::class);
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
     * @return ProductGroup|null
     */
    public function find(Id $id): ?ProductGroup
    {
        /** @var ProductGroup|null $productGroup */
        $productGroup = $this->repo->find($id->getValue());

        return $productGroup;
    }

    /**
     * @param Id $id
     * @return ProductGroup
     */
    public function get(Id $id): ProductGroup
    {
        /** @var ProductGroup $productGroup */
        if (null === $productGroup = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Product group is not found.');
        }

        return $productGroup;
    }

    /**
     * @param ProductGroup $productGroup
     */
    public function add(ProductGroup $productGroup): void
    {
        $this->em->persist($productGroup);
    }

    /**
     * @param ProductGroup $productGroup
     */
    public function remove(ProductGroup $productGroup): void
    {
        $this->em->remove($productGroup);
    }
}
