<?php

namespace App\DataFixtures\Utility\Clients;

use App\Model\Utility\Entity\Clients\Client\Client;
use App\Model\Utility\Entity\Clients\Client\Id;
use App\Model\Utility\Entity\Clients\Client\SecretKey;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    public const REFERENCE_FIRST = 'first';
    public const REFERENCE_SECOND = 'second';

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $firstClient = new Client(
            new Id(1001),
            'First Client',
            new SecretKey('secret_ffffffffffffffffffffffffffffffff'),
            new \DateTimeImmutable()
        );

        $manager->persist($firstClient);
        $this->setReference(self::REFERENCE_FIRST, $firstClient);

        $secondClient = new Client(
            new Id(1002),
            'Second Client',
            new SecretKey('secret_fffffffffffffffffffffffffffffffe'),
            new \DateTimeImmutable()
        );

        $manager->persist($secondClient);
        $this->setReference(self::REFERENCE_SECOND, $secondClient);

        $manager->flush();
    }
}
