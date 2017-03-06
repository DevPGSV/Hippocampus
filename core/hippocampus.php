<?php
require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/db.php');

class Hippocampus {

  private $db;

  public function __construct() {
    global $_HARDCODED;
    $this->db = new Database($_HARDCODED['db']['database'], $_HARDCODED['db']['username'], $_HARDCODED['db']['password'], $_HARDCODED['db']['host']);

    
  }
}
