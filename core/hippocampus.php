<?php
require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/db.php');
require_once(__DIR__ . '/user.php');
require_once(__DIR__ . '/userManager.php');
require_once(__DIR__ . '/themeManager.php');
require_once(__DIR__ . '/theme.php');
require_once(__DIR__ . '/utils.php');

class Hippocampus {

  private $db;
  private $themeManager;
  private $userManager;

  public function __construct() {
    global $_HARDCODED;
    session_start();
    $this->db = new Database($this, $_HARDCODED['db']['database'], $_HARDCODED['db']['username'], $_HARDCODED['db']['password'], $_HARDCODED['db']['host']);
    $this->themeManager = new ThemeManager($this);
    $this->userManager  = new UserManager($this);
  }

  public function run() {
    global $hc;
    $this->themeManager->loadAllThemes();

    $u = $this->userManager->getLoggedInUser();
    if ($u) {
      require(__DIR__ . '/../themes/'.$this->themeManager->getFeaturePath('userview'));
    } else {
      if (!empty($_GET['p']) && ($_GET['p'] === '/register' || $_GET['p'] === 'register'))  {
        require(__DIR__ . '/../themes/'.$this->themeManager->getFeaturePath('register'));
      } else if (!empty($_GET['p']) && ($_GET['p'] === '/admin' || $_GET['p'] === 'admin')){
          require(__DIR__ . '/../themes/'.$this->themeManager->getFeaturePath('admin'));
      }
      else {
        require(__DIR__ . '/../themes/'.$this->themeManager->getFeaturePath('index'));
      }
    }
  }

  public function getDB() {
    return $this->db;
  }

  public function getUserManager() {
    return $this->userManager;
  }
}
