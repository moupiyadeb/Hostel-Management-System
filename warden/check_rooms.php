<?php
require_once "../includes/warden_auth.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Check Rooms</title>

    <style>

        body{
            background:#FFEBD4;
            font-family:Arial,sans-serif;
            margin:0;
        }

        .header{
            background:#F7B5CA;
            text-align:center;
            padding:20px;
        }

        .container{
            width:80%;
            margin:40px auto;
            text-align:center;
        }

        .btn{
            display:inline-block;
            width:250px;
            padding:15px;
            margin:15px;
            background:white;
            color:#333;
            text-decoration:none;
            border-radius:10px;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
        }

    </style>
</head>

<body>

<div class="header">
    <h1>Check Rooms</h1>
</div>

<div class="container">

    <a class="btn" href="old_hostel.php">
        Old Hostel
    </a>

    <a class="btn" href="new_ground.php">
        New Hostel
    </a>

    <br><br>

    <a class="btn" href="dashboard.php">
        Back
    </a>

</div>

</body>
</html>