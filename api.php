<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/core/hippocampus.php');
require_once(__DIR__ . '/core/utils.php');

$hc = new Hippocampus();

if (empty($_GET['action'])) {
    die('no_action');
}
$answer = [];

if ($hc->getModuleManager()->apiIdentifierRegistered($_GET['action'])) {
  $answer = $hc->getModuleManager()->apiIdentifierProcess($_GET['action'], $_POST);
  echo json_encode($answer);
} else {
  switch ($_GET['action']) {
    case 'getSalt':
      if (empty($_POST['username'])) {
          if (empty($_SESSION['csalt'])) {
              $_SESSION['csalt'] = Utils::randStr(32);
          }
          $answer = [
            'status' => 'ok',
            'csalt' => $_SESSION['csalt'],
          ];
      } else {
          if ($uData = $hc->getDB()->getUserDataByUsername($_POST['username'], true)) {
              $answer = [
                'status' => 'ok',
                'csalt' => $uData['csalt'],
              ];
          } else {
              $answer = [
                'status' => 'error',
                'msg' => 'El usuario no ha sido encontrado.',
              ];
          }
      }
      echo json_encode($answer);
      break;
    case 'login':
      $_SESSION['csalt'] = '';
      unset($_SESSION['csalt']);
      $answer['status'] = 'ok';
      $answer['msg'] = [];
      if (empty($_POST['username'])) {
          $answer['status'] = 'error';
          $answer['msg'][] = 'No se ha introducido el usuario.';
      }
      if (empty($_POST['password'])) {
          $answer['status'] = 'error';
          $answer['msg'][] = 'No se ha introducido la contraseña.';
      }
      if ($answer['status'] == 'error') {
          echo json_encode($answer);
          break;
      }
      if ($uData = $hc->getDB()->getUserDataByUsername($_POST['username'], true)) {
          $salt = $uData['salt'];
          $pw = hash('sha256', $salt.$_POST['password']);
          if ($pw !== $uData['pw']) {
              $answer['status'] = 'error';
              $answer['msg'][] = 'Contraseña incorrecta.';
          } else {
              $device = 'DeviceName?';
              $device = trim(substr($device, 0, 64));
              $alc = Utils::randStr(32);
              $dvc = Utils::randStr(32);
              $ip = $_SERVER['REMOTE_ADDR'];
              $firstUseSession = $lastUseSession = time();
              $firstUseCoordLat = 0.0;
              $firstUseCoordLong = 0.0;
              $useragent = trim(substr($_SERVER['HTTP_USER_AGENT'], 0, 256));

              if ($hc->getDB()->createNewUserSession((int)$uData['id'], $device, $alc, $dvc, $ip, true, $firstUseSession, $lastUseSession, $firstUseCoordLat, $firstUseCoordLong, $useragent)) {
                  setcookie('alc', $alc, 0, '/');
                  setcookie('dvc', $dvc, 0, '/');
                  $_SESSION['alc'] = $alc;
                  $_SESSION['dvc'] = $dvc;
                  $_SESSION['device'] = $device;

                  $answer['status'] = 'ok';
                  $answer['msg'][] = 'Usuario logueado.';
              } else {
                  $answer['status'] = 'error';
                  $answer['msg'][] = 'Error creando la sesión.';
              }
          }
      } else {
          $answer['status'] = 'error';
          $answer['msg'][] = 'El usuario no se ha encontrado.';
      }
      echo json_encode($answer);
      break;
    case 'logout':
      $hc->getUserManager()->logOutUser();
      // logout user: set cookies, end user session, return status
      break;
    case 'register':
      // check credentials, create user, log in user?, return status
      $answer['status']='ok';
      if (empty($_POST['nombre'])) {
          $answer['status']='error';
          $answer['msg'][]='No se ha introducido el nombre.';
      }
      if (empty($_POST['email'])) {
          $answer['msg'][]='No se ha introducido el correo.';
          $answer['status']='error';
      }
      if (empty($_POST['usuario'])) {
          $answer['msg'][]='No se ha introducido el usuario.';
          $answer['status']='error';
      }
      if (empty($_POST['password'])) {
          $answer['msg'][]='No se ha introducido la contraseña.';
          $answer['status']='error';
      }
      if ($answer['status'] == 'error') {
          if (count($answer['msg']) === 0) {
              $answer['msg'] = 'Error desconocido.';
          }
          echo json_encode($answer);
          break;
      }
      if ($hc->getDB()->getConfigValue('site.recaptcha.active') == 'true') {
          $gRecaptchaValidation = checkGoogleRecaptcha($hc->getDB()->getConfigValue('site.recaptcha.secret'), $_POST['g-recaptcha-response']);
          if (!$gRecaptchaValidation['success']) {
              $answer['status']='error';
              $answer['msg'][]='Error con el Captcha.';
              if (!empty($gRecaptchaValidation['error-codes'])) {
                  foreach ($gRecaptchaValidation['error-codes'] as $gCaptchaErrorCode) {
                      $answer['msg'][]='recaptcha_'.$gCaptchaErrorCode;
                  }
              }
          }
      }

      if ($hc->getDB()->getUserDataByUsername($_POST['usuario'])!== false) {
          $answer['msg'][]='Ese user ya existe, por favor prueba de nuevo.';
          $answer['status']='error';
      }
      if ($hc->getDB()->getUserDataByEmail($_POST['email'])!== false) {
          $answer['msg'][]='Ese email ya está registrado, por favor prueba de nuevo.';
          $answer['status']='error';
      }
      if (!preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/', $_POST['email'])) {
          $answer['msg'][]='Ese email no es correcto, por favor prueba de nuevo.';
          $answer['status']='error';
      }
      if (!preg_match('/^([a-zA-Z0-9]{4,20})+$/', $_POST['usuario'])) {
          $answer['msg'][]='El usuario debe tener entre 4 y 20 caracteres, por favor prueba de nuevo.';
          $answer['status']='error';
      }
      if (!preg_match('/^([a-zA-Z0-9]{4,20})+$/', $_POST['usuario'])) {
          $answer['msg'][]='El usuario debe tener entre 4 y 20 caracteres, por favor prueba de nuevo.';
          $answer['status']='error';
      }
      /*if(!preg_match('/(?=.*[0-9])(?=.*[¡!¿?@#$%^&*\/\+_<>])(?=.*[a-z])(?=.*[A-Z]).{8,20}/', $_POST['password'])){
        $answer['msg'][]='La contraseña debe contener al menos un número, una letra minúscula, una mayúscula y un caracter espcecial; y tener entre 8 y 20 caracteres. Por favor, inténtelo de nuevo.';
        $answer['status']='error';
      }*/

      if ($answer['status'] == 'error') {
          if (count($answer['msg']) === 0) {
              $answer['msg'] = 'Error desconocido.';
          }
          echo json_encode($answer);
          break;
      }

      $u = new User($hc, -1, $_POST['usuario'], $_POST['email'], false, '-', 3);
      $salt = Utils::randStr(32);
      $pw = hash('sha256', $salt.$_POST['password']);
      $s = $hc->getDB()->registerNewUser($u, $pw, $salt, $_SESSION['csalt']);
      $_SESSION['csalt'] = '';
      unset($_SESSION['csalt']);
      if ($s) {
          $answer['status'] = 'ok';
          $answer['msg'] = 'Usuario creado correctamente';
      } else {
          $answer['status'] = 'error';
          $answer['msg'] = 'Error desconocido.';
      }

      echo json_encode($answer);
      break;
    case 'setWindowBox':
      $u = $hc->getUserManager()->getLoggedInUser();
      if ($u) {
          $boxes = $hc->getDB()->getUserDataById($u->getId())['boxesconfig'];
          $r = $_POST['row'];
          $c = $_POST['col'];
          if (!empty($boxes[$r]) && !empty($boxes[$r][$c])) {
              $boxes[$r][$c] = $_POST['service'];
              if ($hc->getDB()->updateWindowBoxService($u, $boxes)) {
                  $answer['status'] = 'ok';
                  $answer['msg'] = 'windowbox_updated';
              } else {
                  $answer['status'] = 'error';
                  $answer['msg'] = 'unknown';
              }
          } else {
              $answer['status'] = 'error';
              $answer['msg'] = 'invalid_coord_in_grid';
          }
      } else {
          $answer['status'] = 'error';
          $answer['msg'] = 'No se está logueado.';
      }
      echo json_encode($answer);
      break;
    case 'updateWindowLayout':
      $u = $hc->getUserManager()->getLoggedInUser();
      if ($u) {
          $boxes = $hc->getDB()->getUserDataById($u->getId())['boxesconfig'];
          $layout = $_POST['layout'];
          $listOfServices = [];
          foreach ($boxes as $rows) {
              foreach ($rows as $service) {
                  $listOfServices[] = $service;
              }
          }
          $serviceCounter = 0;
          $boxes = [];
          foreach ($layout as $cols) {
              $rowData = [];
              for ($i = 0; $i < $cols; $i++) {
                  if ($serviceCounter < count($listOfServices)) {
                      $rowData[] = $listOfServices[$serviceCounter];
                      $serviceCounter++;
                  } else {
                      $rowData[] = 'none';
                  }
              }
              $boxes[] = $rowData;
          }
          if ($hc->getDB()->updateWindowBoxService($u, $boxes)) {
              $answer['status'] = 'ok';
              $answer['msg'] = 'windowbox_updated';
          } else {
              $answer['status'] = 'error';
              $answer['msg'] = 'Error desconocido.';
          }
      } else {
          $answer['status'] = 'error';
          $answer['msg'] = 'No se está logueado.';
      }
      echo json_encode($answer);
      break;
    default:
      die('unkown_action');
      break;

      case 'admin':
        $answer['status'] = 'ok';
        $answer['msg'] = [];
        if (empty($_POST['newusuario'])) {
            $answer['status'] = 'error';
            $answer['msg'][] = 'No se ha introducido el usuario.';
        }
        if (empty($_POST['newpassword'])) {
            $answer['status'] = 'error';
            $answer['msg'][] = 'No se ha introducido la contraseña.';
        }
        if (empty($_POST['newemail'])) {
            $answer['status'] = 'error';
            $answer['msg'][] = 'No se ha introducido el correo.';
        }
        if ($answer['status'] == 'error') {
            echo json_encode($answer);
            break;
        }

        $username = $_POST['newusuario'];
        $email = $_POST['newemail'];
        if(!empty($_POST['isadmin']) && $_POST['isadmin'] == true){
          $role = 2;
        }
        else {
          $role = 3;
        }
        $password = $_POST['newpassword'];

        $u = new User($hc, -1, $username, $email, false, '-', $role);
        $salt = Utils::randStr(32);
        $csalt = Utils::randStr(32);
        $pw = hash('sha256', $csalt.$password);
        $pw = hash('sha256', $salt.$pw);
        $s = $hc->getDB()->registerNewUser($u, $pw, $salt, $csalt );

        echo json_encode($answer);
        break;
  }
}

function checkGoogleRecaptcha($secret, $response, $remoteip = false) {
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $params = [
      'secret' => $secret,
      'response' => $response,
    ];
    if ($remoteip !== false) {
        $params['remoteip'] = $remoteip;
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true);
}
