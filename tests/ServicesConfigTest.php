<?php

declare(strict_types=1);

namespace Wundii\DataMapper\SymfonyBundle\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Wundii\DataMapper\DataConfig;
use Wundii\DataMapper\DataMapper;
use Wundii\DataMapper\SymfonyBundle\Command\DefaultConfigCommand;

class ServicesConfigTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testServicesAreLoadedCorrectly(): void
    {
        $container = new ContainerBuilder();
        $container->register(DataConfig::class);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../src/Resources/config'));
        $loader->load('services.php');

        $container->compile();

        $this->assertTrue($container->has(DataMapper::class), 'DataMapper service is not registered.');
        $dataMapperDef = $container->getDefinition(DataMapper::class);
        $this->assertTrue($dataMapperDef->isPublic(), 'DataMapper service is not public.');

        $this->assertEquals(DataConfig::class, $dataMapperDef->getArgument(0)?->getClass(), 'DataMapper should be initialized with DataConfig.');

        $this->assertTrue($container->has(DefaultConfigCommand::class), 'DefaultConfigCommand is not registered.');
        $commandDef = $container->getDefinition(DefaultConfigCommand::class);
        $this->assertTrue($commandDef->isAutowired(), 'DefaultConfigCommand should be autowired.');
        $this->assertTrue($commandDef->isAutoconfigured(), 'DefaultConfigCommand should be autoconfigured.');
        $this->assertTrue($commandDef->isPublic(), 'DefaultConfigCommand should be public.');
    }
}
