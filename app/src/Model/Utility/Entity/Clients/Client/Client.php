<?php

declare(strict_types=1);

namespace App\Model\Utility\Entity\Clients\Client;

use App\Model\Utility\Entity\Clients\ProductGroup\ProductGroup;
use App\Model\Utility\Entity\Clients\Site\Site;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="utility_clients_clients")
 */
class Client
{
    /**
     * @var Id
     * @ORM\Column(type="utility_clients_client_id")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var SecretKey
     * @ORM\Column(type="utility_clients_client_secret_key", unique=true)
     */
    private $secretKey;

    /**
     * @var Site[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Model\Utility\Entity\Clients\Site\Site", mappedBy="client", orphanRemoval=true)
     */
    private $sites;

    /**
     * @var ProductGroup[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Model\Utility\Entity\Clients\ProductGroup\ProductGroup", mappedBy="client", orphanRemoval=true)
     */
    private $productGroups;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * Client constructor.
     * @param Id $id
     * @param string $name
     * @param SecretKey $secretKey
     * @param \DateTimeImmutable $createdAt
     */
    public function __construct(
        Id $id,
        string $name,
        SecretKey $secretKey,
        \DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->secretKey = $secretKey;
        $this->createdAt = $createdAt;
    }

    /**
     * @param string $name
     */
    public function edit(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Id
     */
    public function id(): Id
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return SecretKey
     */
    public function secretKey(): SecretKey
    {
        return $this->secretKey;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
