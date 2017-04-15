<?php
require_once(__DIR__ . '/user.php');

class UserManager
{
    private $hc;
    private $loggedinUser;

    public function __construct($hc)
    {
        $this->hc = $hc;
        // $this->checkLoggedInUser();
    }

    public function getLoggedInUser()
    {
        if (!isset($this->loggedinUser)) {
            $this->checkLoggedInUser();
        }
        return $this->loggedinUser;
    }

    public function checkLoggedInUser()
    {
        $this->loggedinUser = false;
        if (empty($_SESSION['alc'])) {
            return;
        }
        if (
          empty($_COOKIE["alc"]) ||
          empty($_SESSION['alc']) ||
          empty($_COOKIE["dvc"]) ||
          empty($_SESSION['dvc']) ||
          ($_COOKIE["alc"] !== $_SESSION['alc']) ||
          ($_COOKIE["dvc"] !== $_SESSION['dvc'])
        ) {
            $this->logOutUser();
            return;
        }

        $userSession = $this->hc->getDB()->getUserSessionByAlc($_SESSION['alc']); // userid, device, alc, dvc, ip, activeSession, firstUseSession, lastUseSession, firstUseCoordLat, firstUseCoordLong, useragent

        if (!$userSession) {
            $this->logOutUser();
            return;
        }

        if (
          $userSession['alc'] !== $_SESSION['alc'] ||
          $userSession['dvc'] !== $_SESSION['dvc'] ||
          $userSession['ip']  !== $_SERVER['REMOTE_ADDR'] ||
          $userSession['useragent'] !== trim(substr($_SERVER['HTTP_USER_AGENT'], 0, 256)) ||
          !$userSession['activeSession']
        ) {
            $this->logOutUser();
            return;
        }
        $this->loggedinUser = $this->getUserById($userSession['userid']);

        $newDvc = Utils::randStr(32);
        if ($this->hc->getDB()->updateUserSessionDvc($userSession['userid'], $userSession['alc'], $newDvc)) {
            $_SESSION['dvc'] = $newDvc;
            setcookie('dvc', $newDvc, 0, '/');
        }
    }

    public function logOutUser()
    {
        if (!empty($_SESSION['alc'])) {
            $userSession = $this->hc->getDB()->getUserSessionByAlc($_SESSION['alc']);
            if ($userSession) {
                // Set session as inactive
            }
        }
        $_SESSION['alc'] = '';
        unset($_SESSION['alc']);
        $_SESSION['dvc'] = '';
        unset($_SESSION['dvc']);
        setcookie('alc', '', time() - 3600, '/');
        setcookie('dvc', '', time() - 3600, '/');
        $this->loggedinUser = false;
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
