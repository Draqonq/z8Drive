<?php
    session_start();
    unset($_SESSION['z8error']);
    require("../database8.php");
    $polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
    if (!$polaczenie) {
        echo "Błąd połączenia z MySQL." . PHP_EOL;
        echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    $polaczenie->query("SET NAMES 'utf8'");

    $login = $_POST['login'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE login = '$login' LIMIT 1;";
    $result = mysqli_query($polaczenie, $sql) or die ("SQL error 2: $dbname");
    $row = $result->fetch_row();
    if($result->num_rows > 0){
        $iduser = $row[0];
        $pass = $row[2];
        $ip = $_SERVER["REMOTE_ADDR"];
        //Last login
        
        $lastLogs = "SELECT * FROM logi WHERE idu = $iduser ORDER BY datetime DESC LIMIT 1;";
        $resultLogs = mysqli_query($polaczenie, $lastLogs) or die ("SQL error 2: $dbname");
        $rowLogs = $resultLogs->fetch_row();
        if($resultLogs->num_rows > 0){
            $date = $rowLogs[2];
            $newDate = date('Y-m-d H:i:s', strtotime(' -1 minutes'));
            if($rowLogs[5] == 1 && $date > $newDate){
                $_SESSION['z8error'] = 1;
                header('Location: index.php');
                exit();
            }
        }
        if($password == $pass){
            //Poprawne logowanie
            $lastLogs = "SELECT * FROM logi WHERE idu = $iduser ORDER BY datetime DESC LIMIT 1;";
            $resultLogs = mysqli_query($polaczenie, $lastLogs) or die ("SQL error 2: $dbname");
            $rowLogs = $resultLogs->fetch_row();
            if($resultLogs->num_rows > 0){
                $isBlockade = $rowLogs[5];
                if($isBlockade){
                    $_SESSION['z8error'] = 3;
                }
            }

            $logs = "INSERT INTO logi (idu, ip_address, correct) VALUES ('$iduser', '$ip', 1);";
            $sendlogs = mysqli_query($polaczenie, $logs) or die ("SQL error 2: $dbname");
            
            $_SESSION['z8user'] = $iduser;
            header('Location: drive.php');
        }
        else{
            //Błędne logowanie
            $lastLogs = "SELECT * FROM logi WHERE idu = $iduser ORDER BY datetime DESC LIMIT 3;";
            $resultLogs = mysqli_query($polaczenie, $lastLogs) or die ("SQL error 2: $dbname");
            $blockadeIter = 0;
            while ($rowLogs = mysqli_fetch_array ($resultLogs)){
                if($rowLogs[5] == 1 || $rowLogs[4] == 1){
                    break;
                }
                $blockadeIter++;
            }
            echo $blockadeIter;
            $logs = null;
            if($blockadeIter >= 2){
                //Blokada
                $logs = "INSERT INTO logi (idu, ip_address, correct, blockade) VALUES ('$iduser', '$ip', 0, 1);";
                $logs_blockade = "INSERT INTO logi_blockade (idu, ip_address) VALUES ('$iduser', '$ip');";
                $sendlogs_blockade = mysqli_query($polaczenie, $logs_blockade) or die ("SQL error 2: $dbname");
                $_SESSION['z8error'] = 2;
            }
            else{
                //Błąd logowania
                $logs = "INSERT INTO logi (idu, ip_address, correct, blockade) VALUES ('$iduser', '$ip', 0, 0);";
            }
            $sendlogs = mysqli_query($polaczenie, $logs) or die ("SQL error 2: $dbname");
            header('Location: index.php');
        }
    }
    else{
        header('Location: index.php');
    }
?>