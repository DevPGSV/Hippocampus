<?php
require_once(__DIR__ . '/user.php');

class UserManager {

    private $hc;

    public function __construct($hc) {
      $this->hc = $hc;
    }
}
