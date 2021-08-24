<?php
include(dirname(__FILE__) . "/../config/settings.php");
include(dirname(__FILE__) . "/../config/dbConn.php");
include(dirname(__FILE__) . "/../libs/custom/imgGen.php");
include(dirname(__FILE__) . "/../config/Classes/users.php");
include(dirname(__FILE__) . "/../config/Classes/dbot_ImgGen.php");
include(dirname(__FILE__) . "/../config/Classes/authenticator.php");
include(dirname(__FILE__) . "/../config/Classes/dropImageUploads.php");

$auth = new Authenticator();
$auth->Required_Admin("?target=index.php");

$dropsCount = $_POST["dropsCount"];
$template = $_POST["template"];
$permissionRole = $_POST["permissionRole"];
$dbot_dbId = 1;
$emj = "❤";

$processedImgName = bin2hex(openssl_random_pseudo_bytes(16)) . ".png";

$dirnameThis = dirname(__FILE__);
$root = substr($dirnameThis, 0, strrpos( $dirnameThis, '/'));
$target_dir = $root . "/dropImageUploadsProcessed/";
$target_file = $target_dir . $processedImgName;
$nameWithFolder =  "dropImageUploadsProcessed/" . $processedImgName;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

$imgGenerator = new ImgGenerator();
$dbot_diu = new DropImageUploads();

$unqUpl = $dbot_diu->GetAllUniqueUploadsInPermGroup($permissionRole);

$imgGenerator->
  Combine4by2_Basic($unqUpl[0]['LocalUrl'],$unqUpl[1]['LocalUrl'],$unqUpl[2]['LocalUrl'],$unqUpl[3]['LocalUrl'],$unqUpl[4]['LocalUrl'],$unqUpl[5]['LocalUrl'],$unqUpl[6]['LocalUrl'],$unqUpl[7]['LocalUrl'], $processedImgName);

$description =  
  $emj . $unqUpl[0]['Username'] . $emj . 
  $unqUpl[1]['Username'] . $emj . 
  $unqUpl[2]['Username'] . $emj . 
  $unqUpl[3]['Username'] . $emj . 
  $unqUpl[4]['Username'] . $emj . 
  $unqUpl[5]['Username'] . $emj . 
  $unqUpl[6]['Username'] . $emj . 
  $unqUpl[7]['Username'] . $emj;

echo $description . "<br>";
echo $nameWithFolder . "<br>";
echo $dbot_dbId . "<br>";

$dbot_ImgGen = new Dbot_ImgGen();

$dbot_ImgGen->Add($nameWithFolder, $description, $dbot_dbId);

// echo $msg;

header('Location: /generatedDrops.php'); 

?>
