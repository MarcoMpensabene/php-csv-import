<?php
require_once './database.php';

// Import CSV file  //Lo script controlla se il modulo HTML ha inviato una richiesta POST con il pulsante "import" premuto.
// Controlla anche se il tipo di importazione (import_type) è stato specificato (può essere "products" o "categories").
if (isset($_POST["import"]) && isset($_POST["import_type"])) {
    $importType = $_POST["import_type"];

    //Qui si verifica se il file CSV è stato caricato senza errori (error == 0 indica un caricamento riuscito).
    if ($_FILES["file"]["error"] == 0) {
        $file = fopen($_FILES["file"]["tmp_name"], "r"); //Il file viene aperto in modalità lettura ("r").
        fgetcsv($file); // utilizzato per saltare la prima riga dove assumiamo ci sia intestazione colonne

        //Se l'utente ha selezionato l'importazione dei prodotti
        if ($importType == "products") {
            $conn->exec("DELETE FROM products"); //elimina tutti i dati della tabella products rimuovendo dati già esistenti
            $stmt = $conn->prepare("INSERT INTO products (id, name, price, category_id) VALUES (?, ?, ?, ?)"); //query per inserire i dati nel database 

            //legge il csv riga per riga tramite ciclo while 
            while (($data = fgetcsv($file, 1000, ",")) !== FALSE) { //estrae una riga dal csv e la converte in un array di max 1000 char per riga
                if (count($data) == 4) { // Assicuriamoci che il CSV abbia 4 colonne
                    $stmt->execute([$data[0], $data[1], $data[2], $data[3]]); // se la riga ha 4 colonne vengono inseriti i dati 
                }
            }
            //se viene scelto category
        } elseif ($importType == "categories") {
            $conn->exec("DELETE FROM categories"); // Tutte le categorie vengono eliminate (DELETE FROM categories).
            $stmt = $conn->prepare("INSERT INTO categories (id, name) VALUES (?, ?)"); // Viene preparata una query per inserire i nuovi dati.

            while (($data = fgetcsv($file, 1000, ",")) !== FALSE) { // lettura riga 
                if (count($data) == 2) { // Assicuriamoci che il CSV abbia 2 colonne
                    $stmt->execute([$data[0], $data[1]]); // se la riga ha 2 colonne vengono inseriti i dati 
                }
            }
        }

        fclose($file); //chiusura del file al termine dell'import
    }
}
