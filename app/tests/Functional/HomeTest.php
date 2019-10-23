<?php

declare(strict_types=1);

namespace App\Tests\Functional;

class HomeTest extends DbWebTestCase
{
    public function testGuest(): void
    {
        $this->client->request('GET', '/');

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame('http://localhost/login', $this->client->getResponse()->headers->get('Location'));
    }

    public function testAdmin(): void
    {
        $this->client->setServerParameters(AuthFixture::adminCredentials());
        $crawler = $this->client->request('GET', '/');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Dashboard', $crawler->filter('title')->text());
    }
}
