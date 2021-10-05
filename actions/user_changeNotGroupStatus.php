<?php

include(dirname(__FILE__) . "/../config/dbConn.php");
include(dirname(__FILE__) . "/../config/Classes/users.php");
include(dirname(__FILE__) . "/../config/settings.php");
include(dirname(__FILE__) . "/../config/Classes/authenticator.php");
$auth = new Authenticator();
$auth->Required_Admin("?target=index.php");

$Username = $_POST["Username"];
$NotGroup = $_POST["NotGroup"];

$usersClass = new Users();

$usersClass
	->UpdateNotGroupStatus($Username, $NotGroup);

exit();
?>