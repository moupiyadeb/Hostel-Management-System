<?php
require_once "../includes/student_auth.php";
require_once "../config/db.php";

$user_id = $_SESSION['user_id'];

$sql = "
    SELECT 
        s.name,
        h.hostel_name,
        r.room_number,
        ss.status
    FROM students s
    LEFT JOIN rooms r ON s.room_id = r.id
    LEFT JOIN hostels h ON r.hostel_id = h.id
    LEFT JOIN student_status ss ON ss.student_id = s.id
    WHERE s.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>

    <style>
        body{
            margin:0;
            font-family:Arial, sans-serif;
            background:#FFEBD4;
        }

        .header{
            background:#F7B5CA;
            padding:20px;
            text-align:center;
        }

        .container{
            width:80%;
            margin:40px auto;
            text-align:center;
        }

        .welcome-box{
            background:white;
            padding:20px;
            border-radius:12px;
            margin-bottom:25px;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
        }

        .card{
            background:white;
            padding:25px;
            border-radius:12px;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
        }

        .btn{
            display:inline-block;
            width:220px;
            padding:15px;
            margin:12px;
            background:#F7B5CA;
            color:#333;
            text-decoration:none;
            border-radius:8px;
            font-weight:bold;
        }

        .status-in{
            color:green;
            font-weight:bold;
        }

        .status-out{
            color:red;
            font-weight:bold;
        }
    </style>
</head>

<body>

<div class="header">
    <h1>Student Dashboard</h1>
</div>

<div class="container">

    <div class="welcome-box">
        <h2>
            Welcome, <?php echo htmlspecialchars($student['name']); ?>
        </h2>

        <p>
            <strong>Hostel:</strong>
            <?php echo htmlspecialchars($student['hostel_name'] ?? 'Not Allocated'); ?>
        </p>

        <p>
            <strong>Room:</strong>
            <?php echo htmlspecialchars($student['room_number'] ?? 'Not Allocated'); ?>
        </p>

        <p>
            <strong>Current Status:</strong>
            <?php 
                $status = $student['status'] ?? 'OUT';

                if($status == 'IN'){
                    echo '<span class="status-in">IN</span>';
                } else {
                    echo '<span class="status-out">OUT</span>';
                }
            ?>
        </p>
    </div>

    <div class="card">

        <a class="btn" href="profile.php">My Profile</a>

        <a class="btn" href="my_qr.php">My QR</a>

        <a class="btn" href="complaints.php">My Complaints</a>

        <a class="btn" href="change_password.php">Change Password</a>

        <a class="btn" href="../logout.php">Logout</a>

    </div>
</div>

</body>
</html>