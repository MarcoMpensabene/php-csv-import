<?php

require_once './database.php';

if (isset($_POST["export"])) { // controlla se avviene la chiamata post export
    $exportType = $_POST["export_type"];

    header('Content-Type: text/csv; charset=utf-8'); // Indica che il file generato è un CSV.
    header("Content-Disposition: attachment; filename={$exportType}.csv"); // Fa sì che il file venga scaricato automaticamente con un nome dinamico (products.csv o categories.csv).

    $output = fopen("php://output", "w"); // è un flusso speciale che invia dati direttamente al browser, evitando di scrivere un file temporaneo.

    // in base alla scelta trasforma gli array in products o categories
    if ($exportType == "products") {
        fputcsv($output, ["id", "name", "price", "category_id"]); // trasformazione array in csv 
        $stmt = $conn->query("SELECT * FROM products");
    } else {
        fputcsv($output, ["id", "name"]);
        $stmt = $conn->query("SELECT * FROM categories"); // trasformazione array in csv 
    }

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // fetch(PDO::FETCH_ASSOC) recupera ogni riga come array associativo.
        fputcsv($output, $row); // scrive la riga nel file CSV.
    }

    fclose($output);
    exit;
}
