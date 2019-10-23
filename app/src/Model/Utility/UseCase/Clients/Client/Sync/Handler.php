<?php

declare(strict_types=1);

namespace App\Model\Utility\UseCase\Clients\Client\Sync;

use App\Model\Flusher;
use App\Model\Utility\Entity\Clients\Client\ClientRepository;
use App\Model\Utility\Entity\Clients\Client\Id;
use App\Model\Utility\Entity\Clients\ProductGroup\Charge\Charge;
use App\Model\Utility\Entity\Clients\ProductGroup\Charge\ChargeRepository;
use App\Model\Utility\Entity\Clients\ProductGroup\Charge\RecurringCycle;
use App\Model\Utility\Entity\Clients\ProductGroup\ProductGroup;
use App\Model\Utility\Entity\Clients\ProductGroup\ProductGroupRepository;
use App\Model\Utility\Entity\Clients\Site\Site;
use App\Model\Utility\Entity\Clients\Site\SiteRepository;
use App\Service\ResponseCRM\Api;
use App\Model\Utility\Entity\Clients\Site\Id as SiteId;
use App\Model\Utility\Entity\Clients\ProductGroup\Id as ProductGroupId;
use App\Model\Utility\Entity\Clients\ProductGroup\Charge\Id as ChargeId;
use Doctrine\Common\Collections\ArrayCollection;

class Handler
{
    /**
     * @var ClientRepository
     */
    private $clients;

    /**
     * @var Flusher
     */
    private $flusher;

    /**
     * @var SiteRepository
     */
    private $sites;

    /**
     * @var ProductGroupRepository
     */
    private $productGroups;

    /**
     * @var ChargeRepository
     */
    private $charges;

    /**
     * Handler constructor.
     * @param ClientRepository $clients
     * @param SiteRepository $sites
     * @param ProductGroupRepository $productGroups
     * @param ChargeRepository $charges
     * @param Flusher $flusher
     */
    public function __construct(
        ClientRepository $clients,
        SiteRepository $sites,
        ProductGroupRepository $productGroups,
        ChargeRepository  $charges,
        Flusher $flusher
    ) {
        $this->clients = $clients;
        $this->flusher = $flusher;
        $this->sites = $sites;
        $this->productGroups = $productGroups;
        $this->charges = $charges;
    }

    /**
     * @param Command $command
     * @throws \Exception
     */
    public function handle(Command $command): void
    {
        $client = $this->clients->get(new Id($command->id));

        $responseCrmApi = new Api($client->secretKey());
        $getSitesResponse = $responseCrmApi->getSites();

        /** @var Site[] $sites */
        $sites = $this->sites->allByClientId($client->id());

        /** @var \App\Service\ResponseCRM\Contract\Type\Site $siteFromResponse */
        foreach ($getSitesResponse->getSites() as $siteFromResponse) {
            foreach ($sites as $key => $site) {
                if ($siteFromResponse->getId() === $site->id()->getValue()) {
                    if ($siteFromResponse->getName() !== $site->name()) {
                        $site->edit($siteFromResponse->getName());
                    }

                    $this->syncProductGroups($site, $siteFromResponse->getGroups());

                    unset($sites[$key]);
                    continue 2;
                }
            }

            $newSite = new Site(
                new SiteId($siteFromResponse->getId()),
                $siteFromResponse->getName(),
                $client,
                new \DateTimeImmutable()
            );

            $this->sites->add($newSite);

            $this->syncProductGroups($newSite, $siteFromResponse->getGroups());
        }

        foreach ($sites as $site) {
            $this->sites->remove($site);
        }

        $this->flusher->flush();
    }

    /**
     * @param Site $site
     * @param \App\Service\ResponseCRM\Contract\Type\ProductGroup[]|ArrayCollection $productGroupsFromResponse
     * @throws \Exception
     */
    private function syncProductGroups(Site $site, ArrayCollection $productGroupsFromResponse): void
    {
        $client = $site->client();

        foreach ($productGroupsFromResponse as $productGroupFromResponse) {
            $productGroup = $this->productGroups->find(new ProductGroupId($productGroupFromResponse->getId()));

            if ($productGroup !== null) {
                if ($productGroupFromResponse->getName() !== $productGroup->name()) {
                    $productGroup->edit($productGroupFromResponse->getName());
                }

                $site->addProductGroup($productGroup);

                $this->syncCharges($productGroup, $productGroupFromResponse->getCharges());
                continue;
            }

            $newProductGroup = new ProductGroup(
                new ProductGroupId($productGroupFromResponse->getId()),
                $client,
                $productGroupFromResponse->getName(),
                $productGroupFromResponse->getProductGroupGUID(),
                new \DateTimeImmutable()
            );

            $this->productGroups->add($newProductGroup);
            $site->addProductGroup($newProductGroup);

            $this->syncCharges($newProductGroup, $productGroupFromResponse->getCharges());
        }

        foreach ($site->productGroups() as $productGroup) {
            foreach ($productGroupsFromResponse as $productGroupFromResponse) {
                if ($productGroup->id()->getValue() === $productGroupFromResponse->getId()) {
                    continue 2;
                }
            }

            $site->removeProductGroup($productGroup);

            if (count($productGroup->sites()) === 0) {
                $this->productGroups->remove($productGroup);
            }
        }
    }

    /**
     * @param ProductGroup $productGroup
     * @param \App\Service\ResponseCRM\Contract\Type\Charge[]|ArrayCollection $chargesFromResponse
     * @throws \Exception
     */
    private function syncCharges(ProductGroup $productGroup, ArrayCollection $chargesFromResponse): void
    {
        foreach ($chargesFromResponse as $chargeFromResponse) {
            $charge = $this->charges->find(new ChargeId($chargeFromResponse->getId()));

            if ($charge !== null) {
                if (
                    $chargeFromResponse->getName() !== $charge->name() ||
                    $chargeFromResponse->getType() !== $charge->type() ||
                    $chargeFromResponse->getAmount() !== $charge->amount()
                ) {
                    $charge->edit(
                        $chargeFromResponse->getName(),
                        $chargeFromResponse->getType(),
                        $chargeFromResponse->getAmount()
                    );
                }

                $this->syncRecurringCycles($charge, $chargeFromResponse->getRecurringCycles());

                continue;
            }

            $newCharge = new Charge(
                new ChargeId($chargeFromResponse->getId()),
                $productGroup,
                $chargeFromResponse->getName(),
                $chargeFromResponse->getType(),
                $chargeFromResponse->getAmount(),
                new \DateTimeImmutable()
            );

            $this->charges->add($newCharge);
            $this->syncRecurringCycles($newCharge, $chargeFromResponse->getRecurringCycles());
        }

        foreach ($this->charges->allByProductGroupId($productGroup->id()) as $charge) {
            foreach ($chargesFromResponse as $chargeFromResponse) {
                if ($charge->id()->getValue() === $chargeFromResponse->getId()) {
                    continue 2;
                }
            }

            $this->charges->remove($charge);
        }
    }

    /**
     * @param Charge $charge
     * @param \App\Service\ResponseCRM\Contract\Type\RecurringCycle[]|ArrayCollection $recurringCyclesFromResponse
     */
    private function syncRecurringCycles(Charge $charge, ArrayCollection $recurringCyclesFromResponse): void
    {
        $needsUpdate = false;

        foreach ($recurringCyclesFromResponse as $recurringCycleFromResponse) {
            foreach ($charge->recurringCycles() as $recurringCycle) {
                if (
                    $recurringCycleFromResponse->getCycleNum() === $recurringCycle->cycleNum() &&
                    (
                        $recurringCycleFromResponse->getAmount() !== $recurringCycle->amount() ||
                        $recurringCycleFromResponse->getDelay() !== $recurringCycle->delay() ||
                        $recurringCycleFromResponse->isShippable() !== $recurringCycle->isShippable()
                    )
                ) {
                    $needsUpdate = true;
                    break 2;
                }
            }
        }

        if ($needsUpdate || count($recurringCyclesFromResponse) !== count($charge->recurringCycles())) {
            $newRecurringCycles = [];

            foreach ($recurringCyclesFromResponse as $recurringCycleFromResponse) {
                $newRecurringCycles[] = new RecurringCycle(
                    $recurringCycleFromResponse->getCycleNum(),
                    $recurringCycleFromResponse->getAmount(),
                    $recurringCycleFromResponse->getDelay(),
                    $recurringCycleFromResponse->isShippable()
                );
            }

            $charge->updateRecurringCycles($newRecurringCycles);
        }
    }
}
