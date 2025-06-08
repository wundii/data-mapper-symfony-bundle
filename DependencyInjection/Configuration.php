<?php

namespace Wundii\DataMapper\SymfonyBundle\DependencyInjection;

use DateTime;
use DateTimeInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Wundii\DataMapper\Enum\AccessibleEnum;
use Wundii\DataMapper\Enum\ApproachEnum;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('data_mapper');
        $treeBuilder->getRootNode()
            ->children()
                ->enumNode('approach')
                    ->values(ApproachEnum::cases())
                    ->defaultValue('SETTER')
                ->end()
                ->enumNode('accessible')
                    ->values(AccessibleEnum::cases())
                    ->defaultValue('PUBLIC')
                ->end()
                ->arrayNode('class_map')
                    ->useAttributeAsKey('interface')
                    ->scalarPrototype()->end()
                    ->defaultValue([
                        DateTimeInterface::class => DateTime::class
                    ])
                ->end()
            ->end();

        return $treeBuilder;
    }
}