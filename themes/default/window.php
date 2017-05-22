<?php
$boxes = $hc->getDB()->getUserDataById($u->getId())['boxesconfig'];
//echo '<p>Dummy data for service: <em>'.$boxes[$_POST['row']][$_POST['col']].'</em></p>';
$d = $hc->moduleManager->onWindowContent($boxes[$_POST['row']][$_POST['col']]);
//echo $d['html'];
echo json_encode($d);
