<?php

/**
	>>> DEPENDENCIES <<<

	include("config/dbConn.php");
 */
class Posts
{
	
	function __construct()
	{
		# code...
	}

	public function Add($UserId, $Username, $PostName, $PostText, $PostUrlData, $PostPrice = 0)
	{
		$dbConn = new DbConn();
		$sql = "INSERT INTO posts (UserId, Username, PostName, PostText, PostUrlData, PostPrice) VALUES (?, ?, ?, ?, ?, ?)";
		$pdo = $dbConn->GetConnection();
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$UserId, $Username, $PostName, $PostText, $PostUrlData, $PostPrice]);
	}

	public function GetById($PostId)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT * FROM posts WHERE Id = '" . $PostId . "'")->fetchAll();
		return $data[0];
	}

	public function GetByUsername($Username, $descend = true)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		if(!$descend)
			$data = $pdo->query("SELECT * FROM posts WHERE Username = '" . $Username . "'")->fetchAll();
		else
			$data = $pdo->query("SELECT * FROM posts WHERE Username = '" . $Username . "' ORDER BY `posts`.`Created` DESC")->fetchAll();
		return $data;
	}

	public function Delete($Id)
	{
		$dbConn = new DbConn();
		$pdo = $dbConn->GetConnection();
		$sql = "DELETE FROM posts Where Id = ?";
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$Id]);
	}

	public function AddView($Id, $Views)
	{
		$dbConn = new DbConn();
		$pdo = $dbConn->GetConnection();
		$sql = "UPDATE posts SET Views = ? Where Id = ?";
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$Views, $Id]);
	}

	public function BuildPost($Created, $myProfileImageUrl, $Username, $PostName, $PostText, $Views)
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
         <span class="pull-right text-muted"> ' . $Views . '	 Views</span>
      </div>
      <div class="timeline-content">
         <p>
            <h4><b>' . $PostName . '</b></h4>
         </p>
      </div>
      <div class="timeline-content">
         <p>
            ' . $PostText . '
         </p>
      </div>
      <div class="timeline-likes">
         <div class="stats-right">
            <span class="stats-text">xxx Shares</span>
            <span class="stats-text">xxx Comments</span>
         </div>
         <div class="stats">
            <span class="fa-stack fa-fw stats-icon">
            <i class="fa fa-circle fa-stack-2x text-primary"></i>
            <i class="fa fa-thumbs-up fa-stack-1x fa-inverse"></i>
            </span>
            <span class="stats-total">xxx</span>
         </div>
      </div>
      <div class="timeline-footer">
         <a href="javascript:;" class="m-r-15 text-inverse-lighter"><i class="fa fa-thumbs-up fa-fw fa-lg m-r-3"></i> Like</a>
         <a href="javascript:;" class="m-r-15 text-inverse-lighter"><i class="fa fa-comments fa-fw fa-lg m-r-3"></i> Comment</a> 
         <a href="javascript:;" class="m-r-15 text-inverse-lighter"><i class="fa fa-share fa-fw fa-lg m-r-3"></i> Share</a>
      </div>
      <div class="timeline-comment-box">
         <div class="user"><img src="' . $myProfileImageUrl . '"></div>
         <div class="input">
            <form action="">
               <div class="input-group">
                  <input type="text" class="form-control rounded-corner" placeholder="Write a comment...">
                  <span class="input-group-btn p-l-10">
                  <button class="btn btn-primary f-s-12 rounded-corner" type="button">Comment</button>
                  </span>
               </div>
            </form>
         </div>
      </div>
   </div>
   <!-- end timeline-body -->
</li>';
	}
}



?>