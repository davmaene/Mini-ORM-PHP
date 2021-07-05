<?php 

require_once("pont.php");
// ----------------------
// $config = new Main;
// var_dump($config->getConn());
$user = new User(1212, "david", "maene");
$tb = $user->onAdd()->res();
print_r($tb);
// $user;
?>