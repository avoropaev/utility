<?php

declare(strict_types=1);

namespace App\Model\Utility\Entity\Clients\ProductGroup\Charge;

use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Model\Utility\Entity\Clients\ProductGroup\Id as ProductGroupId;

class ChargeRepository
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
     * ChargeRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Charge::class);
        $this->em = $em;
    }

    /**
     * @param ProductGroupId $productGroupId
     * @return Charge[]|array
     */
    public function allByProductGroupId(ProductGroupId $productGroupId): array
    {
        return $this->repo->findBy(['productGroup' => $productGroupId->getValue()]);
    }

    /**
     * @param Id $id
     * @return Charge|null
     */
    public function find(Id $id): ?Charge
    {
        /** @var Charge|null $charge */
        $charge = $this->repo->find($id->getValue());

        return $charge;
    }

    /**
     * @param Id $id
     * @return Charge
     */
    public function get(Id $id): Charge
    {
        /** @var Charge $charge */
        if (null === $charge = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Product group is not found.');
        }

        return $charge;
    }

    /**
     * @param Charge $charge
     */
    public function add(Charge $charge): void
    {
        $this->em->persist($charge);
    }

    /**
     * @param Charge $charge
     */
    public function remove(Charge $charge): void
    {
        $this->em->remove($charge);
    }
}
