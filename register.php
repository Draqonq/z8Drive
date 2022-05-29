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

    $login = $_POST['login'];
    $password = $_POST['password'];
    $rpassword = $_POST['rpassword'];

    if($password == $rpassword){
        $sql = "INSERT INTO users (login, password) VALUES ('$login', '$password');";
        $result = mysqli_query($polaczenie, $sql);
    }

    mysqli_close($polaczenie);
    header('Location: index.php');
?>