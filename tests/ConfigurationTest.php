<?php

declare(strict_types=1);

namespace Wundii\DataMapper\SymfonyBundle\Tests;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use Wundii\DataMapper\SymfonyBundle\DependencyInjection\Configuration;

class ConfigurationTest extends TestCase
{
    public function testDefaultConfiguration(): void
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, []);

        $this->assertArrayHasKey('approach', $config);
        $this->assertArrayHasKey('accessible', $config);
        $this->assertArrayHasKey('class_map', $config);
        $this->assertSame('SETTER', $config['approach']);
        $this->assertSame('PUBLIC', $config['accessible']);
        $this->assertArrayHasKey(DateTimeInterface::class, $config['class_map']);
        $this->assertSame(DateTime::class, $config['class_map'][DateTimeInterface::class]);
    }

    public function testOverrideConfiguration(): void
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $input = [[
            'approach' => 'CONSTRUCTOR',
            'accessible' => 'PRIVATE',
            'class_map' => [
                DateTimeInterface::class => DateTimeImmutable::class,
                'Foo' => 'Bar',
            ],
        ]];

        $config = $processor->processConfiguration($configuration, $input);

        $this->assertSame('CONSTRUCTOR', $config['approach']);
        $this->assertSame('PRIVATE', $config['accessible']);
        $this->assertSame(DateTimeImmutable::class, $config['class_map'][DateTimeInterface::class]);
        $this->assertSame('Bar', $config['class_map']['Foo']);
    }
}
