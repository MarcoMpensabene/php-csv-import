<?php
require_once 'database.php';
require_once './script/import.php';
require_once './script/export.php';



// elemenTI DA MOSTRARE 
$queryProduct = $conn->query("SELECT p.id, p.name, p.price, c.name as category 
                       FROM products p 
                       JOIN categories c ON p.category_id = c.id 
                       ORDER BY p.id");
$products = $queryProduct->fetchAll(PDO::FETCH_ASSOC); // fetchAll(PDO::FETCH_ASSOC) recupera array associativo.

$queryCategory = $conn->query("SELECT * FROM categories ORDER BY id");
$categories = $queryCategory->fetchAll(PDO::FETCH_ASSOC);
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