<?php

declare(strict_types=1);

namespace Wundii\DataMapper\SymfonyBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DefaultConfigCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('data-mapper:default-config');
        $this->setDescription('Create a default configuration file for the DataMapper bundle: config/packages/data_mapper.yaml');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $configDir = getcwd() . '/config/packages';
        $configFile = $configDir . '/data_mapper.yaml';

        if (! is_dir($configDir)) {
            mkdir($configDir, 0775, true);
        }

        if (file_exists($configFile)) {
            $symfonyStyle->warning('The file config/packages/data_mapper.yaml already exists. No changes were made.');
            return Command::FAILURE;
        }

        $defaultConfig = file_get_contents(__DIR__ . '/../Resources/config/packages/data_mapper.yaml');
        if ($defaultConfig === false) {
            $symfonyStyle->error('Could not read the default configuration file.');
            return Command::FAILURE;
        }

        file_put_contents($configFile, $defaultConfig);
        $symfonyStyle->success('Default configuration file created at config/packages/data_mapper.yaml');

        return Command::SUCCESS;
    }
}
