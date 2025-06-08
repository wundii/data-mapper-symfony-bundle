<?php

declare(strict_types=1);

namespace Wundii\DataMapper\SymfonyBundle\Resources\Config;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator;
use Wundii\DataMapper\DataConfig;
use Wundii\DataMapper\DataMapper;
use Wundii\DataMapper\SymfonyBundle\src\Command\DumpDefaultConfigCommand;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services();

    // $services->set(DataConfig::class)
    //     ->args([
    //         ApproachEnum::SETTER,
    //         AccessibleEnum::PUBLIC,
    //         [
    //             DateTimeInterface::class => DateTime::class,
    //         ]
    //     ]);

    $services->set(DataMapper::class)
        ->arg('$dataConfig', new ReferenceConfigurator(DataConfig::class))
        ->public();

    $services->set(DumpDefaultConfigCommand::class)
        ->autowire()
        ->autoconfigure()
        ->public();
};
