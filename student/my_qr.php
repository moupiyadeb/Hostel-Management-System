<?php
require_once "../includes/student_auth.php";
require_once "../config/db.php";

$user_id = $_SESSION['user_id'];

$sql = "
    SELECT 
        s.user_id,
        s.name,
        h.hostel_name,
        r.floor_no,
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
    die("Student not found.");
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
    <title>My QR</title>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>

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

        .qr-box{
            width:420px;
            margin:40px auto;
            background:white;
            padding:25px;
            border-radius:12px;
            text-align:center;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
        }

        #qrcode{
            display:flex;
            justify-content:center;
            margin:25px 0;
        }

        .info{
            line-height:1.8;
            color:#333;
        }

        .btn{
            display:inline-block;
            margin-top:20px;
            background:#F7B5CA;
            color:#333;
            text-decoration:none;
            padding:10px 18px;
            border-radius:8px;
            font-weight:bold;
        }
    </style>
</head>

<body>

<div class="header">
    <h1>My QR Code</h1>
</div>

<div class="qr-box">

    <h2><?php echo htmlspecialchars($student['name']); ?></h2>

    <div class="info">
        <strong>Student ID:</strong>
        <?php echo htmlspecialchars($student['user_id']); ?>
        <br>

        <strong>Hostel:</strong>
        <?php echo htmlspecialchars($student['hostel_name'] ?? 'Not Allocated'); ?>
        <br>

        <strong>Floor:</strong>
        <?php echo floorName($student['floor_no']); ?>
        <br>

        <strong>Room:</strong>
        <?php echo htmlspecialchars($student['room_number'] ?? 'Not Allocated'); ?>
    </div>

    <div id="qrcode"></div>

    <a href="dashboard.php" class="btn">Back</a>

</div>

<script>
    var studentId = "<?php echo htmlspecialchars($student['user_id']); ?>";

    new QRCode(document.getElementById("qrcode"), {
        text: studentId,
        width: 220,
        height: 220
    });
</script>

</body>
</html>