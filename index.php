<?php 

require_once("pont.php");
// ----------------------
$config = new Main;
// var_dump($config->getConn());
$user = new User(1212, "david", "maene");
$user->onAdd($user)->res();
// $user;
?>