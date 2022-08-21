<?php

namespace App\Command;

use DateTime;
use DateTimeInterface;
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
        $filepath = $input->getArgument('filepath');

        //load the CSV document from a file path by using https://csv.thephpleague.com/
        $csv = Reader::createFromPath($filepath, 'r');
        $csv->setHeaderOffset(0);
        
        //remove the semicolons from the csv file
        $csv->setDelimiter(';');
        
        //Setting up a loop to go through the rows of our table
        foreach ($csv->getRecords() as $record) {
        
            // https://www.php.net/manual/fr/datetime.modify.php
            // https://www.php.net/manual/fr/datetime.createfromformat.php
        
        // Transformation of array data into objects
        // TODO : vérifier pourquoi les données du second array n'ont pas été prises en compte
        $objectjson=json_encode($record);
        $object=json_decode($objectjson);
        
      
        //Retrieval in a new variable of the created_at object
        $createdAt=$object->created_at;

        //Transformation of the variable into 
        $newCreatedAt= new DateTime($createdAt);

        //Setting up the right format
        $newFormatCreatedAt= $newCreatedAt->format("l,d-M-Y H:i:s e");
        //$newFormatCreatedAt)="Wednesday,12-Dec-2018 10:34:39 Europe/Berlin";

        //transmission of the new data to our association table
        $object->created_at=$newFormatCreatedAt;
        
        //Retrieval in a new variable of the description object
        $description=$object->description;

        //Added the ability to embed HTML using the Heredoc syntax
        $newDescription=$description . <<<EOT
        EOT;
        $object->description=$newDescription;
        
        
        //Displaying data in an online format
        $output->writeln(print_r($record));
        }
        // ;
        return Command::SUCCESS;
    }
}
