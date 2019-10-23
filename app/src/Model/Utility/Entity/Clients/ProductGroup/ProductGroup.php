<?php

declare(strict_types=1);

namespace App\Model\Utility\Entity\Clients\ProductGroup;

use App\Model\Utility\Entity\Clients\Client\Client;
use App\Model\Utility\Entity\Clients\ProductGroup\Charge\Charge;
use App\Model\Utility\Entity\Clients\Site\Site;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="utility_clients_product_groups")
 */
class ProductGroup
{
    /**
     * @var Id
     * @ORM\Column(type="utility_clients_product_group_id")
     * @ORM\Id
     */
    private $id;

    /**
     * @var Client
     * @ORM\ManyToOne(targetEntity="App\Model\Utility\Entity\Clients\Client\Client", inversedBy="productGroups")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     */
    private $client;

    /**
     * @var Site[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Model\Utility\Entity\Clients\Site\Site", mappedBy="productGroups")
     */
    private $sites;

    /**
     * @var Charge[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Model\Utility\Entity\Clients\ProductGroup\Charge\Charge", mappedBy="productGroup", orphanRemoval=true)
     */
    private $charges;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $guid;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * ProductGroup constructor.
     * @param Id $id
     * @param Client $client
     * @param string $name
     * @param string $guid
     * @param \DateTimeImmutable $createdAt
     */
    public function __construct(
        Id $id,
        Client $client,
        string $name,
        string $guid,
        \DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->client = $client;
        $this->name = $name;
        $this->sites = new ArrayCollection();
        $this->guid = $guid;
        $this->createdAt = $createdAt;
    }

    /**
     * @param Site $site
     */
    public function addSite(Site $site): void
    {
        if (!$this->sites->contains($site)) {
            $this->sites->add($site);
            $site->addProductGroup($this);
        }
    }

    /**
     * @param Site $site
     */
    public function removeSite(Site $site): void
    {
        if ($this->sites->contains($site)) {
            $this->sites->removeElement($site);
            $site->removeProductGroup($this);
        }
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
     * @return Client
     */
    public function client(): Client
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function guid(): string
    {
        return $this->guid;
    }

    /**
     * @return Site[]|array
     */
    public function sites(): array
    {
        return $this->sites->toArray();
    }

    /**
     * @return Charge[]|array
     */
    public function charges(): array
    {
        return $this->charges->toArray();
    }

    /**
     * @return \DateTimeImmutable
     */
    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
