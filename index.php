<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page 1</title>
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

    <!-- Page content -->
    <div>
        <h1>Welcome</h1>
        <div>
            <h2>Team members</h2>
            <p>Member 1</p>
            <ul>
                <li>Name: </li>
                <li>Task:</li>
            </ul>
            <p>Member 2</p>
            <ul>
                <li>Name: </li>
                <li>Task:</li>
            </ul>
            <p>Member 3</p>
            <ul>
                <li>Name: </li>
                <li>Task:</li>
            </ul>
            <p>Member 4</p>
            <ul>
                <li>Name: </li>
                <li>Task:</li>
            </ul>

        </div>
    </div>
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

    </script>
</body>

</html>