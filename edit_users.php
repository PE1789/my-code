<?php
// เริ่มต้น session
session_start();

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// URL ของ Google Sheets สำหรับเก็บข้อมูลผู้ใช้
$csv_url = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vQ5qLRcBXn8eKBjFHurukyCr5TjT904Qgi5-n5mXJoSSsRfdWOZf1a6CD8elrkofzO7mLDU9jr6WWXm/pub?output=csv';
$data = array_map('str_getcsv', file($csv_url));

// ลบแถวแรกที่เป็นหัวข้อ
array_shift($data);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลผู้ใช้</title>
    <link rel="stylesheet" href="Front Panel.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .user-list {
            list-style-type: none;
            padding: 0;
        }
        .user-list li {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
        .button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
            display: inline-block;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>แก้ไขข้อมูลผู้ใช้</h1>
    <ul class="user-list">
        <?php foreach ($data as $row): ?>
            <?php if ($row[0] !== 'admin'): // ตรวจสอบไม่ให้แก้ไข admin ?>
                <li>
                    <?php echo htmlspecialchars($row[1]); // ชื่อผู้ใช้ ?>
                    <a href="edit_user_detail.php?user_id=<?php echo htmlspecialchars($row[0]); ?>" class="button">แก้ไข</a> <!-- ปุ่มแก้ไข -->
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
    <a href="admin_dashboard.php" class="button">ย้อนกลับ</a> <!-- ปุ่มย้อนกลับไปยังแดชบอร์ดแอดมิน -->
</div>

</body>
</html>
