<?php
$boxes = $hc->getDB()->getUserDataById($u->getId())['boxesconfig'];
echo '<p>Dummy data for service: <em>'.$boxes[$_POST['row']][$_POST['col']].'</em></p>';
//echo '<pre>', print_r($columns, true), '</pre>';
