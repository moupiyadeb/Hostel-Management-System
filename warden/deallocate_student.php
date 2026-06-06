<?php
require_once "../includes/warden_auth.php";
require_once "../config/db.php";

if (!isset($_GET['id'])) {
    header("Location: manage_students.php");
    exit();
}

$student_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Student not found.");
}

$student = $result->fetch_assoc();

if ($student['room_id'] == NULL) {
    die("This student is not allocated to any room.");
}

if (isset($_POST['confirm_deallocate'])) {

    $update = $conn->prepare("UPDATE students SET room_id = NULL WHERE id = ?");
    $update->bind_param("i", $student_id);

    if ($update->execute()) {
        header("Location: manage_students.php");
        exit();
    } else {
        die("Error deallocating student.");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Deallocate Student</title>

    <style>
        body{
            margin:0;
            font-family:Arial, sans-serif;
            background:#FFEBD4;
        }

        .box{
            width:420px;
            margin:70px auto;
            background:white;
            padding:25px;
            border-radius:12px;
            text-align:center;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
        }

        .confirm-btn{
            background:#F7B5CA;
            border:none;
            padding:10px 16px;
            border-radius:8px;
            font-weight:bold;
            cursor:pointer;
        }

        .cancel-btn{
            background:#ddd;
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

    <h2>Deallocate Student</h2>

    <p>Are you sure you want to remove this student's room allocation?</p>

    <p>
        <strong><?php echo htmlspecialchars($student['user_id']); ?></strong><br>
        <?php echo htmlspecialchars($student['name']); ?>
    </p>

    <form method="POST">
        <button type="submit" name="confirm_deallocate" class="confirm-btn">
            Yes, Deallocate
        </button>

        <a href="manage_students.php" class="cancel-btn">
            Cancel
        </a>
    </form>

</div>

</body>
</html>