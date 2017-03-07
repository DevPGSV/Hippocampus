<?php
require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/db.php');
require_once(__DIR__ . '/user.php');
require_once(__DIR__ . '/userManager.php');
require_once(__DIR__ . '/themeManager.php');


class Hippocampus {

  private $db;

  public function __construct() {
    global $_HARDCODED;
    $this->db = new Database($_HARDCODED['db']['database'], $_HARDCODED['db']['username'], $_HARDCODED['db']['password'], $_HARDCODED['db']['host']);
  }

  public function run() {
    session_start();
    require(__DIR__ . '/../themes/default/index.php');
  }
}
