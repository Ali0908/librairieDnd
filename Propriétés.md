<?php


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
         // TODO : vérifier que cette ligne code permet de bien prendre en charge du contenu HTML
        $newDescription=$description . <<<EOT
        EOT;
        $object->description=$newDescription;
        
        //Retrieval in a new variable of the price and currency object
        $price=$object->price;

        //Display the price as a decimal value using a comma as a separator and concatenation of the currency
        $newFormatPrice= number_format($price, 1, ',', ' ') . $object->currency;
        $object->price=$newFormatPrice;
        
        //Deletion of the currency line
        unset($object->currency);
        
        // dd($object);
        // exit;
        
         /**
          *  TODO : écrire le contenu de $slug en minuscules  sans espace (à remplacer par un _), sans caractères spéciaux (à remplacer par un -)
            * créer un dossier service avec une classe MySlugger https://symfony.com/doc/current/components/string.html#slugger 
            * 
          */

        $slugRecovery=$object->title;
        $slugger= new AsciiSlugger();
        $slug=$slugger->slug();
        //  #string: "Cornelia-the-dark-unicorn"


        dd($slug);
        exit;