<?php

/**
	>>> DEPENDENCIES <<<

	include("config/dbConn.php");
 */
class Dbot_ImgGen_Users
{
	
	function __construct()
	{
		# code...
	}

   public function Add($dbot_db_Id, $dbot_diu_Id, $dbot_imggen_Id, $ProfileId, $Proofed = 0)
   {
      $dbConn = new DbConn();
      $sql = "INSERT INTO dbot_imggen_users (dbot_db_Id, dbot_diu_Id, dbot_imggen_Id, ProfileId, Proofed) VALUES (?, ?, ?, ?, ?)";
      $pdo = $dbConn->GetConnection();
      $stmt= $pdo->prepare($sql);
      $stmt->execute([$dbot_db_Id, $dbot_diu_Id, $dbot_imggen_Id, $ProfileId, $Proofed]);
   }

	public function GetById($Id)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT * FROM dbot_imggen_users WHERE Id = '" . $Id . "'")->fetchAll();
		return $data[0];
	}

	public function GetAllWithDbotDbId($Id)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT u.ProfileId as Username, COUNT(u.Id) as CountOccur FROM dbot_imggen_users u WHERE dbot_db_Id = '" . $Id . "'  GROUP BY u.ProfileId ORDER BY CountOccur DESC, Username")->fetchAll();
		return $data;
	}

	public function Delete($Id)
	{
		$dbConn = new DbConn();
		$pdo = $dbConn->GetConnection();
		$sql = "DELETE FROM dbot_imggen_users Where Id = ?";
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$Id]);
	}


}
?>