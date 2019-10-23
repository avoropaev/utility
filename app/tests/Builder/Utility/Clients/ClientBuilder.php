<?php

declare(strict_types=1);

namespace App\Tests\Builder\Utility\Clients;

use App\Model\Utility\Entity\Clients\Client\Client;
use App\Model\Utility\Entity\Clients\Client\Id;
use App\Model\Utility\Entity\Clients\Client\SecretKey;

class ClientBuilder
{
    public const DEFAULT_ID = 999;
    public const DEFAULT_NAME = 'Test client';
    public const DEFAULT_SECRET_KEY = 'secret_dddddddddddddddddddddddddddddddd';

    /**
     * @var Id
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var SecretKey
     */
    private $secretKey;

    /**
     * ClientBuilder constructor.
     */
    public function __construct()
    {
        $this->id = new Id(self::DEFAULT_ID);
        $this->name = self::DEFAULT_NAME;
        $this->secretKey = new SecretKey(self::DEFAULT_SECRET_KEY);
    }

    /**
     * @param Id $id
     * @return ClientBuilder
     */
    public function withId(Id $id): self
    {
        $clone = clone $this;
        $clone->id = $id;

        return $clone;
    }

    /**
     * @param string $name
     * @return ClientBuilder
     */
    public function withName(string $name): self
    {
        $clone = clone $this;
        $clone->name = $name;

        return $clone;
    }

    /**
     * @param SecretKey $secretKey
     * @return ClientBuilder
     */
    public function withSecretKey(SecretKey $secretKey): self
    {
        $clone = clone $this;
        $clone->secretKey = $secretKey;

        return $clone;
    }

    /**
     * @return Client
     * @throws \Exception
     */
    public function build(): Client
    {
        return new Client(
            $this->id,
            $this->name,
            $this->secretKey,
            new \DateTimeImmutable()
        );
    }
}