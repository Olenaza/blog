<?php

namespace Olenaza\BlogBundle\Extension\Twig;

class BlogTwigExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('add_ellipsis', array($this, 'addEllipsisFilter')),
        );
    }

    public function addEllipsisFilter($string)
    {
        return $string.'...';
    }
}
