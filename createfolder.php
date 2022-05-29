<?php

    if(isset($_POST['directory']) && isset($_POST['folderName'])){
        $dir = $_POST['directory'];
        $name = $_POST['folderName'];
        $directoryPath = $dir."/".$name;
        if(mkdir($directoryPath, 0777, true)){
            //Poprawnie
        }
    }
    header('Location: drive.php');

?>