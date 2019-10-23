<?php

declare(strict_types=1);

namespace App\Service\ResponseCRM\Contract\Type;

use Doctrine\Common\Collections\ArrayCollection;

class ProductGroup
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $productGroupGUID;

    /**
     * @var string
     */
    private $name;

    /**
     * @var ArrayCollection
     */
    private $charges;

    /**
     * ProductGroup constructor.
     */
    public function __construct()
    {
        $this->charges = new ArrayCollection();
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
    public function getProductGroupGUID(): string
    {
        return $this->productGroupGUID;
    }

    /**
     * @param string $productGroupGUID
     */
    public function setProductGroupGUID(string $productGroupGUID): void
    {
        $this->productGroupGUID = $productGroupGUID;
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
     * @return Charge[]|ArrayCollection
     */
    public function getCharges(): ArrayCollection
    {
        return $this->charges;
    }

    /**
     * @param Charge $charge
     */
    public function addCharge(Charge $charge): void
    {
        if (!$this->charges->contains($charge)) {
            $this->charges->add($charge);
        }
    }

    /**
     * @param Charge $charge
     */
    public function removeCharge(Charge $charge): void
    {
        if ($this->charges->contains($charge)) {
            $this->charges->remove($charge);
        }
    }
}
