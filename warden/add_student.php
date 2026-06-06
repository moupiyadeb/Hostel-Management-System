<?php
require_once "../includes/warden_auth.php";
require_once "../config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = trim($_POST['user_id']);
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $guardian_phone = trim($_POST['guardian_phone']);
    $email = trim($_POST['email']);

    if (!preg_match('/^[0-9]{10}$/', $user_id)) {
        $message = "Student ID must be exactly 10 digits.";
    } else {
        $check = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
        $check->bind_param("s", $user_id);
        $check->execute();
        $check_result = $check->get_result();

        if ($check_result->num_rows > 0) {
            $message = "This Student ID already exists.";
        } else {
            $conn->begin_transaction();

            try {
                $role = "student";
                $password = $user_id; // first password = roll number

                $stmt1 = $conn->prepare("INSERT INTO users (user_id, password, role) VALUES (?, ?, ?)");
                $stmt1->bind_param("sss", $user_id, $password, $role);
                $stmt1->execute();

                $stmt2 = $conn->prepare("
                    INSERT INTO students (user_id, name, phone, guardian_phone, email)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt2->bind_param("sssss", $user_id, $name, $phone, $guardian_phone, $email);
                $stmt2->execute();

                $student_db_id = $conn->insert_id;

                $status = "OUT";
                $stmt3 = $conn->prepare("INSERT INTO student_status (student_id, status) VALUES (?, ?)");
                $stmt3->bind_param("is", $student_db_id, $status);
                $stmt3->execute();

                $conn->commit();

                $message = "Student added successfully. Login ID and first password are both: " . $user_id;

            } catch (Exception $e) {
                $conn->rollback();
                $message = "Error adding student.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <style>
        body{ margin:0; font-family:Arial,sans-serif; background:#FFEBD4; }
        .header{ background:#F7B5CA; padding:20px; text-align:center; }
        .form-box{ width:420px; margin:35px auto; background:white; padding:25px; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
        input{ width:100%; padding:10px; margin:8px 0 15px; box-sizing:border-box; }
        button,.btn{ background:#F7B5CA; border:none; padding:10px 16px; border-radius:8px; text-decoration:none; color:#333; font-weight:bold; cursor:pointer; }
        .message{ text-align:center; margin-bottom:15px; font-weight:bold; }
    </style>
</head>
<body>

<div class="header">
    <h1>Add Student</h1>
</div>

<div class="form-box">

    <?php if($message != "") { ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php } ?>

    <form method="POST">

        <label>Student ID / Roll Number (10 digits)</label>
        <input type="text" name="user_id" maxlength="10" required>

        <label>Name</label>
        <input type="text" name="name" required>

        <label>Phone</label>
        <input type="text" name="phone">

        <label>Guardian Phone</label>
        <input type="text" name="guardian_phone">

        <label>Email</label>
        <input type="email" name="email">

        <button type="submit">Add Student</button>
        <a class="btn" href="manage_students.php">Back</a>

    </form>
</div>

</body>
</html>