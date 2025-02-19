<?php
require_once 'database.php';
echo "Connected successfully<br>";

// Import CSV file 
if (isset($_POST["import"]) && isset($_POST["import_type"])) {
    $importType = $_POST["import_type"];

    if ($_FILES["file"]["error"] == 0) {
        $file = fopen($_FILES["file"]["tmp_name"], "r");
        fgetcsv($file); // Skip header

        if ($importType == "products") {
            $conn->exec("DELETE FROM products");
            $stmt = $conn->prepare("INSERT INTO products (id, name, price, category_id) VALUES (?, ?, ?, ?)");

            while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
                if (count($data) == 4) { // Assicuriamoci che il CSV abbia 4 colonne
                    $stmt->execute([$data[0], $data[1], $data[2], $data[3]]);
                }
            }
        } elseif ($importType == "categories") {
            $conn->exec("DELETE FROM categories");
            $stmt = $conn->prepare("INSERT INTO categories (id, name) VALUES (?, ?)");

            while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
                if (count($data) == 2) { // Assicuriamoci che il CSV abbia 2 colonne
                    $stmt->execute([$data[0], $data[1]]);
                }
            }
        }

        fclose($file);
    }
}
// Export CSV file
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
