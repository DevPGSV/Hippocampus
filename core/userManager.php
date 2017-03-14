<?php
require_once(__DIR__ . '/user.php');

class UserManager {

    private $hc;
    private $loggedinUser;

    public function __construct($hc) {
      $this->hc = $hc;
      $this->checkLoggedInUser();
    }

    public function getLoggedInUser() {
      return $this->loggedinUser;
    }

    /// TODO
    public function checkLoggedInUser() {
      if (isset($_GET['loggedin'])) {
        $this->loggedinUser = $this->hc->getDB()->getUserById(3);
      } else {
        $this->loggedinUser = false;
      }
    }
}
