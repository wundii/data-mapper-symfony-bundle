<?php

declare(strict_types=1);

namespace Wundii\DataMapper\SymfonyBundle\Resources\Config;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator;
use Wundii\DataMapper\DataConfig;
use Wundii\DataMapper\DataMapper;
use Wundii\DataMapper\SymfonyBundle\Command\DefaultConfigCommand;

return function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(DataMapper::class)
        ->arg('$dataConfig', new ReferenceConfigurator(DataConfig::class))
        ->public();

    $services->set(DefaultConfigCommand::class)
        ->autowire()
        ->autoconfigure()
        ->public();
};
