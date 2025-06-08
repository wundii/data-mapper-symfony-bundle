<?php

declare(strict_types=1);

namespace Wundii\DataMapper\SymfonyBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DumpDefaultConfigCommand extends Command
{
    protected static $defaultName = 'data-mapper:dump-default-config';

    protected static $defaultDescription = 'Lege eine Beispiel-Datei config/packages/data_mapper.yaml an.';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $configDir = getcwd().'/config/packages';
        $configFile = $configDir.'/data_mapper.yaml';

        if (!is_dir($configDir)) {
            mkdir($configDir, 0775, true);
        }

        if (file_exists($configFile)) {
            $io->warning('Die Datei config/packages/data_mapper.yaml existiert bereits.');
            return Command::FAILURE;
        }

        $defaultConfig = file_get_contents(__DIR__.'/../Resources/config/data_mapper.yaml');
        if ($defaultConfig === false) {
            $io->error('Die Standardkonfigurationsdatei konnte nicht geladen werden.');
            return Command::FAILURE;
        }

        file_put_contents($configFile, $defaultConfig);
        $io->success('Die Datei config/packages/data_mapper.yaml wurde erstellt.');

        return Command::SUCCESS;
    }
}
