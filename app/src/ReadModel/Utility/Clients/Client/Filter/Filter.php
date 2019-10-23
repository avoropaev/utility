<?php

declare(strict_types=1);

namespace App\ReadModel\Utility\Clients\Client\Filter;

class Filter
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $secretKey;

    /**
     * @return Filter
     */
    public static function all(): self
    {
        return new self();
    }
}
