<?php

class Database {
  private $database;
  private $username;
  private $password;
  private $host;

  private $db;

  public function __construct($database, $username, $password, $host = 'localhost') {
    $this->database = $database;
    $this->username = $username;
    $this->password = $password;
    $this->host     = $host;

    $this->dbConnect();
    $this->databaseSetup();
  }

  private function dbConnect() {
    // Check $this->host and $this->database are alphanumeric (with underscores and dashes allowed)
    try {//
      $this->db = new PDO("mysql:host=$this->host;dbname=$this->database;charset=utf8", $this->username, $this->password);
      $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE , PDO::FETCH_ASSOC);
    } catch (PDOException $e) { // Debugging!
      echo $e->getMessage()."<br>\n";
      die('ERROR');
    }
  }

  public function getUserDataById($userId) {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$userId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($rows) === 1) {
      return $rows[0];
    } else {
      return false;
    }
  }

  private function databaseSetup() { // Setup database when new database version is found
    try {
      $dbVersion='3';
      $stmt = $this->db->prepare("SELECT * FROM config WHERE varkey='db.version'");
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if ($rows[0]['value'] !== $dbVersion) {
        throw new Exception();
      }
    } catch (Exception $e) {
      try {
        $this->db->beginTransaction();
        $sql = file_get_contents(__DIR__ . '/../tmp/hippocampus.sql');
        $this->db->exec($sql);
        $this->db->commit();
      } catch (PDOException $e) {
        $this->db->rollback();
        die($e->getMessage());
      }
      die('Database updated. Please refresh.');
    }
  }
}
