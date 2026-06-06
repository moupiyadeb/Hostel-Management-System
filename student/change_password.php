<?php
require_once "../includes/student_auth.php";
require_once "../config/db.php";

$user_id = $_SESSION['user_id'];

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    $stmt = $conn->prepare("
        SELECT password
        FROM users
        WHERE user_id = ?
    ");

    $stmt->bind_param("s", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user['password'] != $current_password) {

        $message = "Current password is incorrect.";

    } elseif ($new_password != $confirm_password) {

        $message = "New passwords do not match.";

    } elseif (strlen($new_password) < 6) {

        $message = "Password must be at least 6 characters.";

    } else {

        $update = $conn->prepare("
            UPDATE users
            SET password = ?
            WHERE user_id = ?
        ");

        $update->bind_param(
            "ss",
            $new_password,
            $user_id
        );

        if ($update->execute()) {

            $message = "Password changed successfully.";

        } else {

            $message = "Failed to update password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>

    <style>
        body{
            margin:0;
            font-family:Arial,sans-serif;
            background:#FFEBD4;
        }

        .header{
            background:#F7B5CA;
            padding:20px;
            text-align:center;
        }

        .box{
            width:450px;
            margin:40px auto;
            background:white;
            padding:25px;
            border-radius:12px;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
        }

        input{
            width:100%;
            padding:10px;
            margin:10px 0 15px;
            box-sizing:border-box;
        }

        button,.btn{
            background:#F7B5CA;
            border:none;
            padding:10px 16px;
            border-radius:8px;
            text-decoration:none;
            color:#333;
            font-weight:bold;
            cursor:pointer;
        }

        .message{
            text-align:center;
            font-weight:bold;
            margin-bottom:15px;
        }
    </style>
</head>

<body>

<div class="header">
    <h1>Change Password</h1>
</div>

<div class="box">

    <?php if($message != "") { ?>
        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php } ?>

    <form method="POST">

        <label>Current Password</label>
        <input
            type="password"
            name="current_password"
            required
        >

        <label>New Password</label>
        <input
            type="password"
            name="new_password"
            required
        >

        <label>Confirm New Password</label>
        <input
            type="password"
            name="confirm_password"
            required
        >

        <button type="submit">
            Change Password
        </button>

        <a
            href="dashboard.php"
            class="btn">
            Back
        </a>

    </form>

</div>

</body>
</html>