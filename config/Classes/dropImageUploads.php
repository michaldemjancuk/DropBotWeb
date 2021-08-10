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
		$sql = "INSERT INTO dropimageuploads (LocalUrl, ProfileId) VALUES (?, ?)";
		$pdo = $dbConn->GetConnection();
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$LocalUrl, $UserId]);
	}

	public function GetById($PostId)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT * FROM dropimageuploads WHERE Id = '" . $PostId . "'")->fetchAll();
		return $data[0];
	}

	public function GetByUsername($Username, $descend = true)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		if(!$descend)
			$data = $pdo->query("SELECT * FROM dropimageuploads WHERE Username = '" . $Username . "'")->fetchAll();
		else
			$data = $pdo->query("SELECT * FROM dropimageuploads WHERE Username = '" . $Username . "' ORDER BY `dropimageuploads`.`Created` DESC")->fetchAll();
		return $data;
	}

	public function Delete($Id)
	{
		$dbConn = new DbConn();
		$pdo = $dbConn->GetConnection();
		$sql = "DELETE FROM dropimageuploads Where Id = ?";
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$Id]);
	}


}
?>