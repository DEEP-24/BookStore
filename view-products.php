<?php
require_once 'config/db_connection.php';
require_once 'includes/db_product_pdo.php';

$pageTitle = "View Products";
$pageDescription = "This is the view products page";

$pdo = DBConnection::getPDO();
$productPDO = new ProductPDO($pdo);
$products = $productPDO->getAllProducts();

function displayProducts($products)
{
    $tableHTML = '<table>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
        </tr>';

    foreach ($products as $product) {
        $tableHTML .= "<tr>
            <td>{$product['id']}</td>
            <td>
                <img src='{$product['image_url']}' alt='{$product['name']}' width='60' height='60' style='object-fit: cover'>
            </td>
            <td>{$product['name']}</td>
            <td>{$product['description']}</td>
            <td>\${$product['price']}</td>
        </tr>";
    }

    $tableHTML .= '</table>';
    return $tableHTML;
}

ob_start();
?>
<main>
    <h3>Products</h3>
    <div id="result">
        <?php
        if (count($products) > 0) {
            echo displayProducts($products);
        } else {
            echo "<h1>There are no products to display</h1>";
        }
        ?>
    </div>
</main>
<?php
$content = ob_get_clean();
include 'templates/template.php';
?>