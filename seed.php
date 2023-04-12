<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config/db_connection.php';

ob_start();

$pdo = DBConnection::getPDO();
$pageTitle = "Seed the database";
$pageDescription = "This is the seed page";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt1 = $pdo->prepare("DROP TABLE IF EXISTS `order`");
        $stmt2 = $pdo->prepare("DROP TABLE IF EXISTS product");

        $stmt1->execute();
        $stmt2->execute();

        $query3 = "CREATE TABLE product (" .
            " id INT AUTO_INCREMENT PRIMARY KEY," .
            " name VARCHAR(255) NOT NULL," .
            " description TEXT," .
            " price DECIMAL(10, 2) NOT NULL," .
            " image_url VARCHAR(255)," .
            " created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, " .
            " updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP" . ");";


        $query4 = "CREATE TABLE `order` (" .
            "id INT AUTO_INCREMENT PRIMARY KEY," .
            "product_id INT NOT NULL," .
            "quantity INT NOT NULL," .
            "total DECIMAL(10, 2) NOT NULL," .
            "status ENUM('shipped', 'completed') NOT NULL DEFAULT 'completed'," .
            "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP," .
            "updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP," .
            "FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE" . ");";

        $stmt3 = $pdo->prepare($query3);
        $stmt4 = $pdo->prepare($query4);

        $stmt3->execute();
        $stmt4->execute();

        $success_message = "Database has been reset";
        header("Location: index.php");
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }


}


?>
<main>
    <br>
    <form action="seed.php" method="POST">
        <button style="cursor: pointer; padding: 4px; margin: 4px;">Seed data</button>
    </form>

    <!-- Display success or error message -->
    <?php if (isset($success_message)): ?>
        <div style="color: green; font-size: 14px;">
            <?= $success_message ?>
        </div>
    <?php elseif (isset($error_message)): ?>
        <div style="color: red; font-size: 14px;">
            <?= $error_message ?>
        </div>
    <?php endif; ?>
</main>

<?php
$content = ob_get_clean();
include 'templates/template.php';
?>