<?php

include(dirname(__FILE__) . "/../config/dbConn.php");
include(dirname(__FILE__) . "/../config/Classes/users.php");
include(dirname(__FILE__) . "/../config/settings.php");
include(dirname(__FILE__) . "/../config/Classes/authenticator.php");
$auth = new Authenticator();
$auth->Required_Admin("?target=index.php");

$id = $_POST["id"];
$firstName = $_POST["firstName"];
$lastName = $_POST["lastName"];
$IsAdmin = false;
$departmentId = $_POST['DepartmentId'];
//$shift = strtoupper($_POST["shift"]);
//$hash = password_hash($id, PASSWORD_DEFAULT);

$usersClass = new Users();

$usersClass
	->AddWithDep($id, $firstName, $lastName, $IsAdmin, $departmentId);

header('Location: /users.php'); 
exit();
?>