<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!file_exists(__DIR__ . '/core/config.php')) {
  header('Location: install.php');
  die('<a href="install.php">Install</a>');
}

require_once(__DIR__ . '/core/hippocampus.php');

$hc = new Hippocampus();
$hc->run();
