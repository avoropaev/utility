<?php

declare(strict_types=1);

namespace App\Model\Utility\Entity\Clients\ProductGroup\Charge;

use App\Model\Utility\Entity\Clients\ProductGroup\ProductGroup;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="utility_clients_product_groups_charges")
 */
class Charge
{
    /**
     * @var Id
     * @ORM\Column(type="utility_clients_product_groups_charge_id")
     * @ORM\Id
     */
    private $id;

    /**
     * @var ProductGroup
     * @ORM\ManyToOne(targetEntity="App\Model\Utility\Entity\Clients\ProductGroup\ProductGroup")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     */
    private $productGroup;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @var RecurringCycle[]|ArrayCollection
     * @ORM\Column(type="utility_clients_product_groups_charge_recurring_cycles")
     */
    private $recurringCycles;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * Charge constructor.
     * @param Id $id
     * @param ProductGroup $productGroup
     * @param string $name
     * @param string $type
     * @param float $amount
     * @param \DateTimeImmutable $createdAt
     */
    public function __construct(
        Id $id,
        ProductGroup $productGroup,
        string $name,
        string $type,
        float $amount,
        \DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->productGroup = $productGroup;
        $this->name = $name;
        $this->type = $type;
        $this->amount = $amount;
        $this->recurringCycles = new ArrayCollection();
        $this->createdAt = $createdAt;
    }

    /**
     * @param RecurringCycle[]|array $newRecurringCycles
     */
    public function updateRecurringCycles(array $newRecurringCycles): void
    {
        Assert::allIsInstanceOf($newRecurringCycles, RecurringCycle::class);
        Assert::uniqueValues(array_map(static function($newRecurringCycle) {
            /** @var RecurringCycle $newRecurringCycle */
            return $newRecurringCycle->cycleNum();
        }, $newRecurringCycles));

        $this->recurringCycles = new ArrayCollection($newRecurringCycles);
    }

    /**
     * @param string $name
     * @param string $type
     * @param float $amount
     */
    public function edit(string $name, string $type, float $amount): void
    {
        $this->name = $name;
        $this->type = $type;
        $this->amount = $amount;
    }

    /**
     * @return Id
     */
    public function id(): Id
    {
        return $this->id;
    }

    /**
     * @return ProductGroup
     */
    public function productGroup(): ProductGroup
    {
        return $this->productGroup;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return float
     */
    public function amount(): float
    {
        return $this->amount;
    }

    /**
     * @return RecurringCycle[]|array
     */
    public function recurringCycles(): array
    {
        return $this->recurringCycles->toArray();
    }

    /**
     * @return \DateTimeImmutable
     */
    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
