<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Work\Entity\Projects\Task;

use App\Model\Utility\Entity\Clients\Client\Client;
use App\Model\Utility\Entity\Clients\Client\Id;
use App\Model\Utility\Entity\Clients\Client\SecretKey;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testSuccess(): void
    {
        $client = new Client(
            $id = new Id(123),
            $name = 'First client',
            $secretKey = new SecretKey('secret_key'),
            $createdAt = new \DateTimeImmutable()
        );

        self::assertEquals($id, $client->id());
        self::assertEquals($name, $client->name());
        self::assertEquals($secretKey, $client->secretKey());
        self::assertEquals($createdAt->getTimestamp(), $client->createdAt()->getTimestamp());
    }
}
