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

        $page = '';
        if (!empty($_GET['p'])) {
          $page = $_GET['p'];
        }
        if (empty($page) || strlen($page) === 0) $page = '/';
        if ($page[0] !== '/') $page = '/'.$page;

        $aliases = [
          '/' => '/home',
          '/index.php' => '/home'
        ];
        if (!empty($aliases[$page])) $page = $aliases[$page];


        $u = $this->userManager->getLoggedInUser();

        switch($page) {
          case '/register':
            require(__DIR__ . '/../themes/'.$this->themeManager->getFeaturePath('register'));
            break;
          case '/admin':
            require(__DIR__ . '/../themes/'.$this->themeManager->getFeaturePath('admin'));
          case '/style.css':
            header("Content-type: text/css");
            require(__DIR__ . '/../themes/'.$this->themeManager->getFeaturePath('style'));
            break;
          case '/scripts.js':
            header('Content-Type: application/javascript');
            require(__DIR__ . '/../themes/'.$this->themeManager->getFeaturePath('javascript'));
            break;
          case '/home':
            if ($u) {
                require(__DIR__ . '/../themes/'.$this->themeManager->getFeaturePath('userview'));
            } else {
                require(__DIR__ . '/../themes/'.$this->themeManager->getFeaturePath('index'));
            }
            break;
          case '/logout':
            $this->userManager->logOutUser();
            header('Location: home');
            echo 'Logged out! <a href="home">Home</a>';
            break;
          case '/window':
            if ($u) {
                require(__DIR__ . '/../themes/'.$this->themeManager->getFeaturePath('window'));
            } else {
                echo '404! Window.';
            }
            break;
          default:
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found\n";
            print_r($_GET);
            break;
        }
    }

    public function getMetacode() {
      $metacodeArr = $this->themeManager->getMetacode();
      $this->moduleManager->onCreatingMetacode($metacodeArr);
      return implode("\n  ", $metacodeArr);
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
