<?php

declare(strict_types=1);

namespace App\Model\Utility\Entity\Clients\Client;

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
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->value;
    }
}
