<?php
require_once "../includes/warden_auth.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Warden Dashboard</title>

    <style>

        body{
            margin:0;
            padding:0;
            background:#FFEBD4;
            font-family:Arial, sans-serif;
        }

        .header{
            background:#F7B5CA;
            padding:20px;
            text-align:center;
        }

        .header h1{
            margin:0;
            color:#333;
        }

        .container{
            width:80%;
            margin:40px auto;
        }

        .card{
            background:white;
            padding:20px;
            margin-bottom:20px;
            border-radius:12px;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
            text-align:center;
        }

        .btn{
            display:inline-block;
            width:250px;
            padding:15px;
            margin:10px;
            background:#F7B5CA;
            color:#333;
            text-decoration:none;
            border-radius:8px;
            font-weight:bold;
        }

        .btn:hover{
            opacity:0.9;
        }

    </style>

</head>

<body>

<div class="header">
    <h1>Warden Dashboard</h1>
</div>

<div class="container">

    <div class="card">
        <a class="btn" href="check_rooms.php">
            Check Rooms
        </a>

        <a class="btn" href="manage_students.php">
            Manage Students
        </a>

        <a class="btn" href="view_complaints.php">
            View Complaints
        </a>

        <a class="btn" href="../logout.php">
            Logout
        </a>

        <a class="btn" href="scan_qr.php">
            QR Scanner
        </a>

        <a class="btn" href="attendance_history.php">
            Attendance History
        </a>

        <a class="btn" href="system_overview.php">
            System Overview
        </a>
    </div>

</div>

</body>
</html>