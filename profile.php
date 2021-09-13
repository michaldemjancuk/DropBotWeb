<?php

include("config/dbConn.php");
include("config/settings.php");
include("config/Classes/users.php");
include("config/Classes/posts.php");
include("config/Classes/authenticator.php");
include("config/Classes/dropImageUploads.php");

$auth = new Authenticator();
$users = new Users();
$posts = new Posts();
$dbot_diu = new DropImageUploads();
$usernameToLoad = (isset($_GET['id'])) ? 
   $_GET['id'] : 
   $auth->GetUserId();

$auth->Required_User();
$userData = $users->GetUserData($usernameToLoad);
$postsData = $posts->GetByUsername($usernameToLoad);
$imgUploads = $dbot_diu->GetByUsername($usernameToLoad);

?>

<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="/css/profileAndPosts.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div id="content" class="content content-full-width">
            <!-- begin profile -->
            <div class="profile">
               <div class="profile-header">
                  <!-- BEGIN profile-header-cover -->
                  <div class="profile-header-cover"></div>
                  <!-- END profile-header-cover -->
                  <!-- BEGIN profile-header-content -->
                  <div class="profile-header-content">
                     <!-- BEGIN profile-header-img -->
                     <div class="profile-header-img">
                        <img src="<?php echo $userData['ProfileImageUrl'] ?>" alt="">
                     </div>
                     <!-- END profile-header-img -->
                     <!-- BEGIN profile-header-info -->
                     <div class="profile-header-info">
                        <h4 class="m-t-10 m-b-5"><?php echo $userData['Username'] ?></h4>
                        <span class="badge rounded-pill bg-info text-dark"><a href="<?php echo $userData['PageUrl'] ?>"><?php echo $userData['PageUrl'] ?></a></span>
                        <a href="#" class="btn btn-sm btn-info mb-2">Edit Profile</a>
                     </div>
                     <!-- END profile-header-info -->
                  </div>
                  <!-- END profile-header-content -->
                  <!-- BEGIN profile-header-tab -->

                  <ul class="profile-header-tab nav nav-tabs" id="myTab" role="tablist">
                     <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="posts-tab" data-bs-toggle="tab" data-bs-target="#posts" type="button" role="tab" aria-controls="posts" aria-selected="true">Posts</button>
                     </li>
                     <li class="nav-item" role="presentation">
                        <button class="nav-link" id="photos-tab" data-bs-toggle="tab" data-bs-target="#photos" type="button" role="tab" aria-controls="photos" aria-selected="false">Photos</button>
                     </li>
                     <li class="nav-item" role="presentation">
                        <button class="nav-link" id="bio-tab" data-bs-toggle="tab" data-bs-target="#bio" type="button" role="tab" aria-controls="bio" aria-selected="false">Bio</button>
                     </li>
                  </ul>
                  <!-- END profile-header-tab -->
               </div>
            </div>
            <!-- end profile -->
            <!-- begin profile-content -->
            <div class="profile-content">
               <!-- begin tab-content -->

               <div class="tab-content p-0" id="myTabContent">
                  <div class="tab-pane fade show active" id="posts" role="tabpanel" aria-labelledby="posts-tab">
                     <!-- begin timeline -->
                     <ul class="timeline">
                        <li>
                           <!-- end timeline-icon -->
                           <!-- begin timeline-body -->
                           <div class="timeline-body">
                              <form method="post" action="actions/addPost.php">
                                 <div class="timeline-header">
                                    <i><b>
                                       Create new post
                                    </b></i>
                                 </div>
                                 <div class="timeline-content">
                                    <p>
                                       <input 
                                          id="PostName" 
                                          name="PostName" 
                                          type="text" 
                                          placeholder="Enter your post name..." 
                                          class="form-control rounded-corner" 
                                          maxlength="125" 
                                          minlength="3" 
                                          required
                                       >
                                    </p>
                                 </div>
                                 <div class="timeline-content">
                                    <p>
                                       <textarea
                                          id="PostText" 
                                          name="PostText" 
                                          type="text" 
                                          placeholder="What do you want to tell to the world? 👀" 
                                          class="form-control rounded-corner" 
                                          maxlength="125" 
                                          minlength="3" 
                                          required
                                       ></textarea>
                                    </p>
                                 </div>
                                 <div class="timeline-likes">
                                    <div class="stats-right">
                                    </div>
                                 </div>
                                 <div class="timeline-footer">
                                    <div class="form-check">
                                       <input name="PostponePost" class="form-check-input" type="checkbox" id="PostPlanCheckbox" disabled>
                                       <label class="form-check-label" for="PostPlanCheckbox">
                                          <s>Plan your post</s> <i>(Comming soon...)</i>
                                       </label>
                                    </div>
                                    <div id="PostponePost">
                                       <input 
                                          id="PostDate" 
                                          name="PostDate" 
                                          type="date" 
                                          class="form-control-sm rounded-corner stats-text"
                                       ><input 
                                          id="PostTime" 
                                          name="PostTime" 
                                          type="time" 
                                          class="form-control-sm rounded-corner stats-text"
                                       >
                                    </div>
                                 </div>
                                    <button
                                       id="NewPostSubmit"
                                       class="btn-sm btn-primary pull-right"
                                       onClick=""
                                       type="submit"
                                    >
                                       Submit your post
                                    </button>
                              </form>
                           </div>
                           <!-- end timeline-body -->
                        </li>

                           <?php 
                            for ($i = 0; $i < sizeof($postsData); ++$i) {
                              $posts->BuildPost(
                                 $postsData[$i]['Created'],
                                 $userData['ProfileImageUrl'],
                                 $postsData[$i]['Username'],
                                 $postsData[$i]['PostName'],
                                 $postsData[$i]['PostText'],
                                 $postsData[$i]['Views'] + 1
                              );
                              $posts->AddView($postsData[$i]['Id'], $postsData[$i]['Views'] + 1);
                           }
                           ?>
                     </ul>
                  </div>
                  <div class="tab-pane fade" id="photos" role="tabpanel" aria-labelledby="photos-tab">
                     <ul class="timeline">
                        <?php 
                         for ($i = 0; $i < sizeof($imgUploads); ++$i) {
                           $dbot_diu->BuildPost(
                              $imgUploads[$i]['Id'],
                              $imgUploads[$i]['Created'],
                              $userData['ProfileImageUrl'],
                              $usernameToLoad,
                              $imgUploads[$i]['LocalUrl'],
                              $imgUploads[$i]['LocalUrl']
                           );
                        }
                        ?>
                     </ul>
                  </div>
                  <div class="tab-pane fade" id="bio" role="tabpanel" aria-labelledby="bio-tab">
                     <ul class="timeline">
                     </ul>
                  </div>
               </div>
               <!-- end tab-content -->
            </div>
            <!-- end profile-content -->
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">

$("#PostponePost").hide(); // default => hidden postpone - send now

$("#PostPlanCheckbox").change(function() {
   if(this.checked) {
      $("#PostponePost").show();
   } else {
      $("#PostponePost").hide();
   }
});
</script>
<style type="text/css">
</style>