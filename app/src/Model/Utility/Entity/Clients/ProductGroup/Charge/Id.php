<?php

declare(strict_types=1);

namespace App\Model\Utility\Entity\Clients\ProductGroup\Charge;

use Webmozart\Assert\Assert;

class Id
{
    /**
     * @var int
     */
    private $value;

    /**
     * Id constructor.
     * @param int $value
     */
    public function __construct(int $value)
    {
        Assert::notEmpty($value);

        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param Id $other
     * @return bool
     */
    public function isEqual(Id $other): bool
    {
        return $this->getValue() === $other->getValue();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->value;
    }
}
