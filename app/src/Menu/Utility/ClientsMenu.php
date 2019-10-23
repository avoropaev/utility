<?php

declare(strict_types=1);

namespace App\Menu\Utility;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ClientsMenu
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
     * ClientsMenu constructor.
     * @param FactoryInterface $factory
     * @param AuthorizationCheckerInterface $auth
     */
    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $auth)
    {
        $this->factory = $factory;
        $this->auth = $auth;
    }

    /**
     * @return ItemInterface
     */
    public function build(): ItemInterface
    {
        $menu = $this->factory->createItem('root')
            ->setChildrenAttributes(['class' => 'nav nav-tabs mb-4']);

        $menu
            ->addChild('Clients', ['route' => 'utility.clients'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');

        $menu
            ->addChild('Sites', ['route' => 'utility.clients.sites'])
            ->setExtra('routes', [
                ['route' => 'utility.clients.sites'],
                ['pattern' => '/^utility.clients.sites\..+/']])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');

        $menu
            ->addChild('Product Groups', ['route' => 'utility.clients.product_groups'])
            ->setExtra('routes', [
                ['route' => 'utility.clients.product_groups'],
                ['pattern' => '/^utility.clients.product_groups\..+/']])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');

        return $menu;
    }
}
