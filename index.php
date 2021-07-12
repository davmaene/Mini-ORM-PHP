<?php 

require_once("pont.php");
// ----------------------
// $config = new Main;
$clause = array("fsname" => "dorone");
$user = new User(1212, "dorone", "maene");
// $tb = $user->save();
$us = $user->getOne($clause);
?>
<pre>
<?php 
    print_r($us);
?>
</pre>