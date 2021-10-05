<?php

/**
	>>> DEPENDENCIES <<<

	include("config/dbConn.php");
 */
class DropImageUploads
{
	
	function __construct()
	{
		# code...
	}

   public function Add($LocalUrl, $UserId)
   {
      $dbConn = new DbConn();
      $sql = "INSERT INTO dbot_diu (LocalUrl , ProfileId) VALUES (?, ?)";
      $pdo = $dbConn->GetConnection();
      $stmt= $pdo->prepare($sql);
      $stmt->execute([$LocalUrl, $UserId]);
   }

   public function GetById($PostId)
   {
      $dbConn = new DbConn(); 
      $pdo = $dbConn->GetConnection();
      $data = $pdo->query("SELECT * FROM dbot_diu WHERE Id = '" . $PostId . "'")->fetchAll();
      return $data[0];
   }

   public function GetByUsername($Username, $descend = true)
   {
      $dbConn = new DbConn(); 
      $pdo = $dbConn->GetConnection();
      if(!$descend)
         $data = $pdo->query("SELECT * FROM dbot_diu WHERE ProfileId = '" . $Username . "'")->fetchAll();
      else
         $data = $pdo->query("SELECT * FROM dbot_diu WHERE ProfileId = '" . $Username . "' ORDER BY `dbot_diu`.`Created` DESC")->fetchAll();
      return $data;
   }

   public function GetByUsernameWithLastUsed($Username, $descend = true)
   {
      $dbConn = new DbConn(); 
      $pdo = $dbConn->GetConnection();
      if(!$descend)
         $data = $pdo->query('
            SELECT diu.*, COALESCE(MAX(dbot_db.Created),"1990-01-01") LastUsed
            FROM dbot_diu diu
            LEFT JOIN dbot_imggen_users users
            ON diu.Id = users.dbot_diu_Id
            LEFT JOIN dbot_db dbot_db
            ON users.dbot_db_Id = dbot_db.Id
            WHERE diu.ProfileId = "' . $Username . '"
            GROUP BY diu.Id
            ORDER BY LastUsed ASC, diu.Id ASC')->fetchAll();
      else
         $data = $pdo->query('
            SELECT diu.*, COALESCE(MAX(dbot_db.Created),"1990-01-01") LastUsed
            FROM dbot_diu diu
            LEFT JOIN dbot_imggen_users users
            ON diu.Id = users.dbot_diu_Id
            LEFT JOIN dbot_db dbot_db
            ON users.dbot_db_Id = dbot_db.Id
            WHERE diu.ProfileId = "' . $Username . '"
            GROUP BY diu.Id
            ORDER BY LastUsed ASC, diu.Id DESC')->fetchAll();
      return $data;
   }

   public function GetAll($descend = true)
   {
      $dbConn = new DbConn(); 
      $pdo = $dbConn->GetConnection();
      if(!$descend)
         $data = $pdo->query("SELECT * FROM dbot_diu")->fetchAll();
      else
         $data = $pdo->query("SELECT * FROM dbot_diu ORDER BY `dbot_diu`.`Created` DESC")->fetchAll();
      return $data;
   }

   public function WriteAllUserUploads()
   {
      $dbConn = new DbConn(); 
      $pdo = $dbConn->GetConnection();
      $data = $pdo->query("SELECT u.PermissionLevel as Perm, u.Username as Username, count(diu.Id) as Count FROM users u LEFT JOIN dbot_diu diu ON u.Username = diu.ProfileId Group by u.Username, u.PermissionLevel ORDER BY u.PermissionLevel")->fetchAll();
      for($i = 0; $i < sizeof($data); $i++){
         echo "<tr><td>" . $data[$i]['Perm'] . "</td><td>" . $data[$i]['Username'] . "</td><td>" . $data[$i]['Count'] . "</td></tr>";
      }   
   }

   public function GetAllUniqueUploads()
   {
      $dbConn = new DbConn(); 
      $pdo = $dbConn->GetConnection();
      $data = $pdo->query("SELECT * FROM dbot_diu ORDER BY `dbot_diu`.`Id` DESC LIMIT 25")->fetchAll();
      return $data;
   }

   public function GetAllUniqueUsersWithPhoto()
   {
      $dbConn = new DbConn(); 
      $pdo = $dbConn->GetConnection();
      $data = $pdo->query("SELECT DISTINCT(ProfileId) FROM dbot_diu ORDER BY `dbot_diu`.`ProfileId` ASC")->fetchAll();
      return $data;
   }

   public function GetAllUniqueUsersWithPhotoInPermGroup($permGroup, $active = 1)
   {
      $dbConn = new DbConn(); 
      $pdo = $dbConn->GetConnection();
      $data = $pdo->query("SELECT DISTINCT(diu.ProfileId) AS Username, u.Occurances AS Occurances, u.OnTheEdge as OnTheEdge, u.NotGroup as NotGroup FROM dbot_diu diu INNER JOIN users u ON u.Username = diu.ProfileId WHERE u.PermissionLevel = " . $permGroup . " and u.Active = " . $active . " ORDER BY Username ASC")->fetchAll();
      return $data;
   }

   public function GetAllUniqueUploadsInPermGroup($permGroup)
   {
      $dbConn = new DbConn(); 
      $pdo = $dbConn->GetConnection();
      $data = $pdo->query("SELECT * FROM dbot_diu diu INNER JOIN users u ON u.Username = diu.ProfileId WHERE u.PermissionLevel = ".$permGroup." and u.Active = 1 ORDER BY u.Username ASC")->fetchAll();
      return $data;
   }

	public function Delete($Id)
	{
		$dbConn = new DbConn();
		$pdo = $dbConn->GetConnection();
		$sql = "DELETE FROM dbot_diu Where Id = ?";
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$Id]);
	}

   public function BuildPost($Id, $Created, $myProfileImageUrl, $Username, $ImageUrl, $PostText)
   {
      echo 
'<li>
   <div class="timeline-time">
      <span class="date">' . $Created . '</span>
   </div>
   <!-- end timeline-time -->
   <!-- begin timeline-icon -->
   <div class="timeline-icon">
      <a href="javascript:;">&nbsp;</a>
   </div>
   <!-- end timeline-icon -->
   <!-- begin timeline-body -->
   <div class="timeline-body">
      <div class="timeline-header">
         <span class="userimage"><img src="' . $myProfileImageUrl . '" alt=""></span>
         <span class="username"><i>@<a href="profile.php?id=' . $Username . '">' . $Username . '</a></i> <small></small></span>
      </div>
      <div class="timeline-content">
         <img src="' . $ImageUrl . '">
      </div>
      <div class="timeline-content">
         <p>
            ' . $PostText . '
         </p>
      </div>
      <div class="timeline-likes">
         <div class="stats-right">
            <span class="stats-text">
               <a href="actions/deleteDropImageUpload.php?imageId=' . $Id . '" class="btn btn-sm btn-danger">Delete</a>
            </span>
         </div>
         <div class="stats">
            <span class="fa-stack fa-fw stats-icon">
            <i class="fa fa-circle fa-stack-2x text-primary"></i>
            <i class="fa fa-thumbs-up fa-stack-1x fa-inverse"></i>
            </span>
            <span class="stats-total">&nbsp;</span>
         </div>
      </div>
   </div>
   <!-- end timeline-body -->
</li>';
   }


}
?>