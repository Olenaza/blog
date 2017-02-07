<?php

namespace Olenaza\BlogBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('olenaza_blog');

        $rootNode
            ->children()
                ->integerNode('posts_per_page')
                    ->min(5)
                    ->defaultValue(5)
                ->end()
                ->integerNode('recent_posts_number')
                    ->info('This value is only used for welcome page')
                    ->min(1)->max(5)
                    ->defaultValue(3)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
