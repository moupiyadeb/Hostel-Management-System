<?php
require_once "../includes/student_auth.php";
require_once "../config/db.php";

$user_id = $_SESSION['user_id'];

$sql = "
    SELECT 
        s.user_id,
        s.name,
        s.phone,
        s.guardian_phone,
        s.email,
        h.hostel_name,
        r.floor_no,
        r.wing,
        r.room_number
    FROM students s
    LEFT JOIN rooms r ON s.room_id = r.id
    LEFT JOIN hostels h ON r.hostel_id = h.id
    WHERE s.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    die("Student profile not found.");
}

$student = $result->fetch_assoc();

function floorName($floor_no) {
    if ($floor_no === NULL) return "Not Allocated";
    if ($floor_no == 0) return "Ground Floor";
    if ($floor_no == 1) return "First Floor";
    if ($floor_no == 2) return "Second Floor";
    return "Floor " . $floor_no;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>

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

        .profile-box{
            width:520px;
            margin:40px auto;
            background:white;
            padding:25px;
            border-radius:12px;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
        }

        .row{
            display:flex;
            justify-content:space-between;
            border-bottom:1px solid #E8D9C8;
            padding:12px 0;
        }

        .label{
            font-weight:bold;
        }

        .btn{
            display:block;
            width:180px;
            text-align:center;
            margin:25px auto 0;
            background:#F7B5CA;
            color:#333;
            text-decoration:none;
            padding:10px;
            border-radius:8px;
            font-weight:bold;
        }
    </style>
</head>

<body>

<div class="header">
    <h1>My Profile</h1>
</div>

<div class="profile-box">

    <div class="row">
        <span class="label">Student ID</span>
        <span><?php echo htmlspecialchars($student['user_id']); ?></span>
    </div>

    <div class="row">
        <span class="label">Name</span>
        <span><?php echo htmlspecialchars($student['name']); ?></span>
    </div>

    <div class="row">
        <span class="label">Phone</span>
        <span><?php echo htmlspecialchars($student['phone']); ?></span>
    </div>

    <div class="row">
        <span class="label">Guardian Phone</span>
        <span><?php echo htmlspecialchars($student['guardian_phone']); ?></span>
    </div>

    <div class="row">
        <span class="label">Email</span>
        <span><?php echo htmlspecialchars($student['email']); ?></span>
    </div>

    <div class="row">
        <span class="label">Hostel</span>
        <span><?php echo htmlspecialchars($student['hostel_name'] ?? 'Not Allocated'); ?></span>
    </div>

    <div class="row">
        <span class="label">Floor</span>
        <span><?php echo floorName($student['floor_no']); ?></span>
    </div>

    <div class="row">
        <span class="label">Wing</span>
        <span><?php echo htmlspecialchars($student['wing'] ?? 'Not Allocated'); ?></span>
    </div>

    <div class="row">
        <span class="label">Room</span>
        <span><?php echo htmlspecialchars($student['room_number'] ?? 'Not Allocated'); ?></span>
    </div>

    <a href="dashboard.php" class="btn">Back</a>

</div>

</body>
</html>