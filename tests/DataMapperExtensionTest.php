<?php

declare(strict_types=1);

namespace Wundii\DataMapper\SymfonyBundle\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Wundii\DataMapper\DataConfig;
use Wundii\DataMapper\DataMapper;
use Wundii\DataMapper\Enum\AccessibleEnum;
use Wundii\DataMapper\Enum\ApproachEnum;
use Wundii\DataMapper\SymfonyBundle\DependencyInjection\DataMapperExtension;

class DataMapperExtensionTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testLoadRegistersServicesWithCorrectArguments(): void
    {
        $container = new ContainerBuilder();
        $extension = new DataMapperExtension();

        $configs = [[
            'approach' => 'SETTER',
            'accessible' => 'PUBLIC',
            'class_map' => [
                'App\Dto\UserDto' => 'App\Entity\User',
            ],
        ]];

        $extension->load($configs, $container);

        $this->assertTrue($container->hasDefinition(DataConfig::class));
        $this->assertTrue($container->hasDefinition(DataMapper::class));

        $dataConfigDef = $container->getDefinition(DataConfig::class);
        $args = $dataConfigDef->getArguments();

        $this->assertInstanceOf(ApproachEnum::class, $args[0]);
        $this->assertInstanceOf(AccessibleEnum::class, $args[1]);
        $this->assertIsArray($args[2]);
        $this->assertArrayHasKey('App\Dto\UserDto', $args[2]);
    }

    public function testGetXsdValidationBasePath(): void
    {
        $extension = new DataMapperExtension();
        $result = $extension->getXsdValidationBasePath();
        $this->assertIsString($result);
        $this->assertStringEndsWith('src/DependencyInjection/../Resources/config/schema', $result);
    }
}
