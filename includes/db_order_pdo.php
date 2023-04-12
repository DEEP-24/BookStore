<?php
class OrderPDO
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  // Fetch all products
  public function getAllOrders()
  {
    $stmt = $this->pdo->prepare("SELECT `order`.*, `product`.`name` AS `product_name` FROM `order` JOIN `product` ON `order`.`product_id` = `product`.`id`");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function insertOrder($product_id, $quantity, $total)
  {
    $stmt = $this->pdo->prepare("INSERT INTO `order` (product_id, quantity, total) VALUES (:product_id, :quantity, :total)");
    $stmt->execute([
      ':product_id' => $product_id,
      ':quantity' => $quantity,
      ':total' => $total
    ]);

    return $stmt->rowCount() > 0;
  }
}

?>