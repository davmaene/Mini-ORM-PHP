<?php 
// ----------------
require("pont.php");
require("main.php");
$config = new Main;
// var_export($config->getConn(), false);
$m = explode("*", "davidmaene");
$m = implode("---", $m);
var_dump($m);
?>