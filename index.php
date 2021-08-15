<?php 
require_once("pont.php"); // don't delete or modify this line
// ----------------------------------------------------------
// ----------------  include classes here ---------------------
// ----------------------------------------------------------
include_once("./models/cl.user.php");

$where = array("fsname" => "david");
$user = new User(null, "david", "maene", 1, "", "kubuya.darone.david@gmail.com", "+243970284772", base64_encode("zaqxswcde"));
$tb = $user->save();
$us = $user->getOne($where);
?>
<pre>
<?= $us->results() ?>
</pre>