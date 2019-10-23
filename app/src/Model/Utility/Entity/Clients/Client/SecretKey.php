<?php

declare(strict_types=1);

namespace App\Model\Utility\Entity\Clients\Client;

use Webmozart\Assert\Assert;

class SecretKey
{
    /**
     * @var string
     */
    private $value;

    /**
     * SecretKey constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::startsWith($value, 'secret_');

        $this->value = mb_strtolower($value);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
