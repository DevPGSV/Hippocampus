<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (file_exists(__DIR__ . '/core/config.php')) {
  header('Location: home');
}

function randStr($length)
{
    if (function_exists('random_bytes')) {
        return substr(bin2hex(random_bytes($length)), 0, $length);
    } elseif (function_exists('openssl_random_pseudo_bytes')) {
        return substr(bin2hex(openssl_random_pseudo_bytes($length)), 0, $length);
    } elseif (function_exists('mcrypt_create_iv')) {
        return substr(bin2hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM)), 0, $length);
    } else {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return substr($randomString, 0, $length);
    }
}

$installationstep = 1;
if (empty($_SESSION['stepscompleted'])) {
  $_SESSION['stepscompleted'] = 2;
  $_SESSION['dbname'] = '';
  $_SESSION['dbuser'] = '';
  $_SESSION['dbpw'] = '';
  $_SESSION['dbhost'] = 'localhost';
  $_SESSION['user_username'] = '';
  $_SESSION['user_email'] = '';
  $_SESSION['user_password'] = '';
}
$correctInstall = false;

if (empty($_GET['step'])) {
  $_GET['step'] = 1;
}
if (empty($_GET['d'])) {
  $_GET['d'] = 0;
}

if ($_GET['step'] > $_SESSION['stepscompleted']+1) {
  $_GET['step'] = $_SESSION['stepscompleted'];
}


if ($_GET['step'] == 1) {
  $installationstep = 1;
  if ($_SESSION['stepscompleted'] < 2) $_SESSION['stepscompleted'] = 2;
} elseif ($_GET['step'] == 2) {
  $installationstep = 2;
  if ($_SESSION['stepscompleted'] < 2) $_SESSION['stepscompleted'] = 2;
} elseif ($_GET['step'] == 3) {
  $installationstep = 3;
  if (empty($_POST['urlpath'])) {
    $uri = rtrim(dirname($_SERVER['REQUEST_URI']), '/').'/';
  } else {
    $uri = $_POST['urlpath'];
  }
  $_SESSION['uri'] = $uri;
  if ($_SESSION['stepscompleted'] < 3) $_SESSION['stepscompleted'] = 3;
} elseif ($_GET['step'] == 4) {
  if (empty($_POST['dbname']) || empty($_POST['dbuser']) || empty($_POST['dbpw']) || empty($_POST['dbhost'])) {
    header('Location: ?step=3&d=1');
    die('Completa todos los campos!');
  }
  $connectionOk = false;
  try {
    $db = new PDO("mysql:host={$_POST['dbhost']};dbname={$_POST['dbname']};charset=utf8", $_POST['dbuser'], $_POST['dbpw']);
    $connectionOk = true;
  } catch (PDOException $e) {
    $connectionOk = false;
  }
  if (!$connectionOk) {
    header('Location: ?step=3&d=2');
    die('Datos inválidos!');
  }
  $_SESSION['dbname'] = $_POST['dbname'];
  $_SESSION['dbuser'] = $_POST['dbuser'];
  $_SESSION['dbpw'] = $_POST['dbpw'];
  $_SESSION['dbhost'] = $_POST['dbhost'];
  $installationstep = 5;
  if ($_SESSION['stepscompleted'] < 5) $_SESSION['stepscompleted'] = 5;
} elseif ($_GET['step'] == 5) {
  $installationstep = 5;
  if ($_SESSION['stepscompleted'] < 5) $_SESSION['stepscompleted'] = 5;
} elseif ($_GET['step'] == 6) {
  if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['password2'])) {
    header('Location: ?step=5&d=1');
    die('Completa todos los campos!');
  }
  if ($_POST['password'] !== $_POST['password2']) {
    header('Location: ?step=5&d=2');
    die('Las contraseñas no coinciden!');
  }

  $_SESSION['user_username'] = $_POST['username'];
  $_SESSION['user_email'] = $_POST['email'];
  $_SESSION['user_password'] = $_POST['password'];
  $installationstep = 7;
  if ($_SESSION['stepscompleted'] < 7) $_SESSION['stepscompleted'] = 7;
} elseif ($_GET['step'] == 7) {
  $installationstep = 8;
  try {
    $db = new PDO("mysql:host={$_SESSION['dbhost']};dbname={$_SESSION['dbname']};charset=utf8", $_SESSION['dbuser'], $_SESSION['dbpw']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    $db = false;
  }
  $correctInstall = false;
  if ($db !== false) {
    $db->beginTransaction();
    try {

      $sql = file_get_contents(__DIR__ . '/tmp/hippocampus.sql');
      $db->exec($sql);

      $stmt = $db->prepare("INSERT INTO users (username, email, confirmedEmail, secretToken, role) VALUES(:username, :email, :confirmedEmail, :secretToken, :role)");
      $stmt->bindValue(':username', $_SESSION['user_username'], PDO::PARAM_STR);
      $stmt->bindValue(':email', $_SESSION['user_email'], PDO::PARAM_STR);
      $stmt->bindValue(':confirmedEmail', false, PDO::PARAM_INT);
      $stmt->bindValue(':secretToken', '-', PDO::PARAM_STR);
      $stmt->bindValue(':role', 1, PDO::PARAM_INT);
      $stmt->execute();
      $_SESSION['user_id'] = $db->lastInsertId();

      $salt = randStr(32);
      $csalt = randStr(32);
      $pw = hash('sha256', $csalt.$_SESSION['user_password']);
      $pw = hash('sha256', $salt.$pw);

      $stmt = $db->prepare("INSERT INTO `users-1auth` (id, pw, salt, csalt) VALUES(:id, :pw, :salt, :csalt)");
      $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
      $stmt->bindValue(':pw', $pw, PDO::PARAM_STR);
      $stmt->bindValue(':salt', $salt, PDO::PARAM_STR);
      $stmt->bindValue(':csalt', $csalt, PDO::PARAM_STR);
      $stmt->execute();

      $_SESSION['siteSecretToken'] = randStr(32);

$htaccessCode = "
#Options +FollowSymLinks
RewriteEngine On
RewriteBase {$_SESSION['uri']}
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?p=$1 [L]
";
$siteConfigCode = "
<?php
\$_CONFIG = [
  'db' => [
    'username' => '{$_SESSION['dbuser']}',
    'password' => '{$_SESSION['dbpw']}',
    'database' => '{$_SESSION['dbname']}',
    'host' => '{$_SESSION['dbhost']}',
  ],
  'siteSecretToken' => '{$_SESSION['siteSecretToken']}'
];
";

      $s1 = file_put_contents(__DIR__ . '/.htaccess', $htaccessCode);
      $s2 = file_put_contents(__DIR__ . '/core/config.php', $siteConfigCode);

      if ($s1 == false || $s2 == false) {
        $correctInstall = false;
        $installationstep = 8;
        $db->rollback();
      } else {
        $db->commit();
        $correctInstall = true;
        $installationstep = 8;
      }

    } catch (PDOException $e) {
      $db->rollback();
      $correctInstall = false;
      $installationstep = 8;
    } catch (Exception $e) {
      $db->rollback();
      $correctInstall = false;
      $installationstep = 8;
    }
  }
}


$urlpath = rtrim(dirname($_SERVER['REQUEST_URI']), '/').'/';
if (!empty($_SESSION['uri'])) $urlpath = $_SESSION['uri'];

?><!DOCTYPE html>
<html lang="es">
<head>
  <title>Formulario de login</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <link rel="stylesheet" href="//bootswatch.com/darkly/bootstrap.min.css">
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script>
    function setupBackgroundGradient() {

      var colors = new Array(
        [17, 240, 159], [17, 240, 203], [17, 211, 240], [17, 129, 240], [17, 55, 240], [84, 240, 94], [13, 1, 175], [0, 27, 162], [23, 153, 209], [23, 209, 178], [23, 209, 135]
      );

      var step = 0;
      var colorIndices = [0, 1, 2, 3];
      var gradientSpeed = 0.010;

      function updateGradient() {
        if ($ === undefined) return;

        var c0_0 = colors[colorIndices[0]];
        var c0_1 = colors[colorIndices[1]];
        var c1_0 = colors[colorIndices[2]];
        var c1_1 = colors[colorIndices[3]];

        var istep = 1 - step;
        var r1 = Math.round(istep * c0_0[0] + step * c0_1[0]);
        var g1 = Math.round(istep * c0_0[1] + step * c0_1[1]);
        var b1 = Math.round(istep * c0_0[2] + step * c0_1[2]);
        var color1 = "rgb(" + r1 + "," + g1 + "," + b1 + ")";

        var r2 = Math.round(istep * c1_0[0] + step * c1_1[0]);
        var g2 = Math.round(istep * c1_0[1] + step * c1_1[1]);
        var b2 = Math.round(istep * c1_0[2] + step * c1_1[2]);
        var color2 = "rgb(" + r2 + "," + g2 + "," + b2 + ")";

        $('body').css({
          background: "-webkit-gradient(linear, left top, right top, from(" + color1 + "), to(" + color2 + "))"
        }).css({
          background: "-moz-linear-gradient(left, " + color1 + " 0%, " + color2 + " 100%)"
        });
        step += gradientSpeed;
        if (step >= 1) {
          step %= 1;
          colorIndices[0] = colorIndices[1];
          colorIndices[2] = colorIndices[3];
          colorIndices[1] = (colorIndices[1] + Math.floor(1 + Math.random() * (colors.length - 1))) % colors.length;
          colorIndices[3] = (colorIndices[3] + Math.floor(1 + Math.random() * (colors.length - 1))) % colors.length;
        }
      }
      setInterval(updateGradient, 40);
    }

    $(document).ready(function() {
      setupBackgroundGradient();
    });
  </script>
  <style>
    .maincontanier {
      background-color: rgba(0, 0, 0, 0.4);
      border-radius: 10px;
      padding: 10px;
    }

    .nav-tabs>li>a.available, .nav-pills>li>a.available {
      cursor: pointer;
    }

    .nav-tabs>li>a.notavailable, .nav-pills>li>a.notavailable {
      cursor: not-allowed;
    }

    input[type="submit"].custombutton {
      display: block;
      padding-top: 10px;
      padding-bottom: 10px;
      padding-left: 30px;
      padding-right: 30px;
      font-size: 20px;

      background: linear-gradient(#41FFD0, #4440FF);
      max-width: 300px;
      border: 0;
      color: white;
      opacity: 0.8;
      cursor: pointer;
      border-radius: 20px;
    }

    .input-group .input-group-addon i {
      width: 15px;
      height: 15px;
    }

  </style>
</head>
<body>
  <div class="container maincontanier" style="margin-top: 100px;">
    <div class="row">
      <div class="col-xs-2">
        <ul class="nav nav-pills nav-stacked">
          <li class="<?php echo ($installationstep == 1 ? 'active':''); ?>"><a href="?step=1" class="<?php echo ($_SESSION['stepscompleted'] >= 1 ? 'available':'notavailable'); ?>">Welcome</a></li>
          <li class="<?php echo ($installationstep == 2 ? 'active':''); ?>"><a href="?step=2" class="<?php echo ($_SESSION['stepscompleted'] >= 2 ? 'available':'notavailable'); ?>">Ruta</a></li>
          <li class="<?php echo ($installationstep == 3 ? 'active':''); ?>"><a href="?step=3" class="<?php echo ($_SESSION['stepscompleted'] >= 3 ? 'available':'notavailable'); ?>">Base de datos</a></li>
          <li class="<?php echo ($installationstep == 5 ? 'active':''); ?>"><a href="?step=5" class="<?php echo ($_SESSION['stepscompleted'] >= 5 ? 'available':'notavailable'); ?>">Usuario</a></li>
          <li class="<?php echo ($installationstep == 7 ? 'active':''); ?>"><a href="?step=7" class="<?php echo ($_SESSION['stepscompleted'] >= 7 ? 'available':'notavailable'); ?>">Fin</a></li>
        </ul>
      </div>
      <div class="col-xs-10"><?php

      if ($installationstep === 1): ?>
      <h1>¡Bienvenido!</h1>
      <p>Esto es el instalador de Hippocampus.</p>
      <p>Con esta herramienta podrás unificar una gran cantidad de herramientas.</p>
      <div>
        <p>Para que el instalador funcione correctamente, necesitará:</p>
        <ul>
          <li>Permisos de escritura para crear los archivos de configuración</li>
          <li>Acceso a una base de datos en la que poder crear las tablas necesarias</li>
        </ul>
      </div>
      <br><br>
      <form method="POST" action="?step=2">
        <input type="hidden" name="currentstep" value="1">
        <input type="submit" class="custombutton" value="Siguiente paso">
      </form>
      <?php elseif ($installationstep === 2): ?>

      <p>¿Cual es la URI donde se localiza el proyecto?</p><br><br>
      <form method="POST" action="?step=3">
        <div class="input-group">
          <span class="input-group-addon"><?php echo (isset($_SERVER['HTTPS']) ? "https" : "http").'://'.$_SERVER['HTTP_HOST']; ?></span>
          <input type="text" class="form-control" name="urlpath" value="<?php echo $urlpath; ?>">
        </div><br>
        <input type="hidden" name="currentstep" value="2">
        <input type="submit" class="custombutton" value="Siguiente paso">
      </form>

    <?php elseif ($installationstep === 3 || $installationstep === 4): ?>
        <p>Indique los datos de acceso a la base de datos:</p>
        <p>(Los datos proporcionados deben ser correctos)</p>
        <br><br>
        <?php
        if ($_GET['d'] == 1) {
          echo '<div class="alert alert-danger"><strong>Error!</strong> Completa todos los campos!</div>';
        } elseif ($_GET['d'] == 2) {
          echo '<div class="alert alert-danger"><strong>Error!</strong> Datos inválidos!</div>';
        }
        ?>

        <form method="POST" action="?step=4">
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-database"></i></span>
            <input type="text" class="form-control" name="dbname" value="<?php echo $_SESSION['dbname']; ?>" placeholder="Database name">
          </div><br>
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input type="text" class="form-control" name="dbuser" value="<?php echo $_SESSION['dbuser']; ?>" placeholder="Database user">
          </div><br>
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk"></i></span>
            <input type="password" class="form-control" name="dbpw" value="<?php echo $_SESSION['dbpw']; ?>" placeholder="Database password">
          </div><br>
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-desktop"></i></span>
            <input type="text" class="form-control" name="dbhost" value="<?php echo $_SESSION['dbhost']; ?>" placeholder="Database host">
          </div><br>
          <input type="hidden" name="currentstep" value="3">
          <input type="submit" class="custombutton" value="Siguiente paso">
        </form>
      <?php elseif ($installationstep === 5 || $installationstep === 6): ?>
        <p>Indique los datos del usuario inicial.</p>
        <br><br>
        <?php
        if ($_GET['d'] == 1) {
          echo '<div class="alert alert-danger"><strong>Error!</strong> Completa todos los campos!</div>';
        } elseif ($_GET['d'] == 2) {
          echo '<div class="alert alert-danger"><strong>Error!</strong> Las contraseñas no coinciden!</div>';
        }
        ?>

        <form method="POST" action="?step=6">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input type="text" class="form-control" name="username" value="<?php echo $_SESSION['user_username']; ?>" placeholder="Username">
          </div><br>
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
            <input type="text" class="form-control" name="email" value="<?php echo $_SESSION['user_email']; ?>" placeholder="Email">
          </div><br>
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk"></i></span>
            <input type="password" class="form-control" name="password" placeholder="Password">
          </div><br>
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk"></i></span>
            <input type="password" class="form-control" name="password2" placeholder="Repeat Password">
          </div><br>
          <input type="hidden" name="currentstep" value="5">
          <input type="submit" class="custombutton" value="Siguiente paso">
        </form>

      <?php elseif ($installationstep === 7): ?>
        <h1>Fin!</h1>
        <p>A continuación se configurará la aplicación.</p>
        <br><br>
        <form method="POST" action="?step=7">
          <input type="hidden" name="currentstep" value="6">
          <input type="submit" class="custombutton" value="Siguiente paso">
        </form>

      <?php elseif ($installationstep === 8):

        if ($correctInstall) {
          echo '<h1>Aplicación instalada!</h1>
          <a href="index.php"><input type="submit" class="custombutton" value="Ir a la aplicación"></a>';
        } else {
          echo '<h1>Error durante la instalación!</h1>
          <p>Compruebe los permisos de la carpeta del proyecto (el usuario ejecutando el servidor web debe poder escribir archivos)</p>';
        }
      endif;


      ?></div>
    </div>
  </div>
</body>
</html>
