<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Wundii\DataMapper\SymfonyBundle\DataMapperBundle;
use Wundii\DataMapper\SymfonyBundle\DependencyInjection\DataMapperExtension;

final class DataMapperBundleTest extends TestCase
{
    public function testBundleReturnsCorrectExtension(): void
    {
        $bundle = new DataMapperBundle();
        $extension = $bundle->getContainerExtension();
        self::assertInstanceOf(DataMapperExtension::class, $extension);
    }
}
