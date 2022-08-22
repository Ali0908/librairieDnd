<?php

namespace App\Command;

use App\Entity\Product;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use League\Csv\Reader;
use Symfony\Component\String\Slugger\AsciiSlugger;



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
        //Set up a variable to improve the layout in the terminal
        $io = new SymfonyStyle($input, $output);
        $io->title("Bienvenue dans la librairie PHP de Dn'd");
        $filepath = $input->getArgument('filepath');

        //load the CSV document from a file path by using https://csv.thephpleague.com/
        $csv = Reader::createFromPath($filepath, 'r');
        $csv->setHeaderOffset(0);
        $csv->setDelimiter(';');


        $output->writeln('+--------+--------+---------+--------------------+------------------------------+-----------------------------+');
        $output->writeln('| Sku    | Status | Price   | Description        | Created At                   | Slug                        |');
        $output->writeln('+--------+--------+---------+--------------------+------------------------------+-----------------------------+');
        
        //Setting up a loop to retrieve data from the CSV file
        foreach ($csv->getRecords() as $record) {
                
                $product= (new Product ())
                ->setSku($record['sku'])  
                ->setTitle($record['title'])
                ->setIsEnabled($record['is_enabled'])
                ->setPrice($record['price'])
                ->setCurrency($record['currency'])
                ->setDescription($record['description'])
                ->setCreatedAt( new \DateTime($record['created_at']))
                ;
                
                $output->writeln(var_dump($product));       
        }
        
        
        $output->writeln('+--------+--------+---------+--------------------+------------------------------+-----------------------------+');
        $io->success('Command exited cleanly!');
        return Command::SUCCESS;
    }
}
