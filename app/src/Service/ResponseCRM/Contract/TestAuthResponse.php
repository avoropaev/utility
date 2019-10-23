<?php

declare(strict_types=1);

namespace App\Service\ResponseCRM\Contract;

class TestAuthResponse
{
    /**
     * @var int
     */
    private $clientId;

    /**
     * @var string
     */
    private $companyName;

    /**
     * @return int
     */
    public function getClientID(): int
    {
        return $this->clientId;
    }

    /**
     * @param int $clientId
     */
    public function setClientID(int $clientId): void
    {
        $this->clientId = $clientId;
    }

    /**
     * @return string
     */
    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    /**
     * @param string $companyName
     */
    public function setCompanyName(string $companyName): void
    {
        $this->companyName = $companyName;
    }
}
