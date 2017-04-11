<?php

class User {

    private $hc;

    private $id;
    private $user;
    private $email;
    private $confirmedEmail;
    private $secretToken;
    private $role;

    public function __construct($hc, $id, $username, $email, $confirmedEmail, $secretToken, $role) {
      $this->hc             = $hc;
      $this->id             = $id;
      $this->username       = $username;
      $this->email          = $email;
      $this->confirmedEmail = $confirmedEmail;
      $this->secretToken    = $secretToken;
      $this->role           = $role;
    }

    public function getId() {
      return $this->id;
    }

    public function getUsername() {
      return $this->username;
    }

    public function getEmail() {
      return $this->email;
    }

    public function isConfirmedEmail() {
      return $this->confirmedEmail;
    }

    public function getSecretToken() {
      return $this->secretToken;
    }

    public function getRole() {
      return $this->role;
    }

}
