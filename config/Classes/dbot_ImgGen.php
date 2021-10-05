<?php

/**
	>>> DEPENDENCIES <<<

	include("config/dbConn.php");
 */
class Dbot_ImgGen
{
	
	function __construct()
	{
		# code...
	}

   public function Add($LocalUrl, $Description, $dbot_db_Id = 1)
   {
      $dbConn = new DbConn();
      $sql = "INSERT INTO dbot_imggen (LocalUrl, Description, dbot_db_Id) VALUES (?, ?, ?)";
      $pdo = $dbConn->GetConnection();
      $stmt= $pdo->prepare($sql);
      $stmt->execute([$LocalUrl, $Description, $dbot_db_Id]);
   }

	public function GetById($Id)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT * FROM dbot_imggen WHERE Id = '" . $Id . "'")->fetchAll();
		return $data[0];
	}

   public function GetLast()
   {
      $dbConn = new DbConn(); 
      $pdo = $dbConn->GetConnection();
      $data = $pdo->query("SELECT * FROM dbot_imggen ORDER BY `dbot_imggen`.`Id` DESC LIMIT 1")->fetchAll();
      return $data[0];
   }

   	public function GetAllDrops()
   	{
   		$dbConn = new dbConn();
   		$pdo = $dbConn->GetConnection();
   		$data = $pdo->query("SELECT * FROM dbot_imggen ORDER BY `dbot_imggen`.`Created` DESC LIMIT 50")->fetchAll();
   		return $data;
   	}

	public function Delete($Id)
	{
		$dbConn = new DbConn();
		$pdo = $dbConn->GetConnection();
		$sql = "DELETE FROM dbot_imggen Where Id = ?";
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$Id]);
	}

   public function BuildPost($Id, $Created, $ImageUrl, $Description)
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
      <div class="timeline-content">
         <img src="' . $ImageUrl . '">
      </div>
      <div class="timeline-content">
         <b><i>' . $Description . '</b></i>
      </div>
      <div class="timeline-likes">
         <div class="stats-right">
            <span class="stats-text">
               <a href="actions/deleteDropImageUpload.php?imageId=' . $Id . '" class="btn btn-sm btn-danger disabled">Delete</a>
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