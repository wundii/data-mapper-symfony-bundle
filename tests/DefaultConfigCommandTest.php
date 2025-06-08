<?php

declare(strict_types=1);

namespace Wundii\DataMapper\SymfonyBundle\Tests;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wundii\DataMapper\SymfonyBundle\Command\DefaultConfigCommand;

class DefaultConfigCommandTest extends TestCase
{
    private string $tempDir;

    private string $configDir;

    private string $configFile;

    private string $defaultConfigFile;

    protected function setUp(): void
    {
        $this->tempDir = sys_get_temp_dir() . '/datamapper_test_' . uniqid();
        mkdir($this->tempDir, 0777, true);

        $this->configDir = $this->tempDir . '/config/packages';
        $this->configFile = $this->configDir . '/data_mapper.yaml';

        $this->defaultConfigFile = dirname(__DIR__) . '/src/Resources/config/packages/data_mapper.yaml';
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->tempDir);
    }

    /**
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function testCreatesDefaultConfigFileSuccessfully(): void
    {
        chdir($this->tempDir); // Wechsel zum temporären Arbeitsverzeichnis

        $command = new DefaultConfigCommand();

        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        $result = $command->run($input, $output);

        $this->assertSame(Command::SUCCESS, $result);
        $this->assertFileExists($this->configFile);
        $this->assertSame(
            file_get_contents($this->defaultConfigFile),
            file_get_contents($this->configFile)
        );
    }

    /**
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function testWarnsIfConfigAlreadyExists(): void
    {
        chdir($this->tempDir);

        mkdir($this->configDir, 0777, true);
        file_put_contents($this->configFile, "existing: true\n");

        $command = new DefaultConfigCommand();

        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        $result = $command->run($input, $output);

        $this->assertSame(Command::FAILURE, $result);
        $this->assertSame("existing: true\n", file_get_contents($this->configFile));
    }

    private function removeDirectory(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = "{$dir}/{$file}";
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                unlink($path);
            }
        }

        rmdir($dir);
    }
}
