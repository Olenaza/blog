<?php

namespace Olenaza\BlogBundle\Menu;

use Knp\Menu\FactoryInterface;

class MenuBuilder
{
    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        $menu->addChild('Home', ['route' => 'welcome']);
        $menu->addChild('Posts', ['route' => 'posts_list']);
        $menu->addChild('About', ['route' => 'about']);

        return $menu;
    }

    public function createBottomMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        $menu->addChild('Dashboard', ['route' => 'sonata_admin_redirect']);

        return $menu;
    }
}
