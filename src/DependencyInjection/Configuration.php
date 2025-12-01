<?php

declare(strict_types=1);

namespace Wundii\DataMapper\SymfonyBundle\DependencyInjection;

use DateTime;
use DateTimeInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Wundii\DataMapper\Enum\AccessibleEnum;
use Wundii\DataMapper\Enum\ApproachEnum;

class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder<'array'>
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $approach = array_map(
            static fn (ApproachEnum $approachEnum): string => $approachEnum->name,
            ApproachEnum::cases()
        );
        $accessible = array_map(
            static fn (AccessibleEnum $accessibleEnum): string => $accessibleEnum->name,
            AccessibleEnum::cases()
        );

        $treeBuilder = new TreeBuilder('data_mapper');

        $treeBuilder->getRootNode()
            ->children()
            ->enumNode('approach')
            ->values($approach)
            ->defaultValue('SETTER')
            ->end()
            ->enumNode('accessible')
            ->values($accessible)
            ->defaultValue('PUBLIC')
            ->end()
            ->arrayNode('class_map')
            ->useAttributeAsKey('interface')
            ->scalarPrototype()->end()
            ->defaultValue([
                DateTimeInterface::class => DateTime::class,
            ])
            ->end()
            ->end();

        return $treeBuilder;
    }
}
