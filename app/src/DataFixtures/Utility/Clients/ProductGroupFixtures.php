<?php

namespace App\DataFixtures\Utility\Clients;

use App\Model\Utility\Entity\Clients\Client\Client;
use App\Model\Utility\Entity\Clients\ProductGroup\Charge\Charge;
use App\Model\Utility\Entity\Clients\ProductGroup\Charge\Id as ChargeId;
use App\Model\Utility\Entity\Clients\ProductGroup\Charge\RecurringCycle;
use App\Model\Utility\Entity\Clients\ProductGroup\Id;
use App\Model\Utility\Entity\Clients\ProductGroup\ProductGroup;
use App\Model\Utility\Entity\Clients\Site\Site;
use App\Model\Utility\Entity\Clients\Site\SiteRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Ramsey\Uuid\Uuid;

class ProductGroupFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var SiteRepository
     */
    private $sites;

    /**
     * ProductGroupFixtures constructor.
     * @param SiteRepository $sites
     */
    public function __construct(SiteRepository $sites)
    {
        $this->sites = $sites;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $date = new \DateTimeImmutable('-100 days');

        $chargeTypes = ['Signup Charge', 'Upsell Charge', 'Recurring Charge'];

        $productGroupId = 0;
        $chargeId = 0;

        /**
         * @var Client $firstClient
         * @var Client $secondClient
         */
        $firstClient = $this->getReference(ClientFixtures::REFERENCE_FIRST);
        $secondClient = $this->getReference(ClientFixtures::REFERENCE_SECOND);

        $sites = $this->sites->allByClientId($firstClient->id());
        $sites = array_merge($sites, $this->sites->allByClientId($secondClient->id()));

        /** @var Site $site */
        foreach ($sites as $site) {
            for ($i = 0; $i < rand(2, 5); $i++) {
                $productGroupId++;
                $date = $date->modify('+' . $faker->numberBetween(1, 3) . 'days 3minutes');

                $productGroup = new ProductGroup(
                    new Id($productGroupId),
                    $site->client(),
                    $faker->company,
                    Uuid::uuid4(),
                    $date
                );

                for ($ii = 0; $ii < rand(1, 5); $ii++) {
                    $chargeId++;
                    $date = $date->modify('+' . $faker->numberBetween(1, 5) . 'minutes');

                    $charge = new Charge(
                        new ChargeId($chargeId),
                        $productGroup,
                        $faker->company,
                        $faker->randomElement($chargeTypes),
                        $faker->randomFloat(2, 0, 200),
                        $date
                    );

                    $manager->persist($charge);

                    if ($charge->type() === 'Recurring Charge') {
                        $recurringCycles = [];

                        for ($iii = 1; $iii <= rand(2, 5); $iii++) {
                            $recurringCycle = new RecurringCycle(
                                $iii,
                                $faker->randomFloat(2, 0, 200),
                                $faker->randomElement([10, 15, 20, 30, 45]),
                                $faker->boolean(60)
                            );

                            $recurringCycles[] = $recurringCycle;
                        }

                        $charge->updateRecurringCycles($recurringCycles);
                    }
                }

                $manager->persist($productGroup);
                $site->addProductGroup($productGroup);

                if ($faker->boolean(30)) {
                    /** @var Site $randomSite */
                    $randomSite = $faker->randomElement($sites);
                    if ($site !== $randomSite) {
                        $randomSite->addProductGroup($productGroup);
                    }
                }
            }
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            SiteFixtures::class
        ];
    }
}
