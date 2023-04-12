<?php
// includes/ProductPDO.php

class ProductPDO
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  // Fetch all products
  public function getAllProducts()
  {
    $stmt = $this->pdo->prepare("SELECT * FROM product");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getProductById($id)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM product WHERE id = ?");
    $stmt->bindValue(1, $id);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  // Insert a new product
  public function insertProduct($name, $description, $price, $image_url)
  {
    $stmt = $this->pdo->prepare("INSERT INTO product (name, description, price, image_url) VALUES (?, ?, ?, ?)");
    $stmt->bindValue(1, $name);
    $stmt->bindValue(2, $description);
    $stmt->bindValue(3, $price);
    $stmt->bindValue(4, $image_url);

    return $stmt->execute();
  }
}

?>