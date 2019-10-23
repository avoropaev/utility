<?php

declare(strict_types=1);

namespace App\Model\Utility\UseCase\Clients\Client\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     * @Assert\Regex("/^secret_[a-z0-9]{32}$/")
     */
    public $secretKey;
}
