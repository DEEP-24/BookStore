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


function addProduct($name, $description, $price, $image_url)
{
    global $connection;
    $stmt = $connection->prepare("INSERT INTO product (name, description, price, image_url) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $description, $price, $image_url);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => "Product has been created"]);
    } else {
        echo json_encode(['success' => false, 'message' => "Error: " . $connection->error]);
    }
    exit();
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];
    addProduct($name, $description, $price, $image_url);

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
    <form action="add-product.php" method="post" id="product_form">
        <!-- Name,description, price, image-url  -->
        <fieldset style="display: flex; flex-direction: column; gap: 4px;">
            <div>
                <label for="name">Name</label>
                <input type="text" name="name" id="name" required />
                <p id="name-error" style=" font-size: 14px; color: red"></p>
            </div>
            <div>
                <label for="description">Description</label>
                <input type="text" name="description" id="description" />
                <p></p>
            </div>
            <div>
                <label for="price">Price</label>
                <input type="number" name="price" id="price" min="1" />
                <p id="price-error" style="font-size: 14px; color: red"></p>
            </div>
            <div>
                <label for="image_url">Image_Url</label>
                <input type="text" name="image_url" id="image_url" />
                <p></p>
            </div>
        </fieldset>
    </form>
    <button id="submit_button">Add Product</button>

    <br>
    <div id="result"></div>
    <script>
        const resultElement = document.getElementById("result");

        document.getElementById('submit_button').addEventListener('click', async function (event) {
            event.preventDefault();

            const form = document.getElementById('product_form');

            if (!validateForm(form)) {
                return;
            }

            const formData = new URLSearchParams(new FormData(form)).toString();

            const response = await fetch('add-product.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: formData
            });

            const result = await response.json();
            document.getElementById('result').innerText = result.message;

            setTimeout(() => {
                clearFormInputs(form);
            }, 0);

            setTimeout(() => {
                hideResultMessage();
            }, 800)
        });

        function clearFormInputs(form) {
            form.reset();
        }

        function hideResultMessage() {
            resultElement.style.display = "none";
        }

        function validateForm(form) {
            const nameInput = form.name;
            const priceInput = form.price;

            let errors = []

            if (!nameInput.value) {
                errors.push({
                    id: "name-error", message: "Name field is required"
                })
            }

            if (priceInput.value < 1) {
                errors.push({
                    id: "price-error", message: "price should be atleast $1"
                })
            }


            if (errors.length > 0) {
                displayErrorMessage(errors);
                return false;
            }

            return true;
        }

        function displayErrorMessage(errors) {
            for (const error of errors) {
                const errorElement = document.getElementById(error.id);
                errorElement.innerHTML = error.message;
            }
        }

    </script>
</body>

</html>