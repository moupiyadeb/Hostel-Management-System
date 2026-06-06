<?php

session_start();

if(
    !isset($_SESSION['role'])
    ||
    $_SESSION['role'] != 'student'
)
{
    header("Location: ../index.php");
    exit();
}
?>