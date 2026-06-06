<?php

$host = "localhost";
$username = "root";
$password = "password";
$database = "hostel_management";
$port = 3306;

$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Optional: Set charset
$conn->set_charset("utf8");

?>