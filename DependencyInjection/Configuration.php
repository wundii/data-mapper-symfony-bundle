<?php

namespace Wundii\DataMapper\SymfonyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('datamapper');

        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
            ->integerNode('max_items')
            ->info('Max number of displayed items past the first level, -1 means no limit.')
            ->min(-1)
            ->defaultValue(2500)
            ->end()
            ->integerNode('min_depth')
            ->info('Minimum tree depth to clone all the items, 1 is default.')
            ->min(0)
            ->defaultValue(1)
            ->end()
            ->integerNode('max_string_length')
            ->info('Max length of displayed strings, -1 means no limit.')
            ->min(-1)
            ->defaultValue(-1)
            ->end()
            ->scalarNode('dump_destination')
            ->info('A stream URL where dumps should be written to.')
            ->example('php://stderr, or tcp://%env(VAR_DUMPER_SERVER)% when using the "server:dump" command')
            ->defaultNull()
            ->end()
            ->enumNode('theme')
            ->info('Changes the color of the dump() output when rendered directly on the templating. "dark" (default) or "light".')
            ->example('dark')
            ->values(['dark', 'light'])
            ->defaultValue('dark')
            ->end()
        ;

        return $treeBuilder;
    }
}