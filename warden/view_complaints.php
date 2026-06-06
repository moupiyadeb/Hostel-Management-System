<?php
require_once "../includes/warden_auth.php";
require_once "../config/db.php";

$result = $conn->query("
    SELECT
        c.id,
        s.user_id,
        s.name,
        r.room_number,
        c.subject,
        c.complaint_text,
        c.status,
        c.created_at
    FROM complaints c
    JOIN students s ON c.student_id = s.id
    LEFT JOIN rooms r ON s.room_id = r.id
    ORDER BY c.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Complaints</title>

    <style>
        body{
            margin:0;
            font-family:Arial,sans-serif;
            background:#FFEBD4;
        }

        .header{
            background:#F7B5CA;
            padding:20px;
            text-align:center;
        }

        .container{
            width:95%;
            margin:30px auto;
        }

        .btn{
            background:#F7B5CA;
            padding:10px 16px;
            border-radius:8px;
            text-decoration:none;
            color:#333;
            font-weight:bold;
        }

        table{
            width:100%;
            border-collapse:collapse;
            background:white;
        }

        th,td{
            border:1px solid #E8D9C8;
            padding:10px;
            text-align:center;
        }

        th{
            background:#F7B5CA;
        }

        .resolve-btn{
            background:#9BE7A2;
            padding:8px 12px;
            border-radius:6px;
            text-decoration:none;
            color:#333;
            font-weight:bold;
        }

        .resolved{
            color:green;
            font-weight:bold;
        }

        .pending{
            color:#d9534f;
            font-weight:bold;
        }
    </style>
</head>

<body>

<div class="header">
    <h1>View Complaints</h1>
</div>

<div class="container">

    <p>
        <a class="btn" href="dashboard.php">Back</a>
    </p>

    <table>
        <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Room</th>
            <th>Subject</th>
            <th>Complaint</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

        <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['room_number'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                <td><?php echo htmlspecialchars($row['complaint_text']); ?></td>

                <td>
                    <?php if(strtolower($row['status']) == 'pending') { ?>
                        <span class="pending">PENDING</span>
                    <?php } else { ?>
                        <span class="resolved">RESOLVED</span>
                    <?php } ?>
                </td>

                <td><?php echo htmlspecialchars($row['created_at']); ?></td>

                <td>
                    <?php if(strtolower($row['status']) == 'pending') { ?>
                        <a
                            class="resolve-btn"
                            href="resolve_complaint.php?id=<?php echo $row['id']; ?>"
                        >
                            Resolve
                        </a>
                    <?php } else { ?>
                        ✓
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>

    </table>

</div>

</body>
</html>