<?php
session_start();

if(isset($_SESSION['role']))
{
    if($_SESSION['role'] == 'warden')
    {
        header("Location: warden/dashboard.php");
        exit();
    }

    if($_SESSION['role'] == 'student')
    {
        header("Location: student/dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>BVEC Hostel Management</title>

    <style>

        body{
            margin:0;
            padding:0;
            background:#FFEBD4;
            font-family:Arial, sans-serif;
        }

        .header{
            background:#F7B5CA;
            text-align:center;
            padding:25px;
        }

        .header h1{
            margin:0;
            color:#333;
        }

        .logo{
            width:100px;
            margin-top:10px;
        }

        .login-box{
            width:350px;
            margin:50px auto;
            background:white;
            padding:25px;
            border-radius:10px;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
        }

        input{
            width:100%;
            padding:10px;
            margin-top:10px;
            margin-bottom:15px;
            box-sizing:border-box;
        }

        button{
            width:100%;
            padding:10px;
            border:none;
            background:#F7B5CA;
            cursor:pointer;
            font-size:16px;
        }

        .error{
            color:red;
            text-align:center;
        }

    </style>

</head>

<body>

<div class="header">

    <h1>BARAK VALLEY ENGINEERING COLLEGE</h1>

    <!-- Replace later with actual logo -->
    <img src="assets/logo.jpeg" class="logo">

</div>

<div class="login-box">

    <form action="authenticate.php" method="POST">

        <label>ID</label>
        <input type="text" name="user_id" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">
            Login
        </button>

    </form>

</div>

</body>
</html>