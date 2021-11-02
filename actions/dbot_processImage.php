<?php
$START_TIME = microtime(true);
include(dirname(__FILE__) . "/../config/settings.php");
include(dirname(__FILE__) . "/../config/dbConn.php");
include(dirname(__FILE__) . "/../libs/custom/imgGen.php");
include(dirname(__FILE__) . "/../config/Classes/users.php");
include(dirname(__FILE__) . "/../config/Classes/dbot_db.php");
include(dirname(__FILE__) . "/../config/Classes/dbot_ImgGen.php");
include(dirname(__FILE__) . "/../config/Classes/authenticator.php");
include(dirname(__FILE__) . "/../config/Classes/dropImageUploads.php");
include(dirname(__FILE__) . "/../config/Classes/dbot_ImgGen_Users.php");
include(dirname(__FILE__) . "/../config/Classes/dbot_Descriptions.php");

$auth = new Authenticator();
$auth->Required_Admin("?target=index.php");


$dropsCount = $_POST["dropsCount"] ?? $_GET["dropsCount"];
$template = $_POST["template"] ?? $_GET["template"];
$permissionRole = $_POST["permissionRole"] ?? $_GET["permissionRole"];
$isFree = ($permissionRole == 26 || $permissionRole == 25) ? 0 : 1;
$dropSize = 8;
$dirnameThis = dirname(__FILE__);

// if(!$auth->IsSuperAdmin()){
//   header('Location: /actions/dbot_processImage2.php?dropsCount='.$dropsCount.'&template='.$template.'&permissionRole='-$permissionRole);
// }

$dbot_db = new Dbot_db();
$dbot_ImgGen = new Dbot_ImgGen();
$dbot_ImgGen_Users = new Dbot_ImgGen_Users();
$imgGenerator = new ImgGenerator();
$dbot_diu = new DropImageUploads();
$descClass = new Dbot_Descriptions();
$dbot_db->Add($permissionRole);
$dbot_dbId = $dbot_db->GetLast()["Id"];

$uniqueUsersWithOccurances = $dbot_diu->GetAllUniqueUsersWithPhotoInPermGroup($permissionRole);

$imagesArray = PrepareImagesArray($dropsCount, $dropSize, $uniqueUsersWithOccurances, $dbot_diu, $auth);

shuffle($imagesArray);
$splittedImages = SplitImagesIntoArrays($imagesArray);
$splittedImages = SwapEdgeUsers($splittedImages);
$splittedImages = SwapNotGroupUsers($splittedImages);
$splittedImages = CheckForDuplicitiesInArray($splittedImages);


for ($i=0; $i < (count($splittedImages) < $dropsCount ? $dropsCount : count($splittedImages)) ; $i++) { 
  $processedImgName = bin2hex(openssl_random_pseudo_bytes(16)) . ".png";
  $root = substr($dirnameThis, 0, strrpos( $dirnameThis, '/'));
  $target_dir = $root . "/dropImageUploadsProcessed/";
  $target_file = $target_dir . $processedImgName;
  $nameWithFolder =  "dropImageUploadsProcessed/" . $processedImgName;
  $imageUrls = array_fill(0, 8, "");
  $desc = $descClass->GetLastUsedAlways($isFree) ?? $descClass->GetLastUsedDescription($isFree);
  $descClass->UpdateLastUsedDate($desc["Id"]);
  $emj = utf8_decode($desc["EmojiUsing"]);
  $description = utf8_decode($desc["PreUsernameText"]) . "<br>";
  for ($y=0; $y < 4/*count($splittedImages[$i]) / 2*/; $y++) { 
    $description .= $emj . "@" . $splittedImages[$i][$y*2]['ProfileId'] . "&nbsp;&nbsp;" . $emj . 
      "@" . $splittedImages[$i][$y*2+1]['ProfileId'] . "<br>";
    $imageUrls[$y*2] = $splittedImages[$i][$y*2]['LocalUrl'];
    $imageUrls[$y*2+1] = $splittedImages[$i][$y*2+1]['LocalUrl'];
  }

  echo $description . "<br>";
  echo $nameWithFolder . "<br>";
  echo $dbot_dbId . "<br>";

 // if(!$auth->IsSuperAdmin()){
    $imgGenerator->
      Combine4by2_Basic($imageUrls, $processedImgName);
  $dbot_ImgGen->Add($nameWithFolder, utf8_encode($description), $dbot_dbId);
  $lastImgGenId = $dbot_ImgGen->GetLast()["Id"];
  for ($y=0; $y < count($splittedImages[$i]); $y++) { // Add dbot_ImgGen_Users records to DB
    $dbot_ImgGen_Users->Add(
      $dbot_dbId, 
      $splittedImages[$i][$y]["Id"], 
      $lastImgGenId, 
      $splittedImages[$i][$y]["ProfileId"], 
      0 // Not proofedd
    );
  }
}

  echo "<script>alert('Done in " . round(microtime(true) - $START_TIME,3) . " seconds');</script>";
if(!$auth->IsSuperAdmin()){
  header('Location: /generatedDrops.php'); 
  echo "<script>window.location.href='/generatedDrops.php';</script>";
}

function CheckForDuplicitiesInArray($splittedImages)
{
  for ($i=0; $i < count($splittedImages); $i++) { 
    $usersInArray = array();
    $copiedUsers = 0;
    for ($y=0; $y < count($splittedImages[$i]); $y++) { 
      if(in_array($splittedImages[$i][$y]["ProfileId"], $usersInArray))
      {
        if( 
          $i + 1 != count($splittedImages) &&
          $splittedImages[$i+1][$copiedUsers]["ProfileId"] != $splittedImages[$i][$y]["ProfileId"]
        ){
          $copiedUser = $splittedImages[$i+1][$copiedUsers];
          $splittedImages[$i+1][$copiedUsers] = $splittedImages[$i][$y];
          $splittedImages[$i][$y] = $copiedUser;
        }
        elseif (
          $i + 1 == count($splittedImages) &&
          $splittedImages[0][$copiedUsers]["ProfileId"] != $splittedImages[$i][$y]["ProfileId"]
        ){
          $copiedUser = $splittedImages[0][$copiedUsers];
          $splittedImages[0][$copiedUsers] = $splittedImages[$i][$y];
          $splittedImages[$i][$y] = $copiedUser;
        }
        else
        {
          $y--;
        }

        $copiedUsers++;
      }
      $usersInArray[count($usersInArray)] = $splittedImages[$i][$y]["ProfileId"];
    }
  }
  return $splittedImages;
}

function PrepareImagesArray($dropsCount, $dropSize, $uniqueUsersWithOccurancez, $dbot_diu, $auth)
{
  $uniqueUsersWithOccurances = $uniqueUsersWithOccurancez;
  $imageCount = $dropsCount * $dropSize;
  $imagesArray = array();
  $rangedIDs = array();
  list($uniqueUsersWithOccurances, $highestOccurance, $userUploadsArray) = GetUserData($uniqueUsersWithOccurances, $dbot_diu);

  for ($i=0; $i < count($uniqueUsersWithOccurances) ; $i++) { //for number of users fill those with occurances set
    
    if($uniqueUsersWithOccurances[$i]["Occurances"] < 9){
      
      //for ($y=0; $y < count($userUploadsArray[$i]); $y++) { 
      for ($y=0; $y < count($userUploadsArray[$i]); $y++) { 
        
        $imagesArray[count($imagesArray)] = $userUploadsArray[$i][$y];
        $imagesArray[count($imagesArray)-1]["OnTheEdge"] = $uniqueUsersWithOccurances[$i]["OnTheEdge"];
        $imagesArray[count($imagesArray)-1]["NotGroup"] = $uniqueUsersWithOccurances[$i]["NotGroup"];
        $uniqueUsersWithOccurances[$i]["Occurances"] = $uniqueUsersWithOccurances[$i]["Occurances"] - 1;
        
        if($uniqueUsersWithOccurances[$i]["Occurances"] == 0) 

          break;
      }
    }
    else
    {
      $rangedIDs[count($rangedIDs)] = $i;
    }
  }

  for ($i=0; $i <= $highestOccurance+1; $i++) { 
    for ($y=0; $y < count($rangedIDs); $y++) { 
      if($rangedIDs[$y]!= null && isset($userUploadsArray[$rangedIDs[$y]][$i])) // If user has enough images for another use
      {
        $imagesArray[count($imagesArray)] = $userUploadsArray[$rangedIDs[$y]][$i];
        $imagesArray[count($imagesArray)-1]["OnTheEdge"] = $uniqueUsersWithOccurances[$rangedIDs[$y]]["OnTheEdge"];
        $imagesArray[count($imagesArray)-1]["NotGroup"] = $uniqueUsersWithOccurances[$rangedIDs[$y]]["NotGroup"];
        $uniqueUsersWithOccurances[$rangedIDs[$y]]["Occurances"] = $uniqueUsersWithOccurances[$rangedIDs[$y]]["Occurances"] - 11;
      }
      else // If user don't have enough photos to be used (use his old images)
      {
        $imagesArray[count($imagesArray)] = $userUploadsArray[$rangedIDs[$y]][count($userUploadsArray[$rangedIDs[$y]])-1];
        $imagesArray[count($imagesArray)-1]["OnTheEdge"] = $uniqueUsersWithOccurances[$rangedIDs[$y]]["OnTheEdge"];
        $imagesArray[count($imagesArray)-1]["NotGroup"] = $uniqueUsersWithOccurances[$rangedIDs[$y]]["NotGroup"];
        $uniqueUsersWithOccurances[$rangedIDs[$y]]["Occurances"] = $uniqueUsersWithOccurances[$rangedIDs[$y]]["Occurances"] - 11;
      }
      if($uniqueUsersWithOccurances[$rangedIDs[$y]]["Occurances"] <= 0) // If user has been used for all of his occuraces, don't use him
        $rangedIDs[$y] = null;
      if($imageCount == count($imagesArray)) // If all photos for image are set stop the cycle
        break;

    }
    if($imageCount == count($imagesArray))
      break;
  }

  // {

  //   echo "imagesArray<pre>";
  //   var_export($imagesArray);
  //   echo "</pre>";


  //   echo "uniqueUsersWithOccurances<pre>";
  //   var_export($uniqueUsersWithOccurances);
  //   echo "</pre>";

  //   echo "userUploadsArray<pre>";
  //   var_export($userUploadsArray);
  //   echo "</pre>";


  //   echo "rangedIDs<pre>";
  //   var_export($rangedIDs);
  //   echo "</pre>";
  
  //   echo "highestOccurance: " . $highestOccurance;
  // }

  return $imagesArray;
}

function GetUserData($uniqueUsersWithOccurances, $EF_DIU)
{
  $highestOccurance = null;
  $imagesArray = array_fill(0, count($uniqueUsersWithOccurances), "");
  for ($i=0; $i < count($uniqueUsersWithOccurances); $i++) { 
    $uniqueUsersWithOccurancesValue = $uniqueUsersWithOccurances[$i]["Occurances"];
    $imagesArray[$i] = $EF_DIU->GetByUsernameWithLastUsed($uniqueUsersWithOccurances[$i]["Username"]); // Load users uploads
    $highestOccurance = 
      ($highestOccurance < $uniqueUsersWithOccurancesValue[0]) ? 
        $uniqueUsersWithOccurancesValue[0] : $highestOccurance;
  }
  return array($uniqueUsersWithOccurances, $highestOccurance, $imagesArray);
}

function SwapNotGroupUsers($splittedImages)
{
  for ($i=0; $i < count($splittedImages); $i++) { 
    $NotGroupAlredyContainedCount = 0;
    for ($y=0; $y < count($splittedImages[$i]); $y++) {
      if ($splittedImages[$i][$y]["NotGroup"] == 1) {
        if ($NotGroupAlredyContainedCount >= 0 && $NotGroupAlredyContainedCount < count($splittedImages[$i])) {
          if ($splittedImages[$i][$NotGroupAlredyContainedCount]["NotGroup"] == 0) {
            $localMemory = $splittedImages[$i][$NotGroupAlredyContainedCount];
            $splittedImages[$i][$NotGroupAlredyContainedCount] = $splittedImages[$i][$y];
            $splittedImages[$i][$y] = $localMemory;
            $NotGroupAlredyContainedCount++;
          }
          else{
            $NotGroupAlredyContainedCount++;
            $y--;
            break;
          }
        }
        elseif ($NotGroupAlredyContainedCount == count($splittedImages[$i])) {
          break;
        }
      }
    }
  }
  return $splittedImages;
}

function SwapEdgeUsers($splittedImages)
{
  echo "<pre>";
  var_export($splittedImages);
  echo "</pre>";
  for ($i=0; $i < count($splittedImages); $i++) { 
    list($isOnStart, $isOnEnd) = array($splittedImages[$i][0]["OnTheEdge"] == 1, false);
    echo "isOnStart?: ".$isOnStart."<br>";
    for ($y=1; $y < /*count($splittedImages[$i])*/ 8; $y++) { 
      if ($splittedImages[$i][$y]["OnTheEdge"] == 1) {
        if (!$isOnEnd && $isOnStart) {
          $localVar = $splittedImages[$i][1];
          $splittedImages[$i][1] = $splittedImages[$i][$y];
          $splittedImages[$i][$y] = $localVar;
          $isOnEnd = true;
          break;
        } 
        if (!$isOnStart && !$isOnEnd) {
          $localVar = $splittedImages[$i][0];
          $splittedImages[$i][0] = $splittedImages[$i][$y];
          $splittedImages[$i][$y] = $localVar;
          $isOnStart = true;
        }
      }
    }
  }
  return $splittedImages;
}

function SplitImagesIntoArrays($users, $divideBy=8)
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
    echo "<pre>";
    var_export($output);
    echo "</pre>";
  }
  return $output;
}

// Automatically use the highest value from occurances range (set the highest value)
function SimplifyOccurances($uniqueUsersWithOccurances, $EF_DIU)
{
  $highestOccurance = 0;
  $imagesArray = array_fill(0, count($uniqueUsersWithOccurances), "");
  for ($i=0; $i < count($uniqueUsersWithOccurances); $i++) { 
    $uniqueUsersWithOccurancesValue = $uniqueUsersWithOccurances[$i]["Occurances"];
    $imagesArray[$i] = $EF_DIU->GetByUsernameWithLastUsed($uniqueUsersWithOccurances[$i]["Username"]); // Load users uploads
    if ($uniqueUsersWithOccurancesValue > 9) {
      $uniqueUsersWithOccurancesValue = $uniqueUsersWithOccurancesValue[strlen($uniqueUsersWithOccurancesValue)-1];
      $uniqueUsersWithOccurances[$i]['Occurances'] = $uniqueUsersWithOccurancesValue; 
    }
    $highestOccurance = 
      ($highestOccurance < $uniqueUsersWithOccurancesValue) ? 
        $uniqueUsersWithOccurancesValue : $highestOccurance;
  }
            echo $i . ", ";
            echo "userUploadsArray:<pre>";
            var_export($uniqueUsersWithOccurances);
            echo "</pre>";
  return array($uniqueUsersWithOccurances, $highestOccurance, $imagesArray);
}

?>
