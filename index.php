<?php
    session_start();
    if(isset($_SESSION['z8error'])){
        if($_SESSION['z8error'] == 1){
            echo "Blokada na to konto trwa minutę!";
        }
        else if($_SESSION['z8error'] == 2){
            echo "Na konto została nałożona blokada!";
        }
        else if($_SESSION['z8error'] == 4){
            echo "Ta nazwa uzytkownika jest zajęta";
        }
        unset($_SESSION['z8error']);
    }
    if(isset($_SESSION['z8user'])){
        header('Location: drive.php');
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
        body{
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #89A;
        }
        header{
            display: flex;
            justify-content: center;
        }
        form{
            display: flex;
            flex-direction: column;
            gap: 5px;
            padding: 50px;
            justify-content: center;
            align-items: center;
            background-color: #CCD;
            border: 2px solid black;
        }
        form:nth-child(1){
            border-right: 0;
        }
    </style>
</head>
<body>
    <header>
        <form action="login.php" method="post">
            <h2>Logowanie</h2>
            Login:
            <input type="text" name="login">
            Hasło:
            <input type="password" name="password">
            <input type="submit" value="Zaloguj">
        </form>
        <form action="register.php" method="post">
            <h2>Rejestracja</h2>
            Login:
            <input type="text" name="login">
            Hasło:
            <input type="password" name="password">
            Powtórz hasło:
            <input type="password" name="rpassword">
            <input type="submit" value="Zarejestruj">
        </form>
    </header>
</body>
</html>