<?php 

require_once("pont.php");
// ----------------------
// $config = new Main;

$user = new User(1212, "david", "maene");
$tb = $user->save();
var_dump($tb);
?>