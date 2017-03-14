<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/core/hippocampus.php');

$hc = new Hippocampus();

if (empty($_GET['action'])) die('No action');

/// TODO
switch($_GET['action']) {
  case 'getSalt':
    if (empty($_POST['user'])) die('No user');
    // return csalt
    break;
  case 'login':
    if (empty($_POST['user'])) die('No user');
    if (empty($_POST['pw'])) die('No password');
    // check credentials, set cookies, log in user, return status
    break;
  case 'logout':
    // logout user: set cookies, end user session, return status
    break;
  case 'register':
    // check credentials, create user, log in user?, return status
    break;
  default:
    die('Unkown action');
    break;
}
