<?php

/**
	>>> DEPENDENCIES <<<

	include("config/dbConn.php");
 */
class Users
{
	
	function __construct()
	{
		# code...
	}

	public function Add($Id, $FirstName, $LastName, $IsAdmin, $Shift, $Hash = '')
	{
		if($IsAdmin == 0 || $IsAdmin == "0"){
			$IsAdmin = '';
		}
		$Hash = password_hash($Id, PASSWORD_DEFAULT);
		$dbConn = new DbConn();
		$sql = "INSERT INTO `Users`(Id, FirstName, LastName, IsAdmin, Shift, Hash) VALUES (?, ?, ?, ?, ?, ?)";
		$pdo = $dbConn->GetConnection();
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$Id, $FirstName, $LastName, $IsAdmin, $Shift, $Hash]);
	}

	public function GetUserData($Id)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT * FROM Users WHERE Id = " . $Id)->fetchAll();
		return $data[0];
	}

	public function Delete($Id)
	{
		$dbConn = new DbConn();
		$pdo = $dbConn->GetConnection();
		$sql = "DELETE FROM Users Where Id = ?";
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$Id]);
	}

	public function VerifiedLogin($Id, $Hash)
	{
		$CalcHash = password_hash($Id, PASSWORD_DEFAULT);
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT * FROM Users WHERE Id = " . $Id)->fetchAll();
		$DbHash = $data[0]['Password'];
		return 	password_verify($Hash, $DbHash) != '1' || 
				password_verify($Hash, $CalcHash) != '1';
	}

	public function GetPasswordHash($Id)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT * FROM Users WHERE Id = " . $Id)->fetchAll();
		return $data[0]['Password'];
	}
}



?>