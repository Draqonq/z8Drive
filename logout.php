<?php
    session_start();
    unset($_SESSION['z8user']);
    header('Location: index.php');
?>