<?php

declare(strict_types=1);

namespace App\Tests\Functional\Utility\Clients;

use App\Model\Utility\Entity\Clients\Client\Id;
use App\Model\Utility\Entity\Clients\Client\SecretKey;
use App\Tests\Builder\Utility\Clients\ClientBuilder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ClientsFixture extends Fixture
{
    public const EXISTING_ID = 111;
    public const EXISTING_NAME = 'Existing name';
    public const EXISTING_SECRET_KEY = 'secret_eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee';

    public const NOT_EXISTING_ID = 404;

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $existing = (new ClientBuilder())
            ->withId(new Id(self::EXISTING_ID))
            ->withName(self::EXISTING_NAME)
            ->withSecretKey(new SecretKey(self::EXISTING_SECRET_KEY))
            ->build();

        $manager->persist($existing);

        $show = (new ClientBuilder())->build();

        $manager->persist($show);

        $manager->flush();
    }
}
