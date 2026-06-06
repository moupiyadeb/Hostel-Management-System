<?php
require_once "../includes/warden_auth.php";
require_once "../config/db.php";

$result = $conn->query("SELECT * FROM students ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Students</title>

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

        .container{
            width:90%;
            margin:30px auto;
        }

        .btn{
            background:#F7B5CA;
            padding:10px 18px;
            border-radius:8px;
            text-decoration:none;
            color:#333;
            font-weight:bold;
            margin-right:10px;
        }

        table{
            width:100%;
            background:white;
            border-collapse:collapse;
            margin-top:25px;
        }

        th, td{
            border:1px solid #E8D9C8;
            padding:12px;
            text-align:center;
        }

        th{
            background:#F7B5CA;
        }

        .delete-btn{
            background:#ff8fa3;
            padding:8px 12px;
            border-radius:6px;
            text-decoration:none;
            color:#333;
            font-weight:bold;
        }

        .deallocate-btn{
            background:#F7B5CA;
            padding:8px 12px;
            border-radius:6px;
            text-decoration:none;
            color:#333;
            font-weight:bold;
            margin-right:5px;
        }
    </style>
</head>

<body>

<div class="header">
    <h1>Manage Students</h1>
</div>

<div class="container">

    <a class="btn" href="add_student.php">Add Student</a>
    <a class="btn" href="allocate_student.php">Allocate Room</a>
    <a class="btn" href="dashboard.php">Back</a>

    <table>
        <tr>
            <th>Roll Number</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Guardian Phone</th>
            <th>Email</th>
            <th>Room Status</th>
            <th>Action</th>
        </tr>

        <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                <td><?php echo htmlspecialchars($row['guardian_phone']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>

                <td>
                    <?php echo $row['room_id'] ? "Allocated" : "Not Allocated"; ?>
                </td>

                <td>
                    <?php if($row['room_id']) { ?>
                        <a 
                            class="deallocate-btn"
                            href="deallocate_student.php?id=<?php echo $row['id']; ?>"
                        >
                            Deallocate
                        </a>
                    <?php } ?>

                    <a 
                        class="delete-btn"
                        href="delete_student.php?id=<?php echo $row['id']; ?>"
                    >
                        Delete
                    </a>
                </td>
            </tr>
        <?php } ?>
    </table>

</div>

</body>
</html>