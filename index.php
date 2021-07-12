<?php 
require_once("pont.php"); // don't delete or modify this line
// ----------------------
$where = array("fsname" => "david");
$user = new User(null, "dorone", "maene");
// $tb = $user->save();
$us = $user->getOne($where);
?>
<pre>
<?php 
    var_dump($us);
?>
</pre>