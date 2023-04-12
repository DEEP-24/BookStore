<?php
class DBConnection
{
  private $dsn = "mysql:host=localhost;dbname=database";
  private $username = "demo";
  private $password = "password";
  private static $instance = null;
  private $connection;

  private function __construct()
  {
    try {
      $this->connection = new PDO($this->dsn, $this->username, $this->password);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
  }

  public static function getInstance()
  {
    if (self::$instance == null) {
      self::$instance = new DBConnection();
    }
    return self::$instance;
  }

  public static function getPDO()
  {
    return self::getInstance()->getConnection();
  }

  public function getConnection()
  {
    return $this->connection;
  }
}
?>