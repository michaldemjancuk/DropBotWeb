<?php

include("config/dbConn.php");
include("config/settings.php");
include("config/Classes/users.php");
include("config/Classes/authenticator.php");
include("config/Classes/dbot_db.php");
include("config/Classes/dbot_ImgGen_Users.php");

$auth = new Authenticator();
$auth->Required_Admin();

$dbot_db = new Dbot_db();
$dbot_ImgGen_Users = new Dbot_ImgGen_Users();

$db_Data = $dbot_db->GetAll();
?>

<table style="border: 1px solid black;">
    <tr>
        <td style="border: 1px solid black;">Permission role</td>
        <td style="border: 1px solid black;">Created</td>
        <td style="border: 1px solid black;">Users used</td>
    </tr>
<?php
for ($db_data_i=0; $db_data_i < count($db_Data); $db_data_i++) { 
$usersData = $dbot_ImgGen_Users->GetAllWithDbotDbId($db_Data[$db_data_i]['Id']);
?>
    <tr>
        <td style="border: 1px solid black;"><?php echo $db_Data[$db_data_i]['PermissionRole']; ?></td>
        <td style="border: 1px solid black;"><?php echo $db_Data[$db_data_i]['Created']; ?></td>
        <td style="border: 1px solid black;">
<?php
for ($i=0; $i < count($usersData); $i++) { 
	echo $usersData[$i]["Username"] . " ("  . $usersData[$i]["CountOccur"] . ")<br>";
}
?>
        </td>
    </tr>
<?php
}
?>
</table>