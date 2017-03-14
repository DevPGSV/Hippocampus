<?php
require_once(__DIR__ . '/user.php');

class UserManager {

    private $hc;

    public function __construct($hc) {
      $this->hc = $hc;
    }

    public function getLoggedInUser() {
      if (isset($_GET['loggedin'])) {
        $uData = $this->hc->getDB()->getUserDataById(3);
        if ($uData) {
          $u = new User($this->hc, $uData['id'], $uData['email'], $uData['confirmedEmail'], $uData['secretToken'], $uData['role']);
          return $u;
        } else {
          return false;
        }
      } else {
        return false;
      }
    }
}
