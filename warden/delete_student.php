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

if ($student['room_id'] != NULL) {
    die("This student is allocated to a room. Please deallocate the room first.");
}

if (isset($_POST['confirm_delete'])) {

    $user_id = $student['user_id'];

    $conn->begin_transaction();

    try {
        $stmt1 = $conn->prepare("DELETE FROM student_status WHERE student_id = ?");
        $stmt1->bind_param("i", $student_id);
        $stmt1->execute();

        $stmt2 = $conn->prepare("DELETE FROM students WHERE id = ?");
        $stmt2->bind_param("i", $student_id);
        $stmt2->execute();

        $stmt3 = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt3->bind_param("s", $user_id);
        $stmt3->execute();

        $conn->commit();

        header("Location: manage_students.php");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        die("Error deleting student.");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Student</title>
    <style>
        body{
            margin:0;
            font-family:Arial,sans-serif;
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

        .delete-btn{
            background:#ff8fa3;
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
    <h2>Delete Student</h2>

    <p>Are you sure you want to delete this student?</p>

    <p>
        <strong><?php echo htmlspecialchars($student['user_id']); ?></strong><br>
        <?php echo htmlspecialchars($student['name']); ?>
    </p>

    <form method="POST">
        <button type="submit" name="confirm_delete" class="delete-btn">
            Yes, Delete
        </button>

        <a href="manage_students.php" class="cancel-btn">
            Cancel
        </a>
    </form>
</div>

</body>
</html>