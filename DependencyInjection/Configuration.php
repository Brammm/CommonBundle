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
                            ->defaultValue('brammm_common.app.templateguesser')
                        ->end()
                        ->scalarNode('default')
                            ->defaultValue('template')
                            ->validate()
                            ->ifNotInArray(['template', 'json'])
                                ->thenInvalid('Invalid default response "%s", options are "template" or "json"')
                            ->end()
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