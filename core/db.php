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
}
