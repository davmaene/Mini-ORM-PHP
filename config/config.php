<?php 
// custermer enviroment infos goes here
$customer_dbname = ""; // the name of your database
$dialect = "mysql";
$customer_hostname = "localhost";
$customer_username = "root";
$customer_password = "";
// dont modify code beyond this line
// ---------------------------------
define("dialect",
     array(
         "dialect" => isset($dialect) ? $dialect : "mysql",
         "dbname" => $customer_dbname ?? "test",
         "hostname" => $customer_hostname ?? "localhost",
         "username" => $customer_username ?? "root",
         "password" => $customer_password ?? ""
     ), 
true);
var_dump(dialect);
?>