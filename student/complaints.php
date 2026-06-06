<?php
require_once "../includes/student_auth.php";
require_once "../config/db.php";

$user_id = $_SESSION['user_id'];
$message = "";

$stmt = $conn->prepare("SELECT id FROM students WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$student_result = $stmt->get_result();

if ($student_result->num_rows == 0) {
    die("Student not found.");
}

$student = $student_result->fetch_assoc();
$student_id = $student['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = trim($_POST['subject']);
    $complaint_text = trim($_POST['complaint_text']);

    if ($subject == "" || $complaint_text == "") {
        $message = "Subject and complaint cannot be empty.";
    } else {
        $insert = $conn->prepare("
            INSERT INTO complaints (student_id, subject, complaint_text)
            VALUES (?, ?, ?)
        ");
        $insert->bind_param("iss", $student_id, $subject, $complaint_text);

        if ($insert->execute()) {
            $message = "Complaint submitted successfully.";
        } else {
            $message = "Error submitting complaint.";
        }
    }
}

$complaints = $conn->prepare("
    SELECT subject, complaint_text, status, created_at
    FROM complaints
    WHERE student_id = ?
    ORDER BY id DESC
");
$complaints->bind_param("i", $student_id);
$complaints->execute();
$result = $complaints->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Complaints</title>

    <style>
        body{ margin:0; font-family:Arial,sans-serif; background:#FFEBD4; }
        .header{ background:#F7B5CA; padding:20px; text-align:center; }
        .container{ width:85%; margin:30px auto; }
        .box{ background:white; padding:25px; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.1); margin-bottom:25px; }
        input, textarea{ width:100%; padding:10px; margin:10px 0 15px; box-sizing:border-box; }
        textarea{ height:100px; }
        button,.btn{ background:#F7B5CA; border:none; padding:10px 16px; border-radius:8px; text-decoration:none; color:#333; font-weight:bold; cursor:pointer; }
        table{ width:100%; border-collapse:collapse; background:white; }
        th,td{ border:1px solid #E8D9C8; padding:12px; text-align:center; }
        th{ background:#F7B5CA; }
        .message{ font-weight:bold; text-align:center; margin-bottom:15px; }
    </style>
</head>

<body>

<div class="header">
    <h1>My Complaints</h1>
</div>

<div class="container">

    <div class="box">
        <?php if($message != "") { ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php } ?>

        <form method="POST">
            <label>Subject</label>
            <input type="text" name="subject" required>

            <label>Complaint</label>
            <textarea name="complaint_text" required></textarea>

            <button type="submit">Submit Complaint</button>
            <a href="dashboard.php" class="btn">Back</a>
        </form>
    </div>

    <table>
        <tr>
            <th>Subject</th>
            <th>Complaint</th>
            <th>Status</th>
            <th>Date</th>
        </tr>

        <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                <td><?php echo htmlspecialchars($row['complaint_text']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
            </tr>
        <?php } ?>
    </table>

</div>

</body>
</html>