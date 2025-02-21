<?php

require_once './database.php';

if (isset($_POST["export"])) {
    $exportType = $_POST["export_type"];

    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename={$exportType}.csv");

    $output = fopen("php://output", "w");

    if ($exportType == "products") {
        fputcsv($output, ["id", "name", "price", "category_id"]);
        $stmt = $conn->query("SELECT * FROM products");
    } else {
        fputcsv($output, ["id", "name"]);
        $stmt = $conn->query("SELECT * FROM categories");
    }

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
}