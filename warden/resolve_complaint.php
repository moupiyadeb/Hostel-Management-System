<?php
require_once "../includes/warden_auth.php";
require_once "../config/db.php";

if (!isset($_GET['id'])) {
    header("Location: view_complaints.php");
    exit();
}

$complaint_id = intval($_GET['id']);

$stmt = $conn->prepare("
    SELECT id, status
    FROM complaints
    WHERE id = ?
");

$stmt->bind_param("i", $complaint_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Complaint not found.");
}

$complaint = $result->fetch_assoc();

if (strtolower($complaint['status']) == 'resolved') {
    header("Location: view_complaints.php");
    exit();
}

if (isset($_POST['confirm_resolve'])) {

    $update = $conn->prepare("
        UPDATE complaints
        SET status = 'Resolved'
        WHERE id = ?
    ");

    $update->bind_param("i", $complaint_id);

    if ($update->execute()) {
        header("Location: view_complaints.php");
        exit();
    } else {
        die("Failed to update complaint.");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Resolve Complaint</title>

    <style>
        body{
            margin:0;
            font-family:Arial,sans-serif;
            background:#FFEBD4;
        }

        .box{
            width:450px;
            margin:80px auto;
            background:white;
            padding:25px;
            border-radius:12px;
            text-align:center;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
        }

        .resolve-btn{
            background:#9BE7A2;
            border:none;
            padding:10px 16px;
            border-radius:8px;
            font-weight:bold;
            cursor:pointer;
        }

        .cancel-btn{
            background:#F7B5CA;
            padding:10px 16px;
            border-radius:8px;
            text-decoration:none;
            color:#333;
            font-weight:bold;
        }
    </style>
</head>

<body>

<div class="box">

    <h2>Resolve Complaint</h2>

    <p>Mark this complaint as resolved?</p>

    <form method="POST">

        <button
            type="submit"
            name="confirm_resolve"
            class="resolve-btn">
            Yes, Resolve
        </button>

        <a
            href="view_complaints.php"
            class="cancel-btn">
            Cancel
        </a>

    </form>

</div>

</body>
</html>