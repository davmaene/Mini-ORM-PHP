<?php 
// custermer enviroment infos goes here
$customer_dbname = "_dbmidleware"; // the name of your database
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
     ), 
true);
// 
?>