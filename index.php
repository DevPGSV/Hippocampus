<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/core/hippocampus.php');

$hc = new Hippocampus();
$hc->run();
