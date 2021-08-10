<?php

include(dirname(__FILE__) . "/../config/dbConn.php");
include(dirname(__FILE__) . "/../config/settings.php");
include(dirname(__FILE__) . "/../config/Classes/users.php");
include(dirname(__FILE__) . "/../config/Classes/posts.php");
include(dirname(__FILE__) . "/../config/Classes/authenticator.php");
$auth = new Authenticator();
$auth->Required_User("?target=index.php");

$users = new Users();
$userData = $users->GetUserData($auth->GetUserId());

$UserId = $userData['Id'];
$Username = $userData['Username'];
$PostName = $_POST["PostName"];
$PostText = $_POST["PostText"];
$PostUrlData = ""; // Add image upload
$PostPrice = 0;
//	$PostponePost = $_POST["PostponePost"];		-- Prepared for update
//	$PostDate = $_POST["PostDate"];				-- Prepared for update
//	$PostTime = $_POST["PostTime"];				-- Prepared for update

$posts = new Posts();
$posts->Add($UserId, $Username, $PostName, $PostText, $PostUrlData, $PostPrice);

//$shift = strtoupper($_POST["shift"]);
//$hash = password_hash($id, PASSWORD_DEFAULT);

header('Location: /profile.php?id=' . $Username); 
exit();
?>