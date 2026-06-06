<?php

session_start();

if(
    !isset($_SESSION['role'])
    ||
    $_SESSION['role'] != 'warden'
)
{
    header("Location: ../index.php");
    exit();
}
?>