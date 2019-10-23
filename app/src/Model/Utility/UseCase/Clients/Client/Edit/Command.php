<?php

declare(strict_types=1);

namespace App\Model\Utility\UseCase\Clients\Client\Edit;

use App\Model\Utility\Entity\Clients\Client\Client;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     */
    public $id;

    /**
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * Command constructor.
     * @param int $id
     * @param string $name
     */
    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @param Client $client
     * @return Command
     */
    public static function fromClient(Client $client): self
    {
        return new self($client->id()->getValue(), $client->name());
    }
}
