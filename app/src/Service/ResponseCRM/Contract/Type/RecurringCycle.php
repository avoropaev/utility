<?php

declare(strict_types=1);

namespace App\Service\ResponseCRM\Contract\Type;

class RecurringCycle
{
    /**
     * @var int
     */
    private $cycleNum;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var int
     */
    private $delay;

    /**
     * @var bool
     */
    private $isShippable;

    /**
     * @return int
     */
    public function getCycleNum(): int
    {
        return $this->cycleNum;
    }

    /**
     * @param int $cycleNum
     */
    public function setCycleNum(int $cycleNum): void
    {
        $this->cycleNum = $cycleNum;
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
     * @return int
     */
    public function getDelay(): int
    {
        return $this->delay;
    }

    /**
     * @param int $delay
     */
    public function setDelay(int $delay): void
    {
        $this->delay = $delay;
    }

    /**
     * @return bool
     */
    public function isShippable(): bool
    {
        return $this->isShippable;
    }

    /**
     * @param bool $isShippable
     */
    public function setIsShippable(bool $isShippable): void
    {
        $this->isShippable = $isShippable;
    }
}
