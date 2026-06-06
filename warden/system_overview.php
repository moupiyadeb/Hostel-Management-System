<?php
require_once "../includes/warden_auth.php";
require_once "../config/db.php";

/* Total Students */
$total_students = $conn->query("
    SELECT COUNT(*) AS total
    FROM students
")->fetch_assoc()['total'];

/* Students IN */
$students_in = $conn->query("
    SELECT COUNT(*) AS total
    FROM student_status
    WHERE status='IN'
")->fetch_assoc()['total'];

/* Students OUT */
$students_out = $total_students - $students_in;

/* Occupied Rooms */
$occupied_rooms = $conn->query("
    SELECT COUNT(DISTINCT room_id) AS total
    FROM students
    WHERE room_id IS NOT NULL
")->fetch_assoc()['total'];

/* Pending Complaints */
$pending_complaints = $conn->query("
    SELECT COUNT(*) AS total
    FROM complaints
    WHERE LOWER(status)='pending'
")->fetch_assoc()['total'];

/* Resolved Complaints */
$resolved_complaints = $conn->query("
    SELECT COUNT(*) AS total
    FROM complaints
    WHERE LOWER(status)='resolved'
")->fetch_assoc()['total'];

/* Old Hostel Students */
$old_hostel_students = $conn->query("
    SELECT COUNT(*) AS total
    FROM students s
    JOIN rooms r ON s.room_id = r.id
    WHERE r.hostel_id = 1
")->fetch_assoc()['total'];

/* New Hostel Students */
$new_hostel_students = $conn->query("
    SELECT COUNT(*) AS total
    FROM students s
    JOIN rooms r ON s.room_id = r.id
    WHERE r.hostel_id = 2
")->fetch_assoc()['total'];

/* Recent Attendance */
$recent_attendance = $conn->query("
    SELECT
        s.user_id,
        s.name,
        sh.previous_status,
        sh.new_status,
        sh.scan_time
    FROM status_history sh
    JOIN students s ON sh.student_id = s.id
    ORDER BY sh.id DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>System Overview</title>

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

        .container{
            width:90%;
            margin:30px auto;
        }

        .cards{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
            gap:20px;
            margin-bottom:30px;
        }

        .card{
            background:white;
            padding:20px;
            border-radius:12px;
            text-align:center;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
        }

        .card h2{
            margin:0;
            color:#F7B5CA;
        }

        .card p{
            font-size:28px;
            font-weight:bold;
            margin:10px 0 0;
        }

        .section{
            background:white;
            padding:20px;
            border-radius:12px;
            margin-bottom:25px;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th,td{
            border:1px solid #E8D9C8;
            padding:10px;
            text-align:center;
        }

        th{
            background:#F7B5CA;
        }

        .btn{
            display:inline-block;
            background:#F7B5CA;
            color:#333;
            text-decoration:none;
            padding:10px 16px;
            border-radius:8px;
            font-weight:bold;
        }
    </style>
</head>

<body>

<div class="header">
    <h1>System Overview</h1>
</div>

<div class="container">

    <div class="cards">

        <div class="card">
            <h2>Total Students</h2>
            <p><?php echo $total_students; ?></p>
        </div>

        <div class="card">
            <h2>Students IN</h2>
            <p><?php echo $students_in; ?></p>
        </div>

        <div class="card">
            <h2>Students OUT</h2>
            <p><?php echo $students_out; ?></p>
        </div>

        <div class="card">
            <h2>Occupied Rooms</h2>
            <p><?php echo $occupied_rooms; ?></p>
        </div>

        <div class="card">
            <h2>Pending Complaints</h2>
            <p><?php echo $pending_complaints; ?></p>
        </div>

        <div class="card">
            <h2>Resolved Complaints</h2>
            <p><?php echo $resolved_complaints; ?></p>
        </div>

    </div>

    <div class="section">
        <h2>Hostel Distribution</h2>

        <table>
            <tr>
                <th>Hostel</th>
                <th>Students</th>
            </tr>

            <tr>
                <td>Old Hostel</td>
                <td><?php echo $old_hostel_students; ?></td>
            </tr>

            <tr>
                <td>New Hostel</td>
                <td><?php echo $new_hostel_students; ?></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Recent Attendance Activity</h2>

        <table>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Previous</th>
                <th>New</th>
                <th>Time</th>
            </tr>

            <?php while($row = $recent_attendance->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['previous_status']); ?></td>
                <td><?php echo htmlspecialchars($row['new_status']); ?></td>
                <td><?php echo htmlspecialchars($row['scan_time']); ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>

    <a href="dashboard.php" class="btn">Back</a>

</div>

</body>
</html>