<?php

declare(strict_types=1);

namespace Wundii\DataMapper\SymfonyBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;
use Wundii\DataMapper\DataConfig;
use Wundii\DataMapper\DataMapper;
use Wundii\DataMapper\Enum\AccessibleEnum;
use Wundii\DataMapper\Enum\ApproachEnum;

class DataMapperExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $approach = ApproachEnum::{$config['approach']};
        $accessibleEnum = AccessibleEnum::{$config['accessible']};
        $classMap = $config['class_map'];

        $dataConfigDef = new Definition(DataConfig::class, [
            $approach,
            $accessibleEnum,
            $classMap,
        ]);
        $container->setDefinition(DataConfig::class, $dataConfigDef);

        $dataMapperDef = new Definition(DataMapper::class, [
            $dataConfigDef
        ]);
        $container->setDefinition(DataMapper::class, $dataMapperDef);
    }

    public function getXsdValidationBasePath(): string|false
    {
        return __DIR__.'/../Resources/config/schema';
    }
}