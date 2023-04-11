<?php

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

$server_name = "localhost";
$user_name = "root";
$password = "";
$database = "database";
$connection = mysqli_connect($server_name, $user_name, $password, $database);

if (!$connection) {
    die("Failed " . mysqli_connect_error());
}

function executeQuery($query)
{
    global $connection;
    return mysqli_query($connection, $query);
}

function fetchAllProducts()
{
    global $connection;
    $query = "SELECT * FROM product";
    $result = executeQuery($query);

    if (!$result) {
        return ['success' => false, 'message' => "Error: " . $connection->error];
    }

    $products = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }

    return ['success' => true, 'products' => $products];
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'fetch_all_products') {
    $response = fetchAllProducts();
    echo json_encode($response);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #444;
            margin-top: 5px;
        }

        th,
        td {
            border: 1px solid #aaa;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #444;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <!-- Navigation bar -->
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="add-product.php">Add Product</a>
        <a href="view-products.php">View Products</a>
        <a href="add-order.php">Add Order</a>
        <a href="view-orders.php">View Orders</a>
        <a href="seed.php">Seed Data</a>
    </div>
    <div id="result"></div>
    <script>
        function loadAllProducts() {
            fetch("view-products.php?action=fetch_all_products")
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success && data.products.length > 0) {
                        handleResponse(data);
                    } else {
                        handleResponse({ success: true, products: null });
                    }
                })
                .catch((error) => console.error("Error:", error));
        }

        function handleResponse(response) {
            console.log("Response:", response);
            const resultDiv = document.getElementById('result');

            if (response.success) {
                if (response.products) {
                    displayProducts(response.products);
                } else {
                    resultDiv.innerHTML = `<h1>There are no products to display</h1>`;
                }
            } else {
                resultDiv.innerHTML = response.message;
            }
        }

        function handleResponse(response) {
            const resultDiv = document.getElementById('result');

            if (response.success) {
                if (response.products) {
                    displayProducts(response.products);
                } else {
                    resultDiv.innerHTML = `<h1>There are no products to display</h1>`
                }
            } else {
                resultDiv.innerHTML = response.message;
            }
        }
        function displayProducts(products) {
            const resultDiv = document.getElementById('result');
            let tableHTML = '<table><tr><th>ID</th><th>Name</th><th>Description</th><th>Price</th><th>Image-URL</th><th>Created_at</th></tr>';

            for (const product of products) {
                tableHTML += `<tr>
            <td>${product.id}</td>
            <td>${product.name}</td>
            <td>${product.description}</td>
            <td>${product.price}</td>
            <td>${product.image_url}</td>
            <td>${product.created_at}</td>
        </tr>`;
            }

            tableHTML += '</table>';
            resultDiv.innerHTML = tableHTML;
        }

        document.addEventListener("DOMContentLoaded", loadAllProducts);

    </script>
</body>

</html>