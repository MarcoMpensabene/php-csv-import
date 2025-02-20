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

$query = $conn->query("SELECT p.id, p.name, p.price, c.name as category 
                       FROM products p 
                       JOIN categories c ON p.category_id = c.id 
                       ORDER BY p.id");
$products = $query->fetchAll(PDO::FETCH_ASSOC);

$queryCat = $conn->query("SELECT * FROM categories ORDER BY id");
$categories = $queryCat->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV Management</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        form {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <h2>Import & Export CSV</h2>

    <!-- Upload CSV Form -->
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <select name="import_type">
            <option value="products">Import Products</option>
            <option value="categories">Import Categories</option>
        </select>
        <button type="submit" name="import">Import CSV</button>
    </form>

    <!-- Export CSV Form -->
    <form method="post">
        <select name="export_type">
            <option value="products">Export Products</option>
            <option value="categories">Export Categories</option>
        </select>
        <button type="submit" name="export">Export CSV</button>
    </form>

    <h3>Products</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price (€)</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['id']) ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['price']) ?></td>
                    <td><?= htmlspecialchars($product['category']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Categories</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?= htmlspecialchars($category['id']) ?></td>
                    <td><?= htmlspecialchars($category['name']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>