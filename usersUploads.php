<?php

include("config/dbConn.php");
include("config/settings.php");
include("config/Classes/users.php");
include("config/Classes/authenticator.php");
include("config/Classes/dropImageUploads.php");

$auth = new Authenticator();
$DIUpload = new DropImageUploads();

$auth->Required_User();
?>

<table>
    <tr>
        <td>GroupId</td>
        <td>User</td>
        <td>Count</td>
    </tr>
    <?php $DIUpload->WriteAllUserUploads() ?>
</table>