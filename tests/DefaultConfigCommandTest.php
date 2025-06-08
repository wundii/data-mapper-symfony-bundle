<?php

declare(strict_types=1);

namespace Wundii\DataMapper\SymfonyBundle\Tests;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;
use Wundii\DataMapper\SymfonyBundle\Command\DefaultConfigCommand;

class DefaultConfigCommandTest extends TestCase
{
    private string $tempDir;

    private string $configDir;

    private string $configFile;

    private string $defaultConfigFile;

    private OutputInterface $output;

    protected function setUp(): void
    {
        $this->tempDir = sys_get_temp_dir() . '/datamapper_test_' . uniqid();
        mkdir($this->tempDir, 0777, true);

        $this->configDir = $this->tempDir . '/config/packages';
        $this->configFile = $this->configDir . '/data_mapper.yaml';

        $this->defaultConfigFile = dirname(__DIR__) . '/src/Resources/config/packages/data_mapper.yaml';

        $this->output = new StreamOutput(fopen('php://memory', 'w+'));
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->tempDir);
    }

    public function testDefaultInformation(): void
    {
        $command = new DefaultConfigCommand();

        $name = $command->getName();
        $description = $command->getDescription();

        $this->assertSame(
            'Create a default configuration file for the DataMapper bundle: config/packages/data_mapper.yaml',
            $description
        );
        $this->assertSame(
            'data-mapper:default-config',
            $name
        );
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

        $result = $command->run($input, $this->output);

        $this->assertSame(Command::SUCCESS, $result);
        $this->assertFileExists($this->configFile);
        $this->assertSame(
            file_get_contents($this->defaultConfigFile),
            file_get_contents($this->configFile)
        );

        $this->assertStringContainsString('Default configuration file created at config/packages/data_mapper.yaml', $this->getDisplay());
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

        $result = $command->run($input, $this->output);

        $this->assertSame(Command::FAILURE, $result);
        $this->assertSame("existing: true\n", file_get_contents($this->configFile));

        $this->assertStringContainsString('The file config/packages/data_mapper.yaml already exists. No changes were made.', $this->getDisplay());
    }

    public function getDisplay(): string
    {
        rewind($this->output->getStream());

        $display = (string) stream_get_contents($this->output->getStream());
        $display = str_replace(["\r\n", "\n"], '', $display);
        return preg_replace('/[ \t]+/', ' ', $display);
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
