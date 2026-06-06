<?php
require_once "../includes/warden_auth.php";
require_once "../config/db.php";

function renderDots($conn, $hostel_id, $floor_no, $room_number) {
    $sql = "
        SELECT s.user_id, s.name, ss.status
        FROM rooms r
        JOIN students s ON s.room_id = r.id
        LEFT JOIN student_status ss ON ss.student_id = s.id
        WHERE r.hostel_id = ?
        AND r.floor_no = ?
        AND r.room_number = ?
        ORDER BY s.id ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $hostel_id, $floor_no, $room_number);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($student = $result->fetch_assoc()) {
        $status = $student['status'] ?? 'OUT';
        $class = ($status == 'IN') ? 'green-dot' : 'red-dot';

        echo '<span class="' . $class . '" title="' .
            htmlspecialchars($student['user_id'] . ' - ' . $student['name'] . ' - ' . $status) .
            '"></span>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>New Hostel Ground Floor</title>

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

        .main-container{
            width:95%;
            margin:30px auto;
        }

        .wing-title{
            text-align:center;
            background:#F7B5CA;
            padding:10px;
            border-radius:10px;
            margin-bottom:25px;
            font-weight:bold;
        }

        .room-row{
            display:flex;
            justify-content:center;
            align-items:center;
            gap:15px;
            margin-bottom:25px;
            flex-wrap:wrap;
        }

        .room{
            width:100px;
            height:110px;
            background:white;
            border:2px solid #F7B5CA;
            border-radius:12px;
            text-align:center;
            padding-top:10px;
            box-sizing:border-box;
        }

        .room-name{
            font-weight:bold;
            margin-bottom:25px;
        }

        .dots{
            min-height:25px;
        }

        .green-dot,
        .red-dot{
            display:inline-block;
            width:16px;
            height:16px;
            border-radius:50%;
            margin:3px;
            cursor:pointer;
        }

        .green-dot{
            background:#4CAF50;
        }

        .red-dot{
            background:#F44336;
        }

        .horizontal-corridor{
            width:100%;
            height:60px;
            background:#f8f8f8;
            border:2px dashed #F7B5CA;
            border-radius:10px;
            display:flex;
            justify-content:center;
            align-items:center;
            margin:30px 0;
            font-weight:bold;
            color:#666;
        }

        .vertical-corridor{
            width:40px;
            height:110px;
            background:#f8f8f8;
            border:2px dashed #F7B5CA;
            border-radius:10px;
            display:flex;
            justify-content:center;
            align-items:center;
            font-size:12px;
            font-weight:bold;
            color:#666;
            text-align:center;
        }

        .back-btn{
            display:block;
            width:220px;
            text-align:center;
            margin:40px auto;
            text-decoration:none;
            background:#F7B5CA;
            color:#333;
            padding:12px;
            border-radius:10px;
            font-weight:bold;
        }
    </style>
</head>

<body>

<div class="header">
    <h1>New Hostel Ground Floor</h1>
</div>

<div class="main-container">

    <div class="wing-title">
        NEW HOSTEL
    </div>

    <div class="room-row">
        <div class="room">
            <div class="room-name">A8</div>
            <div class="dots"><?php renderDots($conn, 2, 0, 'A8'); ?></div>
        </div>

        <div class="room">
            <div class="room-name">A9</div>
            <div class="dots"><?php renderDots($conn, 2, 0, 'A9'); ?></div>
        </div>

        <div class="room">
            <div class="room-name">A10</div>
            <div class="dots"><?php renderDots($conn, 2, 0, 'A10'); ?></div>
        </div>

        <div class="room">
            <div class="room-name">A11</div>
            <div class="dots"><?php renderDots($conn, 2, 0, 'A11'); ?></div>
        </div>

        <div class="room">
            <div class="room-name">A12</div>
            <div class="dots"><?php renderDots($conn, 2, 0, 'A12'); ?></div>
        </div>

        <div class="room">
            <div class="room-name">A13</div>
            <div class="dots"><?php renderDots($conn, 2, 0, 'A13'); ?></div>
        </div>

        <div class="room">
            <div class="room-name">A14</div>
            <div class="dots"><?php renderDots($conn, 2, 0, 'A14'); ?></div>
        </div>
    </div>

    <div class="horizontal-corridor">
        HORIZONTAL CORRIDOR
    </div>

    <div class="room-row">
        <div class="room">
            <div class="room-name">A7</div>
            <div class="dots"><?php renderDots($conn, 2, 0, 'A7'); ?></div>
        </div>

        <div class="room">
            <div class="room-name">A6</div>
            <div class="dots"><?php renderDots($conn, 2, 0, 'A6'); ?></div>
        </div>

        <div class="room">
            <div class="room-name">A5</div>
            <div class="dots"><?php renderDots($conn, 2, 0, 'A5'); ?></div>
        </div>

        <div class="vertical-corridor">
            CORRIDOR
        </div>

        <div class="room">
            <div class="room-name">A4</div>
            <div class="dots"><?php renderDots($conn, 2, 0, 'A4'); ?></div>
        </div>

        <div class="room">
            <div class="room-name">A3</div>
            <div class="dots"><?php renderDots($conn, 2, 0, 'A3'); ?></div>
        </div>

        <div class="room">
            <div class="room-name">A2</div>
            <div class="dots"><?php renderDots($conn, 2, 0, 'A2'); ?></div>
        </div>

        <div class="room">
            <div class="room-name">A1</div>
            <div class="dots"><?php renderDots($conn, 2, 0, 'A1'); ?></div>
        </div>
    </div>

    <a href="check_rooms.php" class="back-btn">Back</a>

</div>

</body>
</html>