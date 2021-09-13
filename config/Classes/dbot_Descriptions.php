<?php

/**
	>>> DEPENDENCIES <<<

	include("config/dbConn.php");
 */
class Dbot_Descriptions
{
	
	function __construct()
	{
		# code...
	}

	public function Add($PreUsernameText, $EmojiUsing, $Free)
	{
		$dbConn = new DbConn();
		$sql = "INSERT INTO dbot_descriptions (PreUsernameText, EmojiUsing, Free) VALUES (?, ?, ?)";
		$pdo = $dbConn->GetConnection();
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$PreUsernameText, $EmojiUsing, $Free]);
	}

   public function GetLastUsedDescription($free = 1)
   {
      $dbConn = new DbConn(); 
      $pdo = $dbConn->GetConnection();
      $data = $pdo->query("SELECT * FROM dbot_descriptions WHERE Free = " . $free . " ORDER BY LastUsed ASC")->fetchAll();
      return $data[0];
   }

   public function GetAll()
   {
      $dbConn = new DbConn(); 
      $pdo = $dbConn->GetConnection();
      $data = $pdo->query("SELECT * FROM dbot_descriptions ORDER BY Id")->fetchAll();
      return $data;
   }

   public function UpdateLastUsedDate($Id)
   {
      $dbConn = new DbConn();
      $sql = "UPDATE dbot_descriptions SET LastUsed = NOW() WHERE Id = ?";
      $pdo = $dbConn->GetConnection();
      $stmt= $pdo->prepare($sql);
      $stmt->execute([$Id]);
   }

	public function Delete($Id)
	{
		$dbConn = new DbConn();
		$pdo = $dbConn->GetConnection();
		$sql = "DELETE FROM dbot_descriptions Where Id = ?";
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$Id]);
	}
}



?>