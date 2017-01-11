<?php

namespace Olenaza\BlogBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MenuBuilder
{
    private $factory;

    private $container;

    public function __construct(FactoryInterface $factory, ContainerInterface $container)
    {
        $this->factory = $factory;
        $this->container = $container;
    }

    /**
     * Create main menu.
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function createMainMenu()
    {
        $menu = $this->factory->createItem('root');

        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        $menu->addChild('Home', ['route' => 'welcome']);

        $menu->addChild('Posts', [
            'route' => 'posts_list',
        ]);

        $rootCategories = $this->container
            ->get('doctrine')
            ->getRepository('OlenazaBlogBundle:Category')
            ->getRootNodes();

        foreach ($rootCategories as $rootCategory) {
            $menu['Posts']->addChild($rootCategory->getTitle());

            $childCategories = $rootCategory->getChildren();

            foreach ($childCategories as $childCategory) {
                $menu['Posts']->addChild($childCategory->getTitle(), [
                    'route' => 'posts_list_by_category',
                    'routeParameters' => ['categoryId' => $childCategory->getId()],
                ]);
            }
        }

        $menu->addChild('About', ['route' => 'about']);

        return $menu;
    }

    /**
     * Create bottom menu.
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function createBottomMenu()
    {
        $menu = $this->factory->createItem('root');

        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        $menu->addChild('Dashboard', ['route' => 'sonata_admin_redirect']);

        return $menu;
    }
}
