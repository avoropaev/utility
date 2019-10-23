<?php

declare(strict_types=1);

namespace App\Tests\Functional\Utility\Clients\Client;

use App\Tests\Builder\Utility\Clients\ClientBuilder;
use App\Tests\Functional\AuthFixture;
use App\Tests\Functional\DbWebTestCase;
use App\Tests\Functional\Utility\Clients\ClientsFixture;

class EditTest extends DbWebTestCase
{
    public function testGetGuest(): void
    {
        $this->client->request('GET', '/utility/clients/' . ClientsFixture::EXISTING_ID . '/edit');

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame('http://localhost/login', $this->client->getResponse()->headers->get('Location'));
    }

    public function testGetAdmin(): void
    {
        $this->client->setServerParameters(AuthFixture::adminCredentials());
        $crawler = $this->client->request('GET', '/utility/clients/' . ClientsFixture::EXISTING_ID . '/edit');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains(ClientsFixture::EXISTING_NAME . ' - Edit', $crawler->filter('title')->text());
    }

    public function testExists(): void
    {
        $this->client->setServerParameters(AuthFixture::adminCredentials());
        $this->client->request('GET', '/utility/clients/' . ClientBuilder::DEFAULT_ID . '/edit');

        $crawler = $this->client->submitForm('Edit', [
            'form[name]' => ClientsFixture::EXISTING_NAME,
        ]);

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->assertContains('Client already exists.', $crawler->filter('.alert.alert-danger')->text());
    }

    public function testEdit(): void
    {
        $this->client->setServerParameters(AuthFixture::adminCredentials());
        $this->client->request('GET', '/utility/clients/' . ClientsFixture::EXISTING_ID . '/edit');

        $this->client->submitForm('Edit', [
            'form[name]' => 'New name'
        ]);

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('New name', $crawler->filter('title')->text());
        $this->assertContains('Client successfully edited.', $crawler->filter('.alert.alert-success')->text());
    }

    public function testNotValid(): void
    {
        $this->client->setServerParameters(AuthFixture::adminCredentials());
        $this->client->request('GET', '/utility/clients/' . ClientsFixture::EXISTING_ID . '/edit');

        $crawler = $this->client->submitForm('Edit', [
            'form[name]' => ''
        ]);

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsString('is-invalid', $crawler
            ->filter('#form_name')->attr('class'));
    }

    public function testNotExists(): void
    {
        $this->client->setServerParameters(AuthFixture::adminCredentials());
        $this->client->request('GET', '/utility/clients/' . ClientsFixture::NOT_EXISTING_ID . '/edit');

        $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }
}
