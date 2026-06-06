<?php

session_start();

require_once "config/db.php";

$user_id = trim($_POST['user_id']);
$password = trim($_POST['password']);

$sql = "SELECT * FROM users
        WHERE user_id = ?
        AND password = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ss",
    $user_id,
    $password
);

$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows == 1)
{
    $user = $result->fetch_assoc();

    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role'] = $user['role'];

    if($user['role'] == 'warden')
    {
        header("Location: warden/dashboard.php");
        exit();
    }

    if($user['role'] == 'student')
    {
        header("Location: student/dashboard.php");
        exit();
    }
}
else
{
    echo "Invalid ID or Password";
}
?>