<?php
require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/db.php');
require_once(__DIR__ . '/user.php');
require_once(__DIR__ . '/userManager.php');
require_once(__DIR__ . '/themeManager.php');
require_once(__DIR__ . '/moduleManager.php');
require_once(__DIR__ . '/theme.php');
require_once(__DIR__ . '/utils.php');

class Hippocampus
{
    private $db;
    private $themeManager;
    private $userManager;
    private $moduleManager;

    public function __construct()
    {
        global $_HARDCODED;
        session_start();
        $this->db = new Database($this, $_HARDCODED['db']['database'], $_HARDCODED['db']['username'], $_HARDCODED['db']['password'], $_HARDCODED['db']['host']);
        $this->themeManager   = new ThemeManager($this);
        $this->userManager    = new UserManager($this);
        $this->moduleManager  = new ModuleManager($this);
    }

    public function run()
    {
        global $hc;
        $this->themeManager->loadAllThemes();


        if (!empty($_GET['p']) && ($_GET['p'] === '/register' || $_GET['p'] === 'register')) {
            require(__DIR__ . '/../themes/'.$this->themeManager->getFeaturePath('register'));
        } elseif (!empty($_GET['p']) && ($_GET['p'] === '/admin' || $_GET['p'] === 'admin')) {
            require(__DIR__ . '/../themes/'.$this->themeManager->getFeaturePath('admin'));
        } elseif (!empty($_GET['p']) && ($_GET['p'] === '/style.css' || $_GET['p'] === 'style.css')) {
            header("Content-type: text/css");
            require(__DIR__ . '/../themes/'.$this->themeManager->getFeaturePath('style'));
        } elseif (!empty($_GET['p']) && ($_GET['p'] === '/scripts.js' || $_GET['p'] === 'scripts.js')) {
            header('Content-Type: application/javascript');
            require(__DIR__ . '/../themes/'.$this->themeManager->getFeaturePath('javascript'));
        } elseif (empty($_GET['p']) || ($_GET['p'] === '/' || $_GET['p'] === '/index.php' || $_GET['p'] === '/home' || $_GET['p'] === 'home')) {
            $u = $this->userManager->getLoggedInUser();
            if ($u) {
                require(__DIR__ . '/../themes/'.$this->themeManager->getFeaturePath('userview'));
            } else {
                require(__DIR__ . '/../themes/'.$this->themeManager->getFeaturePath('index'));
            }
        } elseif (empty($_GET['p']) || ($_GET['p'] === '/logout' || $_GET['p'] === 'logout')) {
            $this->userManager->logOutUser();
            header('Location: home');
            echo 'Logged out! <a href="home">Home</a>';
        } elseif (empty($_GET['p']) || ($_GET['p'] === '/window' || $_GET['p'] === 'window')) {
            $u = $this->userManager->getLoggedInUser();
            if ($u) {
                require(__DIR__ . '/../themes/'.$this->themeManager->getFeaturePath('window'));
            } else {
                echo '404! Window.';
            }
        } else {
            echo "404<br>\n";
            print_r($_GET);
        }
    }

    public function getDB()
    {
        return $this->db;
    }

    public function getUserManager()
    {
        return $this->userManager;
    }

    public function getModuleManager()
    {
        return $this->moduleManager;
    }

    public function getSidebarTabs() {
      $sidebarTabs = [
        3 => [
          'icon' => 'gmail',
          'text' => 'Gmail',
          'id' => 'gmail',
        ],
        4 => [
          'icon' => 'drive',
          'text' => 'Drive',
          'id' => 'drive',
        ],
        5 => [
          'icon' => 'calendar',
          'text' => 'Calendar',
          'id' => 'calendar',
        ],
        6 => [
          'icon' => 'classroom',
          'text' => 'Classroom',
          'id' => 'classroom',
        ],
        7 => [
          'icon' => 'github',
          'text' => 'Github',
          'id' => 'github',
        ],
        9 => [
          'icon' => 'facebook',
          'text' => 'Facebook',
          'id' => 'facebook',
        ],
        11 => [
          'icon' => 'chat',
          'text' => 'Mensajes',
          'id' => 'chat',
        ],
        14 => [
          'icon' => 'software',
          'text' => 'Software',
          'id' => 'software',
        ],
        99 => [
          'icon' => 'settings',
          'text' => 'Ajustes',
          'id' => 'settings',
        ],
        100 => [
          'icon' => 'about',
          'text' => 'Ayuda',
          'id' => 'about',
        ],
      ];
      $this->moduleManager->onCreatingSidebar($sidebarTabs);
      //ksort($sidebarTabs, SORT_NUMERIC);
      return $sidebarTabs;
    }
}
