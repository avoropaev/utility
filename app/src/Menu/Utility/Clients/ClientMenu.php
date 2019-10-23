<?php

declare(strict_types=1);

namespace App\Menu\Utility\Clients;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ClientMenu
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $auth;

    /**
     * ClientMenu constructor.
     * @param FactoryInterface $factory
     * @param AuthorizationCheckerInterface $auth
     */
    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $auth)
    {
        $this->factory = $factory;
        $this->auth = $auth;
    }

    /**
     * @param array $options
     * @return ItemInterface
     */
    public function build(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root')
            ->setChildrenAttributes(['class' => 'nav nav-tabs mb-4']);

        $menu
            ->addChild('Dashboard', [
                'route' => 'utility.clients.client.show',
                'routeParameters' => ['id' => $options['client_id']]
            ])
            ->setExtra('routes', [
                ['route' => 'utility.clients.client.show'],
                ['pattern' => '/^utility.clients.client.show\..+/']
            ])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');

        $menu
            ->addChild('Sites', [
                'route' => 'utility.clients.client.sites',
                'routeParameters' => ['client_id' => $options['client_id']]
            ])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');

        $menu
            ->addChild('Product  Groups', [
                'route' => 'utility.clients.client.product_groups',
                'routeParameters' => ['client_id' => $options['client_id']]
            ])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');

        return $menu;
    }
}
