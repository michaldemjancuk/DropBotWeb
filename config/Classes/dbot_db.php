<?php

/**
	>>> DEPENDENCIES <<<

	include("config/dbConn.php");
 */
class Dbot_db
{
	
	function __construct()
	{
		# code...
	}

   public function Add($permissionRole = 0)
   {
      $dbConn = new DbConn();
      $sql = "INSERT INTO dbot_db (PermissionRole) VALUES (?)";
      $pdo = $dbConn->GetConnection();
      $stmt= $pdo->prepare($sql);
      $stmt->execute([$permissionRole]);
   }

	public function GetById($Id)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT * FROM dbot_db WHERE Id = '" . $Id . "'")->fetchAll();
		return $data[0];
	}

   public function GetLast()
   {
      $dbConn = new DbConn(); 
      $pdo = $dbConn->GetConnection();
      $data = $pdo->query("SELECT * FROM dbot_db ORDER BY `dbot_db`.`Id` DESC LIMIT 1")->fetchAll();
      return $data;
   }

	public function Delete($Id)
	{
		$dbConn = new DbConn();
		$pdo = $dbConn->GetConnection();
		$sql = "DELETE FROM dbot_db Where Id = ?";
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$Id]);
	}


}
?>