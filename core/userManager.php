<?php
require_once(__DIR__ . '/user.php');

class UserManager
{
    private $hc;
    private $loggedinUser;

    public function __construct($hc)
    {
        $this->hc = $hc;
        $this->checkLoggedInUser();
    }

    public function getLoggedInUser()
    {
        return $this->loggedinUser;
    }

    /// TODO
    public function checkLoggedInUser()
    {
        if (isset($_GET['loggedin'])) {
            $this->loggedinUser = $this->getUserById(3);
        } else {
            $this->loggedinUser = false;
        }
    }

    public function getUserById($userid)
    {
        return $this->hc->getDB()->getUserById($userid);
    }

    public function registerNewUser(&$user)
    {
        $this->hc->getDB()->registerNewUser($user);
    }
}
