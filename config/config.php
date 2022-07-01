<?php 
// custermer enviroment infos goes here
<<<<<<< HEAD
$customer_dbname = "___db_transpay_ms"; // the name of your database
=======
$customer_dbname = "sigh_db"; // the name of your database
>>>>>>> 2582f00ef8c7b6af10712fe0f9b4c407545d30e1
$customer_dialect = "mysql"; // env database service cloud
$customer_hostname = "localhost"; // name or ip of host
$customer_username = "root"; // username to access to db
$customer_password = ""; // password to access to db
// ---------------------------------------------------------
//          dont modify code beyond this line
// ---------------------------------------------------------
define("env", // environement
     array(
         "dialect" => $customer_dialect ?? "mysql",
         "dbname" => $customer_dbname ?? "test",
         "hostname" => $customer_hostname ?? "localhost",
         "username" => $customer_username ?? "root",
         "password" => $customer_password ?? ""
     ));
// 
?>