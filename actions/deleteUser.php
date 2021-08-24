<?php

include(dirname(__FILE__) . "/../config/dbConn.php");
include(dirname(__FILE__) . "/../config/settings.php");
include(dirname(__FILE__) . "/../config/Classes/users.php");
include(dirname(__FILE__) . "/../config/Classes/authenticator.php");
$auth = new Authenticator();
$auth->Required_Admin("?target=index.php");

$id = $_GET["Id"];

//BEFORE DETITION USER ANONYMIZE REPAIRS

$usersClass = new Users();

$usersClass
	->Delete($id);

header('Location: /users.php'); 
exit();
?>