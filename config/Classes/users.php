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

	public function AddWithDep($Id, $FirstName, $LastName, $IsAdmin, $DepartmentId)
	{
		if($IsAdmin == 0 || $IsAdmin == "0"){
			$IsAdmin = '';
		}
		$Hash = password_hash($Id, PASSWORD_DEFAULT);
		$dbConn = new DbConn();
		$sql = "INSERT INTO `Users`(Id, FirstName, LastName, IsAdmin, Hash, DepartmentId) VALUES (?, ?, ?, ?, ?, ?)";
		$pdo = $dbConn->GetConnection();
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$Id, $FirstName, $LastName, $IsAdmin, $Hash, $DepartmentId]);
	}

	public function IsSupervisor($userId)
	{
		$dbConn = new DbConn();
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT Id FROM Users WHERE IsSupervisor = 1 AND Id = " . $userId)->fetchAll();
		return count($data) > 0;
	}

	public function AddMultipleUsers($dataInQuery)
	{
		try {
			$sqlInsert = "INSERT INTO `Users`(Id, FirstName, LastName, IsAdmin, Hash, DepartmentId) VALUES";
			$sqlInsert .= $dataInQuery;
			echo "$sqlInsert";
			$dbConn = new DbConn();
			$pdo = $dbConn->GetConnection();
			$pdo->exec($sqlInsert);
		} catch(Exception $e)
		{
		    echo $e->getMessage();        
		}
	}
	
	public function ResetDepartmentIdForUsersWithIt($orderId)
	{
		$dbConn = new DbConn();
		$sql = "UPDATE `Users` SET DepartmentId = NULL WHERE DepartmentId = ?";
		$pdo = $dbConn->GetConnection();
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$orderId]);
	}

	public function GetUsersDepartmentById($Id)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT DepartmentId FROM Users WHERE Id = " . $Id)->fetchAll();
		return $data[0][0];
	}
	public function GetAllUsersInfoToSelect()
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT FirstName, LastName, Id FROM Users WHERE IsAdmin = b'0'")->fetchAll();
		return $data;
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

	public function ResetPassword($Id)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$stmt = $pdo->query("UPDATE Users SET Password = NULL WHERE Id =" . $Id);
	}

	public function CheckPasswordExists($Id)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT * FROM Users WHERE Id = " . $Id)->fetchAll();
		return strlen($data[0]['Password']) != 0;
	}

	public function GetUserHash($Id)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT * FROM Users WHERE Id = " . $Id)->fetchAll();
		return $data[0]['Hash'];
	}

	public function GetPasswordHash($Id)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT * FROM Users WHERE Id = " . $Id)->fetchAll();
		return $data[0]['Password'];
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

	public function CheckAccountExists($Id)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT Id FROM Users WHERE Id =" . $Id)->fetchAll();
		return count($data) > 0;
	}

	public function CheckAccountHasAdminRights($Id)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT Id FROM Users WHERE IsAdmin = 1 AND Id =" . $Id)->fetchAll();
		return count($data) > 0;
	}

	public function SetDepartment($Id, $department)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$stmt = $pdo->prepare("UPDATE Users SET DepartmentId = ? WHERE Id = ?");
		$stmt->execute([$department, $Id]);
		return true;
	}

	public function GetAllUsersWithDepartmentOrderedByLastFirstName($departmentId)
	{
		$dbConn = new DbConn();
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("
			SELECT 
				users.LastName as lName,
				users.FirstName as fName,
				users.Id as userId
			FROM
				`Users` as users
			WHERE
				users.DepartmentId = " . $departmentId . "
			ORDER BY lName, fName")->fetchAll();
		return $data;
	}

	public function GetAllUsersOrderedByLastFirstName()
	{
		$dbConn = new DbConn();
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("
			SELECT 
				users.LastName as lName,
				users.FirstName as fName,
				users.Id as userId
			FROM
				`Users` as users
			ORDER BY lName, fName")->fetchAll();
		return $data;
	}

	public function SavePassword($Id, $Password)
	{
		try
		{
			$dbConn = new DbConn(); 
			$pdo = $dbConn->GetConnection();
			$stmt = $pdo->prepare("UPDATE Users SET Password = ? WHERE Id = ?");
			$stmt->execute([$Password, $Id]);
			return true;
		}
		catch(Exception $e)
		{
			return false;
		}
	}

	public function ValidateAndSetAccountPass($Id, $FirstName, $LastName, $NewPass)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT * FROM Users WHERE Id = " . $Id)->fetchAll();
		if ($data[0]['FirstName'] == $FirstName && 
			$data[0]['LastName'] == $LastName)
		{
			return $this->SavePassword($Id, password_hash($NewPass, PASSWORD_DEFAULT));
		}
		return false;
	}
}



?>