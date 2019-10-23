<?php

declare(strict_types=1);

namespace App\ReadModel\Utility\Clients\ProductGroup\Filter;

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
     * @var int
     */
    public $client;

    /**
     * @var string
     */
    public $guid;

    /**
     * @var array
     */
    public $sites;

    /**
     * Filter constructor.
     * @param int|null $client
     */
    public function __construct(?int  $client)
    {
        $this->client = $client;
    }

    /**
     * @return Filter
     */
    public static function all(): self
    {
        return new self(null);
    }

    /**
     * @param int $clientId
     * @return Filter
     */
    public static function forClient(int $clientId): self
    {
        return new self($clientId);
    }
}
