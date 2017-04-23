<?php
$boxes = $hc->getDB()->getUserDataById($u->getId())['boxesconfig'];
echo $boxes[$_POST['row']][$_POST['col']];
//echo '<pre>', print_r($columns, true), '</pre>';
?>
