<?php

include(dirname(__FILE__) . "/../config/dbConn.php");
include(dirname(__FILE__) . "/../config/Classes/users.php");
include(dirname(__FILE__) . "/../config/settings.php");
include(dirname(__FILE__) . "/../config/Classes/authenticator.php");

$username = $_POST["username"];
$firstName = $_POST["password"];

$authenticator = new Authenticator();
$authenticator->LogIn($username, $hash);

?>