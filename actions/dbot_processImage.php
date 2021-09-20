<?php
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

$dropsCount = $_POST["dropsCount"];
$template = $_POST["template"];
$permissionRole = $_POST["permissionRole"];
$isFree = ($permissionRole == 26 || $permissionRole == 25) ? 0 : 1;
$dropSize = 8;
$dirnameThis = dirname(__FILE__);

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
    $description .= $emj . "@" . $splittedImages[$i][$y*2]['ProfileId'] . $emj . 
      "@" . $splittedImages[$i][$y*2+1]['ProfileId'] . "<br>";
    $imageUrls[$y*2] = $splittedImages[$i][$y*2]['LocalUrl'];
    $imageUrls[$y*2+1] = $splittedImages[$i][$y*2+1]['LocalUrl'];
  }



  echo $description . "<br>";
  echo $nameWithFolder . "<br>";
  echo $dbot_dbId . "<br>";

  if(!$auth->IsSuperAdmin()){
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
}

if(!$auth->IsSuperAdmin()){
  header('Location: /generatedDrops.php'); 
  echo "<script>window.location.href='/generatedDrops.php';</script>";
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

function PrepareImagesArray($dropsCount, $dropSize, $uniqueUsersWithOccurances, $EF_DIU, $EF_AUTH)
{
  $imageCount = $dropsCount * $dropSize;
  echo "ImageCount: " . $imageCount;
  $imagesArray = array_fill(0, $imageCount, "");
  $overflowIndex = count($uniqueUsersWithOccurances);
  list($uniqueUsersWithOccurances, $highestOccurance, $userUploadsArray) 
    = SimplifyOccurances($uniqueUsersWithOccurances, $EF_DIU);
  for ($i=0; $i < count($uniqueUsersWithOccurances); $i++) { 
      $imagesArray[$i] = $userUploadsArray[$i][0];
      $imagesArray[$i] += ['OnTheEdge' => $uniqueUsersWithOccurances[$i]["OnTheEdge"]];
      $uniqueUsersWithOccurances[$i]["Occurances"] = $uniqueUsersWithOccurances[$i]["Occurances"] - 1;
  }
  // if($EF_AUTH->IsSuperAdmin()){
  //   echo "<pre>";
  //   var_export($uniqueUsersWithOccurances);
  //   echo "</pre>";
  // }
  for ($rotations=1; $rotations < $highestOccurance; $rotations++) { 
    if(count($imagesArray) == $imageCount)
      break;
    for ($i=0; $i < (count($uniqueUsersWithOccurances)-1); $i++) { 
      if(count($imagesArray) == $imageCount)
        break;
      if($uniqueUsersWithOccurances[$i]["Occurances"] > 0)
      {
        if(count($userUploadsArray[$i]) >= $rotations)
          { 
            // echo $i . ", ";
            // echo "userUploadsArray:<pre>";
            // var_export(['OnTheEdge' => $uniqueUsersWithOccurances[$i]["OnTheEdge"]]);
            // echo "</pre>";
            $imagesArray[$overflowIndex] = $userUploadsArray[$i][$rotations-1];
            $imagesArray[$overflowIndex] += ['OnTheEdge' => $uniqueUsersWithOccurances[$i]["OnTheEdge"]];
           }
        else 
          { 
            $imagesArray[$overflowIndex] = $userUploadsArray[$i][0];
            $imagesArray[$overflowIndex] += ['OnTheEdge' => $uniqueUsersWithOccurances[$i]["OnTheEdge"]];
          }
        $overflowIndex++;
        $uniqueUsersWithOccurances[$i]["Occurances"] = $uniqueUsersWithOccurances[$i]["Occurances"] - 1;
        if($overflowIndex == $imageCount)
          break;
      }
    }
    if($overflowIndex == $imageCount)
      break;
  }
  echo "<br>imagesArray: " . count($imagesArray);
  return $imagesArray;
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
