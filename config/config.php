<?php 
// custermer enviroment infos goes here
$customer_dbname = "baw16wt8ois4euxctcvd" ?? "___db_transpay_ms" ?? "_dbmidleware"; // the name of your database
$customer_dialect = "mysql"; // env database service cloud
$customer_hostname = "baw16wt8ois4euxctcvd-mysql.services.clever-cloud.com" ?? "localhost"; // name or ip of host
$customer_username = "uwwve3sdwrz9mzzd" ?? "root"; // username to access to db
$customer_password = "VwzZjusFZTG0FEWaXtOA" ?? ""; // password to access to db
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