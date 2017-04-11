<?php

require_once(__DIR__ . '/user.php');

class Database {
  private $database;
  private $username;
  private $password;
  private $host;

  private $hc;
  private $db;

  public function __construct($hc, $database, $username, $password, $host = 'localhost') {
    $this->hc       = $hc;;
    $this->database = $database;
    $this->username = $username;
    $this->password = $password;
    $this->host     = $host;

    $this->dbConnect();
    $this->databaseSetup();
  }

  private function dbConnect() {
    // Check $this->host and $this->database are alphanumeric (with underscores and dashes allowed)
    try {//
      $this->db = new PDO("mysql:host=$this->host;dbname=$this->database;charset=utf8", $this->username, $this->password);
      $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE , PDO::FETCH_ASSOC);
    } catch (PDOException $e) { // Debugging!
      echo $e->getMessage()."<br>\n";
      die('ERROR');
    }
  }

  public function getUserDataById($userId, $join1auth = false) {
    if ($join1auth) {
      $sql = "SELECT * FROM users JOIN `users-1auth` ON users.id = `users-1auth.id` WHERE id=?";
    } else {
      $sql = "SELECT * FROM users WHERE id=?";
    }
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$userId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($rows) === 1) {
      return $rows[0];
    } else {
      return false;
    }
  }

  public function getUserDataByUsername($user, $join1auth = false) {
    if ($join1auth) {
      $sql = "SELECT * FROM users JOIN `users-1auth` ON users.id = `users-1auth.id` WHERE username=?";
    } else {
      $sql = "SELECT * FROM users WHERE username=?";
    }
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$user]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($rows) === 1) {
      return $rows[0];
    } else {
      return false;
    }
  }

  public function getUserDataByEmail($email, $join1auth = false) {
    if ($join1auth) {
      $sql = "SELECT * FROM users JOIN `users-1auth` ON users.id = `users-1auth.id` WHERE email=?";
    } else {
      $sql = "SELECT * FROM users WHERE email=?";
    }
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$email]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($rows) === 1) {
      return $rows[0];
    } else {
      return false;
    }
  }

  private function databaseSetup() { // Setup database when new database version is found
    try {
      $dbVersion='4';
      $stmt = $this->db->prepare("SELECT * FROM config WHERE varkey='db.version'");
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if ($rows[0]['value'] !== $dbVersion) {
        throw new Exception();
      }
    } catch (Exception $e) {
      try {
        $this->db->beginTransaction();
        $sql = file_get_contents(__DIR__ . '/../tmp/hippocampus.sql');
        $this->db->exec($sql);
        $this->db->commit();
      } catch (PDOException $e) {
        $this->db->rollback();
        die($e->getMessage());
      }
      die('Database updated. Please refresh.');
    }
  }

  public function getUserById($userid) {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE id=:id");
    $stmt->bindValue(':id', $userid, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($rows) === 1) {
      $uData = $rows[0];
      $u = new User($this->hc, $uData['id'], $uData['username'], $uData['email'], $uData['confirmedEmail'], $uData['secretToken'], $uData['role']);
      return $u;
    }
    throw new Exception('No user with that id');
    return false;
  }

  public function getUser1AuthData($user) {
    if ($user instanceof User) $userid = $user->getId();
    else if (is_int($user))    $userid = $user;
    else throw new Exception('Invalid id');

    $stmt = $this->db->prepare("SELECT * FROM users-1auth WHERE id=:id");
    $stmt->bindValue(':id', $userid, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($rows) === 1) {
      $data = $rows[0];
      return $data; // id, pw, salt, csalt
    }
    throw new Exception('No user with that id');
    return false;
  }

  /// TODO
  public function registerNewUser(&$user, $pw, $salt, $csalt) {
    if ($this->getUserDataByUsername($user->getUsername())) {
      throw new Exception('Username already registered');
      return false;
    } else if ($this->getUserDataByEmail($user->getEmail())) {
      throw new Exception('Email  already registered');
      return false;
    }

    try {
      $this->db->beginTransaction();

      $stmt = $this->db->prepare("INSERT INTO users (username, email, confirmedEmail, secretToken, role) VALUES(:username, :email, :confirmedEmail, :secretToken, :role)");
      $stmt->bindValue(':username', $user->getUsername(), PDO::PARAM_STR);
      $stmt->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
      $stmt->bindValue(':confirmedEmail', (bool)$user->isConfirmedEmail(), PDO::PARAM_INT);
      $stmt->bindValue(':secretToken', $user->getSecretToken(), PDO::PARAM_STR);
      $stmt->bindValue(':role', $user->getRole(), PDO::PARAM_INT);
      $stmt->execute();
      $uid = $this->db->lastInsertId();
      $user->setId($uid);

      $stmt = $this->db->prepare("INSERT INTO `users-1auth` (id, pw, salt, csalt) VALUES(:id, :pw, :salt, :csalt)");
      $stmt->bindValue(':id', $user->getId(), PDO::PARAM_INT);
      $stmt->bindValue(':pw', $pw, PDO::PARAM_STR);
      $stmt->bindValue(':salt', $salt, PDO::PARAM_STR);
      $stmt->bindValue(':csalt', $csalt, PDO::PARAM_STR);
      $stmt->execute();

      $this->db->commit();
      return true;
    } catch (PDOException $e) {
      $this->db->rollback();
      error_log($e->getMessage());
      return false;
    }
  }

  /// TODO
  public function createNewUserSession($user, $device, $alc, $dvc, $activeSession, $firstUserSession, $lastUseSession, $firstUseCoordLat, $firstUseCoordLong, $useragent) {
    if ($user instanceof User) $userid = $user->getId();
    else if (is_int($user))    $userid = $user;
    else throw new Exception('Invalid id');
    return false;
  }

  public function getUserSessionByAlc($alc) {
    $stmt = $this->db->prepare("SELECT * FROM user-sessions WHERE alc=:alc");
    $stmt->bindValue(':alc', $alc, PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($rows) === 1) {
      $data = $rows[0];
      return $data; // userid, device, alc, dvc, ip, activeSession, firstUseSession, lastUseSession, firstUseCoordLat, firstUseCoordLong, useragent
    }
    throw new Exception('No user session with that alc');
    return false;
  }

  /// TODO
  public function updateUserSessionDvc($user, $alc, $newDvc) {
    if ($user instanceof User) $userid = $user->getId();
    else if (is_int($user))    $userid = $user;
    else throw new Exception('Invalid id');
    return false;
  }


}
