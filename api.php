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
/// TODO
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
              'msg' => 'user_not_found',
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
        $answer['msg'][] = 'no_user';
    }
    if (empty($_POST['password'])) {
        $answer['status'] = 'error';
        $answer['msg'][] = 'no_password';
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
            $answer['msg'][] = 'incorrect_password';
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

            if ($hc->getDB()->createNewUserSession($uData['id'], $device, $alc, $dvc, $ip, true, $firstUseSession, $lastUseSession, $firstUseCoordLat, $firstUseCoordLong, $useragent)) {
                setcookie('alc', $alc, 0, '/');
                setcookie('dvc', $dvc, 0, '/');
                $_SESSION['alc'] = $alc;
                $_SESSION['dvc'] = $dvc;
                $_SESSION['device'] = $device;

                $answer['status'] = 'ok';
                $answer['msg'][] = 'logged_in';
            } else {
                $answer['status'] = 'error';
                $answer['msg'][] = 'error_creating_session';
            }
        }
    } else {
        $answer['status'] = 'error';
        $answer['msg'][] = 'user_not_found';
    }
    echo json_encode($answer);
    break;
  case 'logout':
    // logout user: set cookies, end user session, return status
    break;
  case 'register':
    // check credentials, create user, log in user?, return status
    $answer['status']='ok';
    if (empty($_POST['nombre'])) {
        $answer['status']='error';
        $answer['msg'][]='no_name';
    }
    if (empty($_POST['email'])) {
        $answer['msg'][]='no_email';
        $answer['status']='error';
    }
    if (empty($_POST['usuario'])) {
        $answer['msg'][]='no_user';
        $answer['status']='error';
    }
    if (empty($_POST['password'])) {
        $answer['msg'][]='no_password';
        $answer['status']='error';
    }
    if ($answer['status'] == 'error') {
        if (count($answer['msg']) === 0) {
            $answer['msg'] = 'unknown';
        }
        echo json_encode($answer);
        break;
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
            $answer['msg'] = 'unknown';
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
        $answer['msg'] = 'user_created';
    } else {
        $answer['status'] = 'error';
        $answer['msg'] = 'unknown';
    }

    echo json_encode($answer);
    break;
  default:
    die('unkown_action');
    break;
}
