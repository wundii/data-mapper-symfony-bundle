<?php

declare(strict_types=1);

namespace Wundii\DataMapper\SymfonyBundle\src\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DumpDefaultConfigCommand extends Command
{
    protected static $defaultName = 'data-mapper:default-config';

    protected static $defaultDescription = 'Create a default configuration file for the DataMapper bundle: config/packages/data_mapper.yaml';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $configDir = getcwd().'/config/packages';
        $configFile = $configDir.'/data_mapper.yaml';

        if (!is_dir($configDir)) {
            mkdir($configDir, 0775, true);
        }

        if (file_exists($configFile)) {
            $io->warning('The file config/packages/data_mapper.yaml already exists. No changes were made.');
            return Command::FAILURE;
        }

        $defaultConfig = file_get_contents(__DIR__.'/../Resources/config/packages/data_mapper.yaml');
        if ($defaultConfig === false) {
            $io->error('Could not read the default configuration file.');
            return Command::FAILURE;
        }

        file_put_contents($configFile, $defaultConfig);
        $io->success('Default configuration file created at config/packages/data_mapper.yaml');

        return Command::SUCCESS;
    }
}
