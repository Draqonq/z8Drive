<?php
    session_start();
    require("../database8.php");
    $polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
    if (!$polaczenie) {
        echo "Błąd połączenia z MySQL." . PHP_EOL;
        echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    $polaczenie->query("SET NAMES 'utf8'");
    $login = null;

    if(!isset($_SESSION['z8user'])){
        header('Location: index.php');
    }
    $iduser = $_SESSION['z8user'];
    $getUser = "SELECT * FROM users WHERE idu = '$iduser' LIMIT 1;";
    $resultUser = mysqli_query($polaczenie, $getUser) or die ("SQL error : $dbname");
    $row = $resultUser->fetch_row();
    $fileView = "icon";
    if($resultUser->num_rows > 0){
        $login = $row[1];
    }
    if(isset($_SESSION['z8error'])){
        if($_SESSION['z8error'] == 3){
            $lastLogs = "SELECT * FROM logi WHERE idu = '$iduser' ORDER BY datetime DESC LIMIT 2;";
            $resultLogs = mysqli_query($polaczenie, $lastLogs) or die ("SQL error 2: $dbname");
            $date = null;
            while ($rowLogs = mysqli_fetch_array ($resultLogs)){
                if($rowLogs[5] == 1){
                    $date = $rowLogs[2];
                }
            }
            echo "<p class='error'>Nastąpiło trzykrotne błędne logowanie, konto zostało zablokowane $date </p>";
        }
        unset($_SESSION['z8error']);
    }
    $directoryPath = 'users/'.$login;

    if(isset($_GET['dirName'])){
        $getDirectory = $_GET['dirName'];
        $directoryPath = $getDirectory;
    }

    if(isset($_GET['backDir'])){
        $getDirectory = $_GET['backDir'];
        $directoryPath = dirname($getDirectory);
    }

    if(isset($_SESSION['fileView'])){
        $fileView = $_SESSION['fileView'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Głośnicki</title>
    <style>
        *{
            box-sizing: border-box;
        }
        body{
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .error{
            color: red;
            font-weight: bold;
        }
        form{
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .folderName{
            height: 25px;
        }
        button, .file{
            width: 50px;
            height: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-size: 10px;
            border: none;
            background-color: white;
            cursor: pointer;
        }
        img{
            width: 35px;
        }
    </style>
</head>
<body>
    <a href="logout.php"><h2>Logout</h2></a>
    <form action="fileview.php" method="post">
        Widok plików:
        <button name="fileView" value="normal">Nazwy plików</button>
        <button name="fileView" value="icon">Ikony</button>
    </form>
    <form action="createfolder.php" method="post">
        Directory name: <input class="folderName" type="text" name="folderName">
        <button name="directory" value="<?php echo $directoryPath ?>">
            <img src="createFolder.png" alt="create folder">
            Create folder
        </button>
    </form>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="fileToUpload">
        <button name="directory" value="<?php echo $directoryPath ?>">
            <img src="upload.png" alt="create folder">
            Upload file
        </button>
    </form>
    <form action="#" method="get">
        <?php
            $userDirectory = 'users/'.$login;
            if($directoryPath != $userDirectory){
                echo "<button name='backDir' value='$directoryPath'><img src='backdir.jpg' alt='Powrót'>Wróć</button>";
            }
            $files = scandir($directoryPath);
            foreach($files as $file){
                if($file === '.' || $file === '..') {continue;}
                else{
                    if(is_file("$directoryPath/$file")){
                        if($fileView == "icon"){
                            $pathinfo = pathinfo("$directoryPath/$file");
                            $extension = $pathinfo['extension'];
                            if($extension == "png" || $extension == "jpg" || $extension == "jpeg" || $extension == "gif"){
                                echo "<div class='file'><a href='$directoryPath/$file'><img src='$directoryPath/$file' alt='$file'></a>$file</div>";
                            }
                            else if($extension == "mp4" || $extension == "avi"){
                                echo "<div class='file'><a href='$directoryPath/$file'><img src='video.png' alt='$file'></a>$file</div>";
                            }
                            else if($extension == "mp3" || $extension == "wav"){
                                echo "<div class='file'><a href='$directoryPath/$file'><img src='audio.png' alt='$file'></a>$file</div>";
                            }
                            else{
                                echo "<div class='file'><a href='$directoryPath/$file'><img src='file.png' alt='$file'></a>$file</div>";
                            }
                        }
                        else{
                            echo "<a href='$directoryPath/$file'>$file</a>";
                        }
                    }
                    else if(is_dir("$directoryPath/$file")){
                        if($fileView == "icon"){
                            echo "<button name='dirName' value='$directoryPath/$file'><img src='folder.png' alt='$file'>$file</button>";
                        }
                        else{
                            echo "<button name='dirName' value='$directoryPath/$file'>$file</button>";
                        }
                    }
                }
            }
        ?>
    </form>
</body>
</html>