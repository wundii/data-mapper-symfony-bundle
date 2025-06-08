<?php

declare(strict_types=1);

namespace Wundii\DataMapper\SymfonyBundle\src;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Wundii\DataMapper\SymfonyBundle\src\DependencyInjection\DataMapperExtension;

class DataMapperBundle extends Bundle
{
    public function getContainerExtension(): ExtensionInterface
    {
        return new DataMapperExtension();
    }
}