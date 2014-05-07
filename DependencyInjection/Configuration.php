<?php

namespace Brammm\CommonBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('brammm_common');

        $rootNode
            ->children()
                ->arrayNode('response')
                    ->children()
                        ->scalarNode('guesser')
                            ->defaultValue('brammm_common.templateguesser.app')
                        ->end()
                        ->enumNode('default')
                            ->defaultValue('template')
                            ->values(['template', 'json'])
                        ->end()
                        ->arrayNode('types')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
} 