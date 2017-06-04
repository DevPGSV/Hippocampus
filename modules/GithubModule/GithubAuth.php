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

    $cu = $hc->getUserManager()->getLoggedInUser();
    if ($cu !== false) {
      $stmt = $hc->getDB()->getDBo()->prepare("INSERT INTO hc_m_GithubModule_credentials(username, password) VALUES ('$username', '$encryptedpassword')");
      $stmt->execute();
    }

    // Consulta para añadir las credenciales de Github del usuario a la base de datos.
    // $sql_add_user = "INSERT INTO hc_m_GithubModule_credentials(username, password) VALUES ('$username', '$encryptedpassword')";
    // $query_add_user = mysqli_query($db, $sql_add_user);

    $client = new GitHubClient();
    $client->setCredentials($username, $password));

    $_SESSION['client'] = $client;

    header('Location: ../../home');

?>
