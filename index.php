<?php 
require_once("pont.php"); // don't delete or modify this line
// ----------------------------------------------------------
// ----------------  include classes here ---------------------
// ----------------------------------------------------------
include_once("./models/cl.user.php");

$where = array("fsname" => "david");
$user = new User(null, "dorone", "maene");
// $tb = $user->save();
$us = $user->getOne($where);
?>

<pre>
<?= $us->results() ?>
</pre>