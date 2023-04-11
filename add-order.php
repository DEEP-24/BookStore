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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    $query = "INSERT INTO `order` (`product_id`) VALUES ('$productId')";
    $result = executeQuery($query);
    if (!$result) {
        echo "Error: " . mysqli_error($connection);
    } else {
        echo "Order placed successfully!";
    }
} else {
    echo "Invalid request!";
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
    <form id="orderForm">
        <label for="productSelect">Select a product:</label>
        <br>
        <div id="productSelectDiv"></div>
        <br>
        <input type="submit" value="Place Order">
    </form>
    <script>
        function loadAllProducts() {
            fetch("view-products.php?action=fetch_all_products")
                .then((response) => response.json()) -
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


        //display the products
        function displayProducts(products) {
            let selectHTML = '<select id="productSelect">';
            const resultDiv = document.getElementById('result');
            for (const product of products) {
                selectHTML += `<option value="${product.id}">${product.name} - $${product.price}</option>`;
            }
            selectHTML += '</select>';
            resultDiv.innerHTML = selectHTML;
        }

        const orderForm = document.getElementById('orderForm');
        orderForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const productId = document.getElementById('productSelect').value;
            fetch('add-order.php', {
                method: 'POST',
                body: new URLSearchParams({
                    'product_id': productId
                })
            })
                .then((response) => response.text())
                .then((data) => {
                    alert(data);
                })
                .catch((error) => console.error("Error:", error));
        });

        document.addEventListener("DOMContentLoaded", loadAllProducts);

    </script>
</body>

</html>