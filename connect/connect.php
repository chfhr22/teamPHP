<?php
    $host = "localhost";
    $user = "chfhrdk1";
    $pw = "add940922!";
    $db = "chfhrdk1";

    $connect = new mysqli($host, $user, $pw, $db);
    $connect -> set_charset("utf-8");

    // if(mysqli_connect_errno()){
    //     echo "DATABASE Connect False";
    // } else {
    //     echo "DATABASE Connect True";
    // }
?>