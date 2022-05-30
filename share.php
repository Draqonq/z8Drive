<?php
    session_start();
    $fromFile = $_POST['fromFile'];
    $file = basename($fromFile);
    $toUser = $_POST['toUser'];
    $userDir = "users/".$toUser;
    $toDir = $userDir."/shared";
    $toFile = $toDir."/".$file;

    if(!is_dir($toDir)){
        if(!(mkdir($toDir, 0777, true))){
            header('Location: drive.php');
        }
    }
    if(copy($fromFile, $toFile)){
        $_SESSION['z8error'] = 5;
    }

    header('Location: drive.php');
?>