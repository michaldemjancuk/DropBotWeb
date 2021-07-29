<?php

include("config/dbConn.php");
include("config/settings.php");
include("config/Classes/users.php");
include("config/Classes/authenticator.php");
$auth = new Authenticator();

$users = new Users();
$auth->Required_User();

?>
<!doctype html>
<html lang="cs">
  <head>
    <title>DropBot</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Refresh" content="14400"> 
    <link rel="stylesheet" type="text/css" href="/css/custom.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="js/custom.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </head>

  <body class="bg-light">
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
      <a onclick="document.getElementById('iframeContent').src = '/help/index.html'" class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">DropBot</a>
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link btn btn-xs btn-warning rounded-pill text-dark" href="/actions/logout.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> Odhl&aacute;sit
          </a>
        </li>
      </ul>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky position-fixed">
            <ul class="nav flex-column">
              <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Dropbot
<?php 
if($auth->IsAdmin()) { 
  echo '[<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-award"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg> <i>Admin</i> ]';
  echo "<li class='nav-item btn-light'>
    <a class='nav-link' onclick='document.getElementById(&quot;iframeContent&quot;).src =&quot;xxxxxxxxxxxxx.php&quot;;'>
    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-award'><circle cx='12' cy='8' r='7'></circle><polyline points='8.21 13.89 7 23 12 20 17 23 15.79 13.88'></polyline></svg>
    Spravovat dropy
    </a>
    </li>"; 
    echo "<li class='nav-item btn-light'>
    <a class='nav-link' onclick='document.getElementById(&quot;iframeContent&quot;).src =&quot;xxxxxxxxxxxxx.php&quot;;'>
    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-file'><path d='M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z'></path><polyline points='13 2 13 9 20 9'></polyline></svg>
    Vytvořit drop
    </a>
    </li>";  
} else {
  echo "
    <li class='nav-item btn-light'>
    <a class='nav-link' onclick='document.getElementById(&quot;iframeContent&quot;).src =&quot;xxxxxxxxxxxxx.php&quot;;'>
    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-file'><path d='M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z'></path><polyline points='13 2 13 9 20 9'></polyline></svg>
    Upload image
    </a>
    </li>
    <li class='nav-item btn-light'>
    <a class='nav-link' onclick='document.getElementById(&quot;iframeContent&quot;).src =&quot;xxxxxxxxxxxxx.php&quot;;'>
    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-file'><path d='M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z'></path><polyline points='13 2 13 9 20 9'></polyline></svg>
    My drops
    </a>
    </li>";
} ?>
              </span>
              <!-- <a class="d-flex align-items-center text-muted" href="#">
                <span data-feather="plus-circle"></span>
              </a> -->
            </h6>
            <hr />
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
              <span>Nastaven&iacute;
<?php 
if($auth->IsAdmin()) { 
  echo '[<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-award"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>]';
  echo "<li class='nav-item btn-light'>
    <a class='nav-link' onclick='document.getElementById(&quot;iframeContent&quot;).src =&quot;users.php&quot;;'>
    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-user'><path d='M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2'/><circle cx='12' cy='7' r='4'/></svg>
    U&#382;ivatel&eacute;
    </a>
    </li>";
}
?>
                <li class="nav-item btn-light">
                  <a class="nav-link" onclick="document.getElementById('iframeContent').src ='userSettings.php';">
                      <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-settings'><circle cx='12' cy='12' r='3'/><path d='M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z'/></svg>
                    M&aacute; nastaven&iacute;
                  </a>
                </li>
              </span>
              <!-- <a class="d-flex align-items-center text-muted" href="#">
                <span data-feather="plus-circle"></span>
              </a> -->
            </h6>
            <hr />
<?php 
// if($auth->IsAdmin()) { 
//   echo '
//   <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
//   <span>P&#345;ehledy&nbsp;
//     <span class="text-dark">
//         [<span data-feather="award"></span>]
//     </span>
//   </span>
//   </h6>
//   <ul class="nav flex-column mb-2">
//   <li class="nav-item">
//     <a class="nav-link disabled" href="#">
//       <span data-feather="arrow-up-circle"></span>
//         V další verzi 
//     </a>
//   </li>
//   </ul>  
//   </div>
//   </nav>'; 
//} ?>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
          <div class="ratio ratio-16x9">
            <iframe id="iframeContent" class="embed-responsive-item col-md-12" src="/help/index.html" allowfullscreen></iframe>
          </div>
        </main>
      </div>
    </div>
    <div style="margin-left: 10px; bottom: 0; position: absolute;">
      <i>
        <!-- <button class="btn btn-danger btn-sm">
          <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-frown'><circle cx='12' cy='12' r='10'/><path d='M16 16s-1.5-2-4-2-4 2-4 2'/><line x1='9' y1='9' x2='9.01' y2='9'/><line x1='15' y1='9' x2='15.01' y2='9'/></svg>
          Nahlásit chybu
        </button><br> -->
        <small>
          Verze: <?php echo IndexSettings()['Version']; ?><br>
          DEV: Michal Demjan&#269;uk<br>
        </small>
      </i>
    </div>
  </body>
</html>
