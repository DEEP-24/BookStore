<?php
require_once 'config/db_connection.php';
require_once 'includes/db_product_pdo.php';

ob_start();

$pageTitle = "Add Product";
$pageDescription = "This is the add product page";

$pdo = DBConnection::getPDO();
$productPDO = new ProductPDO($pdo);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $image_url = $_POST['image_url'] ?? '';

    if (empty($name)) {
        $errors['name'] = "Name field is required";
    }

    if (empty($price) || $price < 1) {
        $errors['price'] = "Price should be at least $1";
    }

    if (!empty($image_url) && !filter_var($image_url, FILTER_VALIDATE_URL)) {
        $errors['image_url'] = "Image URL is not valid";
    }

    if (empty($errors)) {
        $response = $productPDO->insertProduct($name, $description, $price, $image_url);

        if ($response) {
            $success_message = "Product has been created";
            // redirect to view-products.php on success
            header("Location: view-products.php");
        } else {
            $error_message = "Error: Could not create the product";
        }
    }
}
?>

<main>
    <form action="add-product.php" method="post" id="product_form">
        <!-- Name,description, price, image-url  -->
        <fieldset style="display: flex; flex-direction: column; gap: 4px; margin-top: 5px;">
            <div>
                <label for="name">Name</label>
                <input type="text" name="name" id="name" required value="<?= htmlspecialchars($name ?? '') ?>" />
                <p id="name-error" style=" font-size: 14px; color: red">
                    <?= $errors['name'] ?? '' ?>
                </p>
                </p>
            </div>
            <div style="display: flex; flex-direction: row; gap: 4px; ">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows='5'>
                    <?= htmlspecialchars($description ?? '') ?>
                </textarea>
            </div>
            <div>
                <label for="price">Price</label>
                <input type="number" name="price" id="price" min="1" value='<?= htmlspecialchars($price ?? '') ?>' />
                <p id="price-error" style="font-size: 14px; color: red">
                    <?= $errors['price'] ?? '' ?>
                </p>
            </div>
            <div>
                <label for="image_url">Image_Url</label>
                <input type="text" name="image_url" id="image_url" required
                    value="<?= htmlspecialchars($image_url ?? '') ?>" />
                <p id="image_url-error" style="font-size: 14px; color: red">
                    <?= $errors['image_url'] ?? '' ?>
                </p>
            </div>
        </fieldset>

        <button id="submit_button" style=" margin-top: 3px; border: 1px solid black; border-radius: 5px;">Add
            Product
        </button>
    </form>


    <br>
    <?php if (isset($success_message)): ?>
        <div id="result" style="color: green; font-size: 14px;">
            <?= $success_message ?>
        </div>

        <script>
            setTimeout(() => {
                document.getElementById('product_form').reset();
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