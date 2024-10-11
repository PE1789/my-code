<?php
// เริ่มต้น session
session_start();

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// เช็คว่ามีการส่ง user_id มาหรือไม่
if (!isset($_GET['user_id'])) {
    header("Location: edit_users.php");
    exit();
}

// ดึงข้อมูลจาก Google Sheets
$csv_url = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vQ5qLRcBXn8eKBjFHurukyCr5TjT904Qgi5-n5mXJoSSsRfdWOZf1a6CD8elrkofzO7mLDU9jr6WWXm/pub?output=csv';
$data = array_map('str_getcsv', file($csv_url));

// ค้นหาข้อมูลของผู้ใช้ที่ต้องการแก้ไข
$user_data = [];
foreach ($data as $row) {
    if ($row[0] === $_GET['user_id']) {
        $user_data = $row;
        break;
    }
}

// ตรวจสอบว่าพบข้อมูลผู้ใช้หรือไม่
if (empty($user_data)) {
    header("Location: edit_users.php");
    exit();
}
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
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>แก้ไขข้อมูลผู้ใช้</h1>
    <form action="update_user.php" method="POST">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_data[0]); ?>"> <!-- รหัสผู้ใช้ -->

        <label for="username">ชื่อผู้ใช้:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user_data[0]); ?>" required>

        <label for="email">อีเมล:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user_data[1]); ?>" required>

        <label for="password">รหัสผ่าน:</label>
        <input type="password" name="password" value="<?php echo htmlspecialchars($user_data[2]); ?>" required>

        <label for="address">ที่อยู่:</label>
        <input type="text" name="address" value="<?php echo htmlspecialchars($user_data[3]); ?>" required>

        <button type="submit">อัปเดตข้อมูล</button>
    </form>
    <a href="edit_users.php" class="button">ย้อนกลับ</a> <!-- ปุ่มย้อนกลับไปยังหน้าแก้ไขผู้ใช้ -->
</div>

</body>
</html>
