<?php
    $userid = $_POST["Input_user"];
    $ChangeNick = $_POST["ChangeNick"];
    
    $con = mysqli_connect("", "", "", "");
    if(!$con)
        die("Could not Connect".mysqli_connect_error());

    $check = mysqli_query($con, "SELECT * FROM UserInfo WHERE  `UserId` = '". $userid."' ");
    $numrows = mysqli_num_rows($check);
    if ($numrows == 0)
        die("ID does not exist. \n");

    $nickcheck = mysqli_query($con, "SELECT * FROM UserInfo WHERE `UserNick` = '".$ChangeNick."' ");
    $nicknumrows = mysqli_num_rows($nickcheck);
    if($nicknumrows != 0)
        die("Duplicate nicknames. \n");
    
    if ($row = mysqli_fetch_assoc($check))
    {
        mysqli_query($con, "UPDATE UserInfo SET `UserNick` = '".$ChangeNick."' WHERE `UserId`= '".$userid."' ");    
        echo ("ChangeNickSuccess");
    }        
    
    mysqli_close($con);
?>