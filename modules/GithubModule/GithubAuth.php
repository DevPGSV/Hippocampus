<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once(__DIR__ . '/../../core/hippocampus.php');
    require_once(__DIR__ . '/client/GitHubClient.php');

    $hc = new Hippocampus();
    
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Encriptamos la contraseña con un hash para no guardarla como texto plano en la base de datos:
    $encryptedpassword = password_hash($password, PASSWORD_DEFAULT);

    $hc->getDB()->setConfigValue('module.GithubModule.username', $username);
    $hc->getDB()->setConfigValue('module.GithubModule.password', $encryptedpassword);

    parse_str($username, $usernameDecoded);
    parse_str($password, $passwordDecoded);

    $cu = $hc->getUserManager()->getLoggedInUser();
    if ($cu !== false) {
      $stmt = $hc->getDB()->getDBo()->prepare("INSERT INTO hc_m_GithubModule_credentials(username, password) VALUES (:username, :encryptedpassword)");

      $stmt->bindValue(':username', $usernameDecoded, PDO::PARAM_STR);
      $stmt->bindValue(':encryptedpassword', $passwordDecoded, PDO::PARAM_STR);

      $stmt->execute();
    }

    $client->setCredentials($username, $password);

    header('Location: ../../home');

?>
