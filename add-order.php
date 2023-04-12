<?php
require_once 'config/db_connection.php';
require_once 'includes/db_order_pdo.php';
require_once 'includes/db_product_pdo.php';

ob_start();

$pageTitle = "Add Order";
$pageDescription = "This is the add order page";

$pdo = DBConnection::getPDO();
$orderPDO = new OrderPDO($pdo);
$productPDO = new ProductPDO($pdo);

$products = $productPDO->getAllProducts();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    $product = $productPDO->getProductById($productId);
    $quantity = $_POST['quantity'];
    $total = $product['price'] * $quantity;

    if ($product) {
        $response = $orderPDO->insertOrder($productId, $quantity, $total);

        if ($response) {
            $success_message = "Order has been created";
            // redirect to view-orders.php on success
            header("Location: view-orders.php");
        } else {
            $error_message = "Error: Could not create the order";
        }
    } else {
        echo "Invalid product ID!";
    }
}
?>

<main>
    <form id="orderForm" action="add-order.php" method="POST">
        <label for="productSelect" id="select" style="font-size: 20px; font-weight: bold; ">Select a product</label>
        <div id="productSelectDiv" style="font-size: 22px">
            <select id="product_id" name="product_id">
                <?php foreach ($products as $product): ?>
                    <option value="<?= $product['id'] ?>"><?= $product['name'] . ' ($' . $product['price'] . ')' ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <br>

        <div id="quantitySelection">
            <label for="quantity" style="font-size: 20px; font-weight: bold; ">Select Quantity</label>
            <br>
            <input type="number" name="quantity" id="quantity" style="margin-top: 3px;" required />
        </div>

        <button type="submit" value="Place Order" id="place_order"
            style="font-size: 15px; margin-top: 15px; border: 1px solid black; border-radius: 5px;">
            Place Order
        </button>
    </form>

    <?php if (isset($success_message)): ?>
        <div id="result" style="color: green; font-size: 14px;">
            <?= $success_message ?>
        </div>

        <script>
            setTimeout(() => {
                document.getElementById('order_form').reset();
                document.getElementById('result').style.display = 'none';
            }, 3000);
        </script>
    <?php elseif (isset($error_message)): ?>
        <div id="result" style="color: red; font-size: 14px;">
            <?= $error_message ?>
        </div>
    <?php endif; ?>
</main>

<?php
$content = ob_get_clean();
include 'templates/template.php';
?>