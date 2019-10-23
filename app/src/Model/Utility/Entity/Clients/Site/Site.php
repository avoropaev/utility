<?php

declare(strict_types=1);

namespace App\Model\Utility\Entity\Clients\Site;

use App\Model\Utility\Entity\Clients\Client\Client;
use App\Model\Utility\Entity\Clients\ProductGroup\ProductGroup;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="utility_clients_sites")
 */
class Site
{
    /**
     * @var Id
     * @ORM\Column(type="utility_clients_site_id")
     * @ORM\Id
     */
    private $id;

    /**
     * @var Client
     * @ORM\ManyToOne(targetEntity="App\Model\Utility\Entity\Clients\Client\Client", inversedBy="sites", cascade={"all"})
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     */
    private $client;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var ProductGroup[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Model\Utility\Entity\Clients\ProductGroup\ProductGroup", inversedBy="sites")
     * @ORM\JoinTable(name="utility_clients_sites_product_groups")
     */
    private $productGroups;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * Site constructor.
     * @param Id $id
     * @param string $name
     * @param Client $client
     * @param \DateTimeImmutable $createdAt
     */
    public function __construct(
        Id $id,
        string $name,
        Client $client,
        \DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->client = $client;
        $this->productGroups = new ArrayCollection();
        $this->createdAt = $createdAt;
    }

    /**
     * @param ProductGroup $productGroup
     */
    public function addProductGroup(ProductGroup $productGroup): void
    {
        if (!$this->productGroups->contains($productGroup)) {
            $this->productGroups->add($productGroup);
            $productGroup->addSite($this);
        }
    }

    /**
     * @param ProductGroup $productGroup
     */
    public function removeProductGroup(ProductGroup $productGroup): void
    {
        if ($this->productGroups->contains($productGroup)) {
            $this->productGroups->removeElement($productGroup);
            $productGroup->removeSite($this);
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
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return Client
     */
    public function client(): Client
    {
        return $this->client;
    }

    /**
     * @return ProductGroup[]|array
     */
    public function productGroups(): array
    {
        return $this->productGroups->toArray();
    }

    /**
     * @return \DateTimeImmutable
     */
    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
