<?php

declare(strict_types=1);

namespace Wundii\DataMapper\SymfonyBundle\src\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Wundii\DataMapper\DataConfig;
use Wundii\DataMapper\DataMapper;
use Wundii\DataMapper\Enum\AccessibleEnum;
use Wundii\DataMapper\Enum\ApproachEnum;

class DataMapperExtension extends Extension
{
    /**
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.php');

        $approachEnum = ApproachEnum::{$config['approach']};
        $accessibleEnum = AccessibleEnum::{$config['accessible']};
        $classMap = $config['class_map'];

        $dataConfigDef = new Definition(DataConfig::class, [
            $approachEnum,
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