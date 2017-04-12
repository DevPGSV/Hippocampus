<?php
require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/db.php');
require_once(__DIR__ . '/user.php');
require_once(__DIR__ . '/userManager.php');
require_once(__DIR__ . '/themeManager.php');
require_once(__DIR__ . '/theme.php');
require_once(__DIR__ . '/utils.php');

class Hippocampus
{
    private $db;
    private $themeManager;
    private $userManager;

    public function __construct()
    {
        global $_HARDCODED;
        session_start();
        $this->db = new Database($this, $_HARDCODED['db']['database'], $_HARDCODED['db']['username'], $_HARDCODED['db']['password'], $_HARDCODED['db']['host']);
        $this->themeManager = new ThemeManager($this);
        $this->userManager  = new UserManager($this);
    }

    public function run()
    {
        global $hc;
        $this->themeManager->loadAllThemes();

        $u = $this->userManager->getLoggedInUser();
        if ($u) {
            require(__DIR__ . '/../themes/'.$this->themeManager->getFeaturePath('userview'));
        } else {
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
            } elseif (empty($_GET['p']) || ($_GET['p'] === '/' || $_GET['p'] === '/index.php')) {
                require(__DIR__ . '/../themes/'.$this->themeManager->getFeaturePath('index'));
            } else {
                echo '404';
                print_r($_GET);
            }
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
}
