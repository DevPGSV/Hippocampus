<?php
$boxes = $hc->getDB()->getUserDataById($u->getId())['boxesconfig'];
//echo '<p>Dummy data for service: <em>'.$boxes[$_POST['row']][$_POST['col']].'</em></p>';

$cbdata = [];
if (!empty($_POST['cbdata'])) $cbdata = $_POST['cbdata'];
$d = $hc->moduleManager->onWindowContent($boxes[$_POST['row']][$_POST['col']], $cbdata);
//echo $d['html'];
echo json_encode($d);
