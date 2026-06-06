<?php
require_once "../includes/warden_auth.php";
require_once "../config/db.php";

$result = $conn->query("
    SELECT 
        sh.id,
        s.user_id,
        s.name,
        sh.previous_status,
        sh.new_status,
        sh.scan_time
    FROM status_history sh
    JOIN students s ON sh.student_id = s.id
    ORDER BY sh.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attendance History</title>

    <style>
        body{ margin:0; font-family:Arial,sans-serif; background:#FFEBD4; }
        .header{ background:#F7B5CA; padding:20px; text-align:center; }
        .container{ width:90%; margin:30px auto; }
        .btn{ background:#F7B5CA; padding:10px 16px; border-radius:8px; text-decoration:none; color:#333; font-weight:bold; }
        table{ width:100%; border-collapse:collapse; background:white; margin-top:20px; }
        th,td{ border:1px solid #E8D9C8; padding:12px; text-align:center; }
        th{ background:#F7B5CA; }
        .in{ color:green; font-weight:bold; }
        .out{ color:red; font-weight:bold; }
    </style>
</head>

<body>

<div class="header">
    <h1>Attendance History</h1>
</div>

<div class="container">

    <a href="dashboard.php" class="btn">Back</a>

    <table>
        <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Previous Status</th>
            <th>New Status</th>
            <th>Scan Time</th>
        </tr>

        <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>

                <td class="<?php echo strtolower($row['previous_status']); ?>">
                    <?php echo htmlspecialchars($row['previous_status']); ?>
                </td>

                <td class="<?php echo strtolower($row['new_status']); ?>">
                    <?php echo htmlspecialchars($row['new_status']); ?>
                </td>

                <td><?php echo htmlspecialchars($row['scan_time']); ?></td>
            </tr>
        <?php } ?>
    </table>

</div>

</body>
</html>