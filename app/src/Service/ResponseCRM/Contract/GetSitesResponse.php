<?php

declare(strict_types=1);

namespace App\Service\ResponseCRM\Contract;

use App\Service\ResponseCRM\Contract\Type\Site;
use Doctrine\Common\Collections\ArrayCollection;

class GetSitesResponse
{
    /**
     * @var int
     */
    private $status;

    /**
     * @var Site[]|ArrayCollection
     */
    private $sites;

    /**
     * GetSitesResponse constructor.
     */
    public function __construct()
    {
        $this->sites = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return Site[]|ArrayCollection
     */
    public function getSites(): ArrayCollection
    {
        return $this->sites;
    }

    /**
     * @param Site $site
     */
    public function addSite(Site $site): void
    {
        if (!$this->sites->contains($site)) {
            $this->sites->add($site);
        }
    }

    /**
     * @param Site $site
     */
    public function removeSite(Site $site): void
    {
        if ($this->sites->contains($site)) {
            $this->sites->remove($site);
        }
    }
}
