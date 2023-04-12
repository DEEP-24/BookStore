<?php
require_once 'config/db_connection.php';
require_once 'includes/db_order_pdo.php';

$pageTitle = "View Orders";
$pageDescription = "This is the view orders page";

$pdo = DBConnection::getPDO();
$orderPDO = new OrderPDO($pdo);

$orders = $orderPDO->getAllOrders();

// log orders to console
echo "<script>console.log('Orders: " . json_encode($orders) . "');</script>";
function displayOrders($orders)
{
    $tableHTML = "<table>
    <tr>
        <th>OrderID</th>
        <th>ProductName</th>
        <th>Quantity</th>
        <th>Total</th>
        <th>Status</th>
    </tr>";

    foreach ($orders as $order) {
        $tableHTML = $tableHTML . "<tr>
            <td>{$order['id']}</td>
            <td>{$order['product_name']}</td>
            <td>{$order['quantity']}</td>
            <td>\${$order['total']}</td>
            <td>{$order['status']}</td>
        </tr>";
    }

    $tableHTML .= '</table>';
    return $tableHTML;
}

ob_start();
?>
<main>
    <h3>Orders</h3>
    <div id="result">
        <?php
        if (count($orders) > 0) {
            echo displayOrders($orders);
        } else {
            echo "<h1>There are no orders to display</h1>";
        }
        ?>
    </div>
</main>
<?php
$content = ob_get_clean();
include 'templates/template.php';
?>