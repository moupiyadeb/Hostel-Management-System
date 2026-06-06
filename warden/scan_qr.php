<?php
require_once "../includes/warden_auth.php";
require_once "../config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = trim($_POST['student_id']);

    if ($user_id == "") {
        $message = "Student ID is required.";
    } else {

        $stmt = $conn->prepare("SELECT id, user_id, name, room_id FROM students WHERE user_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $student_result = $stmt->get_result();

        if ($student_result->num_rows == 0) {
            $message = "Student not found.";
        } else {

            $student = $student_result->fetch_assoc();
            $student_db_id = $student['id'];

            if ($student['room_id'] == NULL) {
                $message = "Student is not allocated to any room.";
            } else {

                $status_stmt = $conn->prepare("SELECT status FROM student_status WHERE student_id = ?");
                $status_stmt->bind_param("i", $student_db_id);
                $status_stmt->execute();
                $status_result = $status_stmt->get_result();

                if ($status_result->num_rows == 0) {
                    $previous_status = "OUT";

                    $insert_status = $conn->prepare("INSERT INTO student_status (student_id, status) VALUES (?, 'OUT')");
                    $insert_status->bind_param("i", $student_db_id);
                    $insert_status->execute();
                } else {
                    $status_row = $status_result->fetch_assoc();
                    $previous_status = $status_row['status'];
                }

                $new_status = ($previous_status == "IN") ? "OUT" : "IN";

                $update = $conn->prepare("UPDATE student_status SET status = ? WHERE student_id = ?");
                $update->bind_param("si", $new_status, $student_db_id);
                $update->execute();

                $history = $conn->prepare("
                    INSERT INTO status_history (student_id, previous_status, new_status)
                    VALUES (?, ?, ?)
                ");
                $history->bind_param("iss", $student_db_id, $previous_status, $new_status);
                $history->execute();

                $message = $student['name'] . " (" . $student['user_id'] . ") status changed from " . $previous_status . " to " . $new_status . ".";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>QR Scanner</title>

    <script src="https://unpkg.com/html5-qrcode"></script>

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
            width:90%;
            max-width:650px;
            margin:35px auto;
            background:white;
            padding:25px;
            border-radius:12px;
            text-align:center;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
        }

        input{
            width:80%;
            padding:10px;
            margin:10px 0;
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
            margin:5px;
        }

        #reader{
            width:320px;
            margin:20px auto;
        }

        .message{
            font-weight:bold;
            margin-bottom:15px;
        }
    </style>
</head>

<body>

<div class="header">
    <h1>QR Scanner</h1>
</div>

<div class="container">

    <?php if($message != "") { ?>
        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php } ?>

    <h3>Manual Entry</h3>

    <form method="POST" id="manualForm">
        <input 
            type="text" 
            name="student_id" 
            id="student_id" 
            placeholder="Enter Student ID"
            required
        >

        <br>

        <button type="submit">Update Status</button>
    </form>

    <hr>

    <h3>Camera QR Scan</h3>

    <div id="reader"></div>

    <a href="dashboard.php" class="btn">Back</a>

</div>

<script>
    function onScanSuccess(decodedText) {
        document.getElementById("student_id").value = decodedText;
        document.getElementById("manualForm").submit();
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        {
            fps: 10,
            qrbox: 250
        }
    );

    html5QrcodeScanner.render(onScanSuccess);
</script>

</body>
</html>