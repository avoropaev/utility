<?php

namespace App\DataFixtures\Utility\Clients;

use App\Model\Utility\Entity\Clients\Site\Id;
use App\Model\Utility\Entity\Clients\Site\Site;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class SiteFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $clients = [
            $this->getReference(ClientFixtures::REFERENCE_FIRST),
            $this->getReference(ClientFixtures::REFERENCE_SECOND)
        ];

        $date = new \DateTimeImmutable('-100 days');

        for ($i = 0; $i < 100; $i++) {
            $date = $date->modify('+' . $faker->numberBetween(1, 3) . 'days 3minutes');

            $site = new Site(
                new Id($i + 1),
                $faker->company,
                $faker->randomElement($clients),
                $date
            );

            $manager->persist($site);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            ClientFixtures::class
        ];
    }
}
