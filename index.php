<?php 

require_once("pont.php");
// ----------------------
// $config = new Main;
$clause = array("id" => 1);
$user = new User(1212, "david", "maene");
$tb = $user->save();
$us = $user->getOne($clause);
var_dump($us);
?>