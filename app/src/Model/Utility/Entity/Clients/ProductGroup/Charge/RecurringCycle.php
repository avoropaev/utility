<?php

declare(strict_types=1);

namespace App\Model\Utility\Entity\Clients\ProductGroup\Charge;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

class RecurringCycle
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $cycleNum;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $delay;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $shippable;

    /**
     * RecurringCycle constructor.
     * @param int $cycleNum
     * @param float $amount
     * @param int $delay
     * @param bool $shippable
     */
    public function __construct(
        int $cycleNum,
        float $amount,
        int $delay,
        bool $shippable
    ) {
        $this->cycleNum = $cycleNum;
        $this->amount = $amount;
        $this->delay = $delay;
        $this->shippable = $shippable;
    }

    /**
     * @return int
     */
    public function cycleNum(): int
    {
        return $this->cycleNum;
    }

    /**
     * @return float
     */
    public function amount(): float
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function delay(): int
    {
        return $this->delay;
    }

    /**
     * @return bool
     */
    public function isShippable(): bool
    {
        return $this->shippable;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'cycle_num' =>  $this->cycleNum,
            'amount' =>  $this->amount,
            'delay' =>  $this->delay,
            'shippable' =>  $this->shippable
        ];
    }

    /**
     * @param array $recurringCycle
     * @return RecurringCycle
     */
    public static function fromArray(array $recurringCycle): self
    {
        Assert::keyExists($recurringCycle, 'cycle_num');
        Assert::integer($recurringCycle['cycle_num']);

        Assert::keyExists($recurringCycle, 'amount');

        Assert::keyExists($recurringCycle, 'delay');
        Assert::integer($recurringCycle['delay']);

        Assert::keyExists($recurringCycle, 'shippable');
        Assert::boolean($recurringCycle['shippable']);

        return new self(
            $recurringCycle['cycle_num'],
            $recurringCycle['amount'],
            $recurringCycle['delay'],
            $recurringCycle['shippable']
        );
    }
}
