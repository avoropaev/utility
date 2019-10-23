<?php

declare(strict_types=1);

namespace App\Service\ResponseCRM\Contract\Type;

use Doctrine\Common\Collections\ArrayCollection;

class Charge
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var ArrayCollection
     */
    private $recurringCycles;

    /**
     * Charge constructor.
     */
    public function __construct()
    {
        $this->recurringCycles = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return RecurringCycle[]|ArrayCollection
     */
    public function getRecurringCycles(): ArrayCollection
    {
        return $this->recurringCycles;
    }

    /**
     * @param RecurringCycle $recurringCycle
     */
    public function addRecurringCycle(RecurringCycle $recurringCycle): void
    {
        if (!$this->recurringCycles->contains($recurringCycle)) {
            $this->recurringCycles->add($recurringCycle);
        }
    }

    /**
     * @param RecurringCycle $recurringCycle
     */
    public function removeRecurringCycle(RecurringCycle $recurringCycle): void
    {
        if ($this->recurringCycles->contains($recurringCycle)) {
            $this->recurringCycles->remove($recurringCycle);
        }
    }
}
