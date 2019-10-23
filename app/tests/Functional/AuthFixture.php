<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AuthFixture extends Fixture
{
    /**
     * @return array
     */
    public static function adminCredentials(): array
    {
        return [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ];
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {

    }
}
