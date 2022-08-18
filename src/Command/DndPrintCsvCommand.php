<?php

namespace App\Command;

use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use League\Csv\Reader;


// bin/console dnd:print-csv /var/www/html/Projects/librairieDnd/public/products.csv

class DndPrintCsvCommand extends Command
{
    protected static $defaultName = 'dnd:print-csv';
    protected static $defaultDescription = 'Displays a csv file content in console';

    protected function configure(): void
    {
        $this
            ->addArgument('filepath', InputArgument::OPTIONAL, 'Absolute path to your CSV file')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filepath = $input->getArgument('filepath');

        $csv = Reader::createFromPath($filepath, 'r');
        
        foreach ($csv as $line) {
            $io->info($line);
        }
        // dd($csv);

        return Command::SUCCESS;
    }
}
