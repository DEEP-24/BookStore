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

function fetchAllOrders()
{
    global $connection;
    $query = "SELECT `order`.*, `product`.`name` AS `product_name` FROM `order` JOIN `product` ON `order`.`product_id` = `product`.`id`";
    $result = executeQuery($query);

    if (!$result) {
        return ['success' => false, 'message' => "Error: " . $connection->error];
    }

    $orders = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }

    return ['success' => true, 'orders' => $orders];
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'fetch_all_orders') {
    $response = fetchAllOrders();
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
    <h3>Orders</h3>
    <div id="result"></div>
    <script>

        const navbarLinks = document.querySelectorAll('.navbar a');
        const path = window.location.pathname;

        const currentRoute = path.split('/')?.[2]

        for (let i = 0; i < navbarLinks.length; i++) {
            const link = navbarLinks[i];
            if (link.getAttribute('href') === currentRoute) {
                link.classList.add('active');
                break;
            }
        }

        function loadAllOrders() {
            fetch("view-orders.php?action=fetch_all_orders")
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success && data.orders.length > 0) {
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
                if (response.orders) {
                    displayOrders(response.orders);
                } else {
                    resultDiv.innerHTML = `<h1>There are no orders to display</h1>`;
                }
            } else {
                resultDiv.innerHTML = response.message;
            }
        }

        function displayOrders(orders) {
            const resultDiv = document.getElementById('result');
            let tableHTML = '<table><tr><th>OrderID</th><th>ProductName</th><th>Quantity</th><th>Total</th><th>Status</th><th>Created_at</th></tr>';

            for (const order of orders) {
                tableHTML += `<tr>
            <td>${order.id}</td>
            <td>${order.product_name}</td>
            <td>${order.quantity}</td>
            <td>${'$' + order.total}</td>
            <td>${order.status}</td>
            <td>${order.created_at}</td>
        </tr>`;
            }

            tableHTML += '</table>';
            resultDiv.innerHTML = tableHTML;
        }
        document.addEventListener("DOMContentLoaded", loadAllOrders);

    </script>
</body>

</html>