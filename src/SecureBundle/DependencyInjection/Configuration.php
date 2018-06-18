<?php

namespace SecureBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('secure_bundle');

        $rootNode
            ->children()
            ->arrayNode('user_avatars')
            ->children()
            ->scalarNode('default')->end()
            ->scalarNode('default_man')->end()
            ->scalarNode('default_woman')->end()
            ->end()
            ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}