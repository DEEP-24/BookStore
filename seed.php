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


function seedData($connection)
{
    //Dropping table if they exits
    $query1 = "DROP TABLE IF EXISTS product";
    $query2 = "DROP TABLE IF EXISTS `order`";

    if (!executeQuery($query1) || !executeQuery($query2)) {
        return ['success' => false, 'message' => "Error: " . $connection->error];
    }

    //creating table after dropping them
    $query3 = "CREATE TABLE product (" .
        " id INT AUTO_INCREMENT PRIMARY KEY," .
        " name VARCHAR(255) NOT NULL," .
        " description TEXT," .
        " price DECIMAL(10, 2) NOT NULL," .
        " image_url VARCHAR(255)," .
        " created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, " .
        " updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);";


    $query4 = "CREATE TABLE `order` (" .
        "id INT AUTO_INCREMENT PRIMARY KEY," .
        "product_id INT NOT NULL," .
        "total DECIMAL(10, 2) NOT NULL," .
        "status ENUM('pending', 'processing', 'shipped', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'," .
        "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP," .
        "updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP," .
        "FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE" .
        ");";


    if (executeQuery($query3) == TRUE && executeQuery($query4) == TRUE) {
        return ['success' => true, 'message' => "Table product and order have been created"];
    } else {
        return ['success' => false, 'message' => "Error: " . $connection->error];
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $response = ['success' => false, 'message' => 'Invalid action'];

    switch ($action) {
        case 'seed_data':
            $response = seedData($connection);
            break;
        // Add more cases for different actions
    }

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
    <button onClick="setActionAndSubmitForm('seed_data')" style="cursor: pointer; padding: 4px; margin: 4px;">Seed
        Data</button>
    <br>
    <div id="result"></div>
    <form id="hiddenForm" method="POST" style="display: none;">
        <input type="hidden" name="action" id="action" value="">
    </form>
    <script>
        function setActionAndSubmitForm(action) {
            document.getElementById('action').value = action;
            submitForm();
        }

        function submitForm() {
            const form = document.getElementById('hiddenForm');
            const formData = new FormData(form);
            const request = new XMLHttpRequest();

            request.onreadystatechange = function () {
                if (request.readyState === 4 && request.status === 200) {
                    handleResponse(request.responseText);
                }
            };

            request.open("POST", "seed.php");
            request.send(formData);
        }

        function handleResponse(responseText) {
            console.log("Response :", responseText);
            const response = JSON.parse(responseText);
            const resultDiv = document.getElementById('result');

            console.log(response);
            if (response.success) {
                resultDiv.innerHTML = response.message;
                setTimeout(() => {
                    window.location.href = "index.php";
                }, 1500);
            } else {
                resultDiv.innerHTML = response.message;
            }

        }


    </script>
</body>

</html>