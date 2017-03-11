<?php
require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/db.php');
require_once(__DIR__ . '/user.php');
require_once(__DIR__ . '/userManager.php');
require_once(__DIR__ . '/themeManager.php');
require_once(__DIR__ . '/theme.php');

class Hippocampus {

  private $db;
  private $themeManager;

  public function __construct() {
    global $_HARDCODED;
    $this->db = new Database($_HARDCODED['db']['database'], $_HARDCODED['db']['username'], $_HARDCODED['db']['password'], $_HARDCODED['db']['host']);
    $this->themeManager = new ThemeManager($this);
  }

  public function run() {
    $d = $this->themeManager->loadTheme('default');
    session_start();
    if (isset($_GET['loggedin'])) {
      require(__DIR__ . '/../themes/'.$d->getUserviewPath());
    } else {
      require(__DIR__ . '/../themes/'.$d->getIndexPath());
    }

  }
}
