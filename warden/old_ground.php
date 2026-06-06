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
    <title>Old Hostel Ground Floor</title>

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

        .floor-layout{
            display:flex;
            justify-content:space-between;
            gap:30px;
        }

        .wing{
            width:45%;
        }

        .wing-title{
            text-align:center;
            background:#F7B5CA;
            padding:10px;
            border-radius:10px;
            margin-bottom:20px;
            font-weight:bold;
        }

        .room-row{
            display:flex;
            justify-content:center;
            align-items:center;
            gap:20px;
            margin-bottom:25px;
        }

        .room{
            width:120px;
            height:120px;
            background:white;
            border:2px solid #F7B5CA;
            border-radius:12px;
            text-align:center;
            padding-top:10px;
            box-sizing:border-box;
        }

        .hall-room{
            width:180px;
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
            height:50px;
            background:#f8f8f8;
            border:2px dashed #F7B5CA;
            border-radius:10px;
            display:flex;
            justify-content:center;
            align-items:center;
            margin:20px 0;
            font-weight:bold;
            color:#666;
        }

        .vertical-corridor{
            width:80px;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#f8f8f8;
            border-radius:10px;
            font-weight:bold;
            color:#555;
            writing-mode:vertical-rl;
            text-orientation:mixed;
            padding:20px 0;
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
    <h1>Old Hostel Ground Floor</h1>
</div>

<div class="main-container">

    <div class="floor-layout">

        <div class="wing">
            <div class="wing-title">LEFT WING</div>

            <div class="room-row">
                <div class="room">
                    <div class="room-name">A11</div>
                    <div class="dots"><?php renderDots($conn, 1, 0, 'A11'); ?></div>
                </div>

                <div class="room">
                    <div class="room-name">A10</div>
                    <div class="dots"><?php renderDots($conn, 1, 0, 'A10'); ?></div>
                </div>

                <div class="room hall-room">
                    <div class="room-name">HALL</div>
                    <div class="dots"><?php renderDots($conn, 1, 0, 'HALL'); ?></div>
                </div>
            </div>

            <div class="horizontal-corridor">
                COMMON CORRIDOR
            </div>

            <div class="room-row">
                <div class="room">
                    <div class="room-name">A9</div>
                    <div class="dots"><?php renderDots($conn, 1, 0, 'A9'); ?></div>
                </div>
            </div>
        </div>

        <div class="vertical-corridor">
            COMMON CORRIDOR
        </div>

        <div class="wing">
            <div class="wing-title">RIGHT WING</div>

            <div class="room-row">
                <div class="room">
                    <div class="room-name">A7</div>
                    <div class="dots"><?php renderDots($conn, 1, 0, 'A7'); ?></div>
                </div>

                <div class="room">
                    <div class="room-name">A5</div>
                    <div class="dots"><?php renderDots($conn, 1, 0, 'A5'); ?></div>
                </div>

                <div class="room">
                    <div class="room-name">A3</div>
                    <div class="dots"><?php renderDots($conn, 1, 0, 'A3'); ?></div>
                </div>

                <div class="room">
                    <div class="room-name">A1</div>
                    <div class="dots"><?php renderDots($conn, 1, 0, 'A1'); ?></div>
                </div>
            </div>

            <div class="horizontal-corridor">
                COMMON CORRIDOR
            </div>

            <div class="room-row">
                <div class="room">
                    <div class="room-name">A8</div>
                    <div class="dots"><?php renderDots($conn, 1, 0, 'A8'); ?></div>
                </div>

                <div class="room">
                    <div class="room-name">A6</div>
                    <div class="dots"><?php renderDots($conn, 1, 0, 'A6'); ?></div>
                </div>

                <div class="room">
                    <div class="room-name">A4</div>
                    <div class="dots"><?php renderDots($conn, 1, 0, 'A4'); ?></div>
                </div>

                <div class="room">
                    <div class="room-name">A2</div>
                    <div class="dots"><?php renderDots($conn, 1, 0, 'A2'); ?></div>
                </div>
            </div>
        </div>

    </div>

    <a href="old_hostel.php" class="back-btn">Back</a>

</div>

</body>
</html>