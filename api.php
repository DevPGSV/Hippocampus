<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/core/hippocampus.php');
require_once(__DIR__ . '/core/utils.php');

$hc = new Hippocampus();

if (empty($_GET['action'])) die('No action');
$answer = [];
/// TODO
switch($_GET['action']) {
  case 'getSalt':
    //if (empty($_POST['user'])) die('No user');
    echo json_encode(['csalt'=>Utils::randStr(32)]);
    // return csalt
    break;
  case 'login':
    if (empty($_POST['user'])) $answer['msg']='No user';
    if (empty($_POST['pw'])) $answer['msg']='No password';
    // check credentials, set cookies, log in user, return status
    break;
  case 'logout':
    // logout user: set cookies, end user session, return status
    break;
  case 'register':
    // check credentials, create user, log in user?, return status
    $answer['status']='ok';
    if (empty($_POST['nombre'])) {
      $answer['status']='error';
      $answer['msg'][]='No nombre';
    }
    if (empty($_POST['email'])) {
      $answer['msg'][]='No email';
      $answer['status']='error';
    }
    if (empty($_POST['usuario'])) {
      $answer['msg'][]='No usuario';
      $answer['status']='error';
    }
    if (empty($_POST['password'])) {
      $answer['msg'][]='No password';
      $answer['status']='error';
    }
    // if (empty($_POST['confirmpassword'])) $answer['msg'][]='No contraseña confirmada';
    if ($answer['status'] == 'error'){
      if(count($answer['msg']) === 0) $answer['msg'] = 'Unknown';
      echo json_encode($answer);
      break;
    }
    /*if($_POST['confirmpassword'] != $_POST['password']){
      $answer['msg'][]='Las contraseñas no coinciden';
    }*/
    if($hc->getDB()->getUserDataByUsername($_POST['usuario'])!== false){
      $answer['msg'][]='Ese user ya existe, por favor prueba de nuevo.';
      $answer['status']='error';
    }
    if($hc->getDB()->getUserDataByEmail($_POST['email'])!== false){
      $answer['msg'][]='Ese email ya está registrado, por favor prueba de nuevo.';
      $answer['status']='error';
    }
    if(!preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/', $_POST['email'])){
      $answer['msg'][]='Ese email no es correcto, por favor prueba de nuevo.';
      $answer['status']='error';
    }
    if(!preg_match('/^([a-zA-Z0-9]{4,20})+$/', $_POST['usuario'])){
      $answer['msg'][]='El usuario debe tener entre 4 y 20 caracteres, por favor prueba de nuevo.';
      $answer['status']='error';
    }
    if(!preg_match('/^([a-zA-Z0-9]{4,20})+$/', $_POST['usuario'])){
      $answer['msg'][]='El usuario debe tener entre 4 y 20 caracteres, por favor prueba de nuevo.';
      $answer['status']='error';
    }
    /*if(!preg_match('/(?=.*[0-9])(?=.*[¡!¿?@#$%^&*\/\+_<>])(?=.*[a-z])(?=.*[A-Z]).{8,20}/', $_POST['password'])){
      $answer['msg'][]='La contraseña debe contener al menos un número, una letra minúscula, una mayúscula y un caracter espcecial; y tener entre 8 y 20 caracteres. Por favor, inténtelo de nuevo.';
      $answer['status']='error';
    }*/

    if ($answer['status'] == 'error'){
      if(count($answer['msg']) === 0) $answer['msg'] = 'Unknown';
      echo json_encode($answer);
      break;
    }

    $u = new User($hc, -1, $_POST['usuario'], $_POST['email'], false, 0, 3);
    $s = $hc->getDB()->registerNewUser($u);
    if ($s) {
      $answer['status'] = 'ok';
      $answer['msg'] = 'User created';
    } else {
      $answer['status'] = 'error';
      $answer['msg'] = 'Error creating user';
    }

    echo json_encode($answer);
    break;
  default:
    die('Unkown action');
    break;
}
