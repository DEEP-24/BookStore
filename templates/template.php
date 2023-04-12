<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php echo $pageTitle; ?>
  </title>
  <meta name="description" content="<?php echo $pageDescription; ?>">
  <link rel="stylesheet" href="assets/css/styles.css">
  <script src="assets/js/navigation.js" defer></script>
</head>

<body>
  <!-- Navigation bar -->
  <nav class="navbar">
    <a href="index.php">Home</a>
    <a href="add-product.php">Add Product</a>
    <a href="view-products.php">View Products</a>
    <a href="add-order.php">Add Order</a>
    <a href="view-orders.php">View Orders</a>
    <a href="seed.php">Seed Data</a>
  </nav>

  <!-- Page content -->
  <?php echo $content; ?>

</body>

</html>