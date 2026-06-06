<?php
require_once "../includes/warden_auth.php";
require_once "../config/db.php";

$message = "";

function floorName($floor_no) {
    if ($floor_no == 0) return "Ground Floor";
    if ($floor_no == 1) return "First Floor";
    if ($floor_no == 2) return "Second Floor";
    return "Floor " . $floor_no;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_id = intval($_POST['student_id']);
    $room_id = intval($_POST['room_id']);

    $roomQuery = $conn->prepare("SELECT capacity FROM rooms WHERE id = ?");
    $roomQuery->bind_param("i", $room_id);
    $roomQuery->execute();
    $roomResult = $roomQuery->get_result();

    if ($roomResult->num_rows == 0) {
        $message = "Invalid room selected.";
    } else {
        $room = $roomResult->fetch_assoc();
        $capacity = $room['capacity'];

        $countQuery = $conn->prepare("SELECT COUNT(*) AS total FROM students WHERE room_id = ?");
        $countQuery->bind_param("i", $room_id);
        $countQuery->execute();
        $countResult = $countQuery->get_result()->fetch_assoc();

        if ($countResult['total'] >= $capacity) {
            $message = "This room is already full.";
        } else {
            $update = $conn->prepare("UPDATE students SET room_id = ? WHERE id = ? AND room_id IS NULL");
            $update->bind_param("ii", $room_id, $student_id);

            if ($update->execute() && $update->affected_rows == 1) {
                $message = "Student allocated successfully.";
            } else {
                $message = "Allocation failed. Student may already be allocated.";
            }
        }
    }
}

$students = $conn->query("
    SELECT id, user_id, name
    FROM students
    WHERE room_id IS NULL
    ORDER BY name ASC
");

$rooms = $conn->query("
    SELECT 
        rooms.id,
        rooms.floor_no,
        rooms.room_number,
        rooms.wing,
        rooms.capacity,
        hostels.hostel_name,
        (
            SELECT COUNT(*)
            FROM students
            WHERE students.room_id = rooms.id
        ) AS occupied
    FROM rooms
    JOIN hostels ON rooms.hostel_id = hostels.id
    ORDER BY rooms.hostel_id, rooms.floor_no, rooms.room_number
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Allocate Student</title>

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

        .form-box{
            width:520px;
            margin:40px auto;
            background:white;
            padding:25px;
            border-radius:12px;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
        }

        label{
            font-weight:bold;
        }

        select{
            width:100%;
            padding:10px;
            margin:10px 0 20px;
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
        }

        .message{
            text-align:center;
            margin-bottom:15px;
            font-weight:bold;
        }
    </style>
</head>

<body>

<div class="header">
    <h1>Allocate Student To Room</h1>
</div>

<div class="form-box">

    <?php if($message != "") { ?>
        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php } ?>

    <form method="POST">

        <label>Select Student</label>
        <select name="student_id" required>
            <option value="">-- Select Student --</option>

            <?php while($student = $students->fetch_assoc()) { ?>
                <option value="<?php echo $student['id']; ?>">
                    <?php 
                        echo htmlspecialchars(
                            $student['name'] . " (" . $student['user_id'] . ")"
                        ); 
                    ?>
                </option>
            <?php } ?>
        </select>

        <label>Select Room</label>
        <select name="room_id" required>
            <option value="">-- Select Room --</option>

            <?php while($room = $rooms->fetch_assoc()) { ?>

                <?php
                    $available = $room['capacity'] - $room['occupied'];
                    $disabled = ($available <= 0) ? "disabled" : "";
                ?>

                <option value="<?php echo $room['id']; ?>" <?php echo $disabled; ?>>
                    <?php
                        echo htmlspecialchars(
                            $room['hostel_name'] . " - " .
                            floorName($room['floor_no']) . " - " .
                            $room['wing'] . " Wing - " .
                            $room['room_number'] .
                            " (" . $room['occupied'] . "/" . $room['capacity'] . ")"
                        );
                    ?>
                </option>

            <?php } ?>
        </select>

        <button type="submit">Allocate Room</button>
        <a href="manage_students.php" class="btn">Back</a>

    </form>
</div>

</body>
</html>