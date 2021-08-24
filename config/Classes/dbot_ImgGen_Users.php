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

   public function Add($statusCode = 0)
   {
      $dbConn = new DbConn();
      $sql = "INSERT INTO dbot_ImgGen_Users (StatusCode) VALUES (?)";
      $pdo = $dbConn->GetConnection();
      $stmt= $pdo->prepare($sql);
      $stmt->execute([$statusCode]);
   }

	public function GetById($Id)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT * FROM dbot_ImgGen_Users WHERE Id = '" . $Id . "'")->fetchAll();
		return $data[0];
	}

   public function GetLast()
   {
      $dbConn = new DbConn(); 
      $pdo = $dbConn->GetConnection();
      $data = $pdo->query("SELECT * FROM dbot_ImgGen_Users ORDER BY `dbot_ImgGen_Users`.`Id` DESC LIMIT 1")->fetchAll();
      return $data;
   }

	public function Delete($Id)
	{
		$dbConn = new DbConn();
		$pdo = $dbConn->GetConnection();
		$sql = "DELETE FROM dbot_ImgGen_Users Where Id = ?";
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$Id]);
	}


}
?>