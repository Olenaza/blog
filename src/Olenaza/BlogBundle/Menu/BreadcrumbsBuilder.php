<?php

namespace Olenaza\BlogBundle\Menu;

use Olenaza\BlogBundle\Repository\CategoryRepository;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class BreadcrumbsBuilder
{
    private $categoryRepository;

    private $breadcrumbs;

    public function __construct(Breadcrumbs $breadcrumbs, CategoryRepository $categoryRepository)
    {
        $this->breadcrumbs = $breadcrumbs;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param $categorySlug
     * @param $tagName
     *
     * @return Breadcrumbs
     */
    public function createBreadcrumbs($categorySlug = null, $tagName = null)
    {
        if (!empty($categorySlug)) {
            $category = $this->categoryRepository
                ->findOneBy(['slug' => $categorySlug]);

            $breadcrumbs = $this->breadcrumbs
                ->prependRouteItem($category->getTitle(), 'posts_list_by_category', [
                    'slug' => $category->getSlug(),
                ])
                ->prependItem($category->getParent()->getTitle())
                ->prependRouteItem('Усі записи', 'posts_list')
                ->prependRouteItem('Домівка', 'welcome')
            ;
        } elseif (!empty($tagName)) {
            $breadcrumbs = $this->breadcrumbs
                ->addRouteItem('Домівка', 'welcome')
                ->addRouteItem('Усі записи', 'posts_list')
                ->addRouteItem("Тег $tagName", 'posts_list_by_tag', [
                    'name' => $tagName,
                ])
            ;
        } else {
            $breadcrumbs = $this->breadcrumbs
                ->addRouteItem('Домівка', 'welcome')
                ->addRouteItem('Усі записи', 'posts_list')
            ;
        }

        return $breadcrumbs;
    }
}
