<?php

declare(strict_types=1);

namespace App\Service\ResponseCRM\Contract\Type;

use Doctrine\Common\Collections\ArrayCollection;

class Site
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var ArrayCollection
     */
    private $groups;

    /**
     * Site constructor.
     */
    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return ProductGroup[]|ArrayCollection
     */
    public function getGroups(): ArrayCollection
    {
        return $this->groups;
    }

    /**
     * @param ProductGroup $productGroup
     */
    public function addGroup(ProductGroup $productGroup): void
    {
        if (!$this->groups->contains($productGroup)) {
            $this->groups->add($productGroup);
        }
    }

    /**
     * @param ProductGroup $productGroup
     */
    public function removeGroup(ProductGroup $productGroup): void
    {
        if ($this->groups->contains($productGroup)) {
            $this->groups->remove($productGroup);
        }
    }
}
