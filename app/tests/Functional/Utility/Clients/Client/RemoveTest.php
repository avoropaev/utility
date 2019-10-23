<?php

declare(strict_types=1);

namespace App\Tests\Functional\Utility\Clients\Client;

use App\Tests\Builder\Utility\Clients\ClientBuilder;
use App\Tests\Functional\AuthFixture;
use App\Tests\Functional\DbWebTestCase;
use App\Tests\Functional\Utility\Clients\ClientsFixture;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

class RemoveTest extends DbWebTestCase
{
    public function testDeleteGuest(): void
    {
        $this->client->request('POST', '/utility/clients/' . ClientsFixture::EXISTING_ID . '/delete');

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame('http://localhost/login', $this->client->getResponse()->headers->get('Location'));
    }

    public function testDeleteAdmin(): void
    {
        /** @var CsrfTokenManager $csrfTokenManager */
        $csrfTokenManager = static::$kernel->getContainer()->get('security.csrf.token_manager');

        $this->client->setServerParameters(AuthFixture::adminCredentials());
        $this->client->request('POST', '/utility/clients/' . ClientsFixture::EXISTING_ID . '/delete', [
            'token' => $csrfTokenManager->getToken('delete')->getValue()
        ]);

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Clients', $crawler->filter('title')->text());
        $this->assertContains('Client successfully removed.', $crawler->filter('.alert.alert-success')->text());
    }

    public function testNotExists(): void
    {
        $this->client->setServerParameters(AuthFixture::adminCredentials());
        $this->client->request('POST', '/utility/clients/' . ClientsFixture::NOT_EXISTING_ID . '/delete');

        $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }
}
