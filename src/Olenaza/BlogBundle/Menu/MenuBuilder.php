<?php

namespace Olenaza\BlogBundle\Menu;

use Knp\Menu\FactoryInterface;
use Olenaza\BlogBundle\Repository\CategoryRepository;

class MenuBuilder
{
    private $factory;

    private $categoryRepository;

    public function __construct(FactoryInterface $factory, CategoryRepository $categoryRepository)
    {
        $this->factory = $factory;
        $this->categoryRepository = $categoryRepository;
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

        $menu->addChild('Домівка', [
            'route' => 'welcome',
        ]);

        $menu->addChild('Подорожні нотатки', [
                'uri' => '#',
                'linkAttributes' => [
                    'class' => 'dropdown-toggle',
                    'data-toggle' => 'dropdown',
                ],
                'childrenAttributes' => [
                    'class' => 'dropdown-menu',
                ],
                'attributes' => [
                    'class' => 'dropdown',
                ],
            ])
        ;

        $menu['Подорожні нотатки']->addChild('Усі записи', [
            'route' => 'posts_list',
        ]);

        $rootCategories = $this->categoryRepository
            ->getRootNodes();

        foreach ($rootCategories as $rootCategory) {
            $menu['Подорожні нотатки']->addChild($rootCategory->getTitle());

            $childCategories = $rootCategory->getChildren();

            foreach ($childCategories as $childCategory) {
                $menu['Подорожні нотатки']->addChild($childCategory->getTitle(), [
                    'route' => 'posts_list_by_category',
                    'routeParameters' => ['categorySlug' => $childCategory->getSlug()],
                ]);
            }
        }

        $menu->addChild('Про автора', [
            'route' => 'about', ]);

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
