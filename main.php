<?php
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
    // Your seed data code here
    $query2 = "DROP TABLE IF EXISTS product";
    $query1 = "DROP TABLE IF EXISTS `order`";

    if (executeQuery($query1) == TRUE && executeQuery($query2) == TRUE) {

    } else {

    }


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
        echo "Table product and order has been created";
        echo "<br />";


    } else {
        echo "Error: " . $connection->error;
        echo "<br />";

    }





}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    switch ($action) {
        case 'seed_data':
            seedData($connection);
            break;
        // Add more cases for different actions
    }

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
    <button onClick="setActionAndSubmitForm('seed_data')">Seed Data</button>
    <br>
    <div id="result"></div>
    <form id="hiddenForm" method="POST" style="display: none;">
        <input type="hidden" name="action" id="action" value="">
    </form>
    <script>
        function setActionAndSubmitForm(action) {
            document.getElementById('action').value = action;
            document.getElementById('hiddenForm').submit();
        }



    </script>
</body>

</html>