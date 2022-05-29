<?php
    session_start();
    $_SESSION['fileView'] = $_POST['fileView'];
    header('Location: drive.php');
?>