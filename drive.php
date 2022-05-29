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

    if(!isset($_SESSION['z8user'])){
        header('Location: index.php');
    }
    if(isset($_SESSION['z8error'])){
        if($_SESSION['z8error'] == 3){
            $iduser = $_SESSION['z8user'];
            $lastLogs = "SELECT * FROM logi WHERE idu = $iduser ORDER BY datetime DESC LIMIT 2;";
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Głośnicki</title>
    <style>
        .error{
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <a href="logout.php">Logout</a>
</body>
</html>