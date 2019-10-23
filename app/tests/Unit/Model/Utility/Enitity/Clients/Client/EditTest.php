<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Work\Entity\Projects\Task;

use App\Tests\Builder\Utility\Clients\ClientBuilder;
use PHPUnit\Framework\TestCase;

class EditTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testSuccess(): void
    {
        $client = (new ClientBuilder())->build();

        $client->edit($name = 'New Name');

        self::assertEquals($name, $client->name());
    }
}
