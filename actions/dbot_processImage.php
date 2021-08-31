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
$processedImgName = "";
$dirnameThis = dirname(__FILE__);

$imgGenerator = new ImgGenerator();
$dbot_diu = new DropImageUploads();

$unqUpl = $dbot_diu->GetAllUniqueUploadsInPermGroup($permissionRole);
$uniqueUsers = $dbot_diu->GetAllUniqueUsersWithPhotoInPermGroup($permissionRole);

shuffle($unqUpl);

$splittedUsers = SplitUsersIntoArrays($unqUpl);

for ($i=0; $i < (count($splittedUsers) < $dropsCount ? $dropsCount : count($splittedUsers)) ; $i++) { 
  $processedImgName = bin2hex(openssl_random_pseudo_bytes(16)) . ".png";
  $root = substr($dirnameThis, 0, strrpos( $dirnameThis, '/'));
  $target_dir = $root . "/dropImageUploadsProcessed/";
  $target_file = $target_dir . $processedImgName;
  $nameWithFolder =  "dropImageUploadsProcessed/" . $processedImgName;
  $imageUrls = array_fill(0, 8, "");
  $description = $emj;
  for ($y=0; $y < count($splittedUsers[$i]); $y++) { 
    $description .= $splittedUsers[$i][$y]['Username'] . $emj;
    $imageUrls[$y] = $splittedUsers[$i][$y]['LocalUrl'];
  }
  $imgGenerator->
    Combine4by2_Basic($imageUrls, $processedImgName);

  echo $description . "<br>";
  echo $nameWithFolder . "<br>";
  echo $dbot_dbId . "<br>";

  $dbot_ImgGen = new Dbot_ImgGen();

  $dbot_ImgGen->Add($nameWithFolder, $description, $dbot_dbId);
}

// $unqUploads = array($unqUpl[0]['LocalUrl'],$unqUpl[1]['LocalUrl'],$unqUpl[2]['LocalUrl'],$unqUpl[3]['LocalUrl'],$unqUpl[4]['LocalUrl'],$unqUpl[5]['LocalUrl'],$unqUpl[6]['LocalUrl'],$unqUpl[7]['LocalUrl']);

// $imgGenerator->
//   Combine4by2_Basic_2($unqUploads, $processedImgName);



// echo $msg;

//header('Location: /generatedDrops.php'); 


function SplitUsersIntoArrays($users, $divideBy=8)
{
  $auth = new Authenticator();
  $input      = $users;
  $chunk_size = $divideBy;
  $output     = array_chunk($input, $chunk_size);

  // If we can't chunk into equal sized parts merge the last two arrays.
  if(count($input) % $chunk_size) {
    $leftovers = array_pop($output);
    $last      = array_pop($output);
    array_push($output, array_merge($last, $leftovers));
  }

  if($auth->IsSuperAdmin()){
    // echo "<pre>";
    // var_export($output);
    // echo "</pre>";
  }

  return $output;
}

?>
