<?php
// เริ่มต้น session
session_start();

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// URL ของ Google Sheets สำหรับเก็บข้อมูลผู้ใช้
$csv_url = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vQ5qLRcBXn8eKBjFHurukyCr5TjT904Qgi5-n5mXJoSSsRfdWOZf1a6CD8elrkofzO7mLDU9jr6WWXm/pub?output=csv';
$data = array_map('str_getcsv', file($csv_url));

// ค้นหาข้อมูลของผู้ใช้ที่เข้าสู่ระบบ
$user_data = null;
foreach ($data as $row) {
    if ($row[0] == $_SESSION['username']) {
        $user_data = $row;
        break;
    }
}

// ตรวจสอบว่าพบข้อมูลผู้ใช้
if ($user_data === null) {
    echo "ไม่พบข้อมูลผู้ใช้";
    exit();
}

// การบันทึกข้อมูลเมื่อฟอร์มถูกส่ง
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updated_username = $_POST['username'];
    $updated_email = $_POST['email'];
    $updated_password = $_POST['password'];
    $updated_address = $_POST['address'];

    // อัปเดตข้อมูลใน Google Sheets ที่นี่ (โปรดใช้ API หรือวิธีอื่น ๆ ในการบันทึกข้อมูลกลับไปยัง Google Sheets)

    // แสดงข้อความว่าอัปเดตสำเร็จ (สามารถปรับปรุงให้เหมาะสมได้)
    echo "<script>alert('อัปเดตข้อมูลเรียบร้อยแล้ว');</script>";
    $user_data = [$updated_username, $updated_password, $updated_email, $updated_address]; // แสดงข้อมูลที่อัปเดต
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลส่วนตัว</title>
    <link rel="stylesheet" href="Front Panel.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #218838;
        }
        .back-button {
            background-color: #007bff;
            margin-top: 10px;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
        .toggle-password {
            position: absolute;
            right: 20px;
            top: 30px;
            cursor: pointer;
            background: none;
            border: none;
            color: #007bff;
        }
    </style>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleButton = document.getElementById('toggle-password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleButton.innerText = 'ซ่อน';
            } else {
                passwordField.type = 'password';
                toggleButton.innerText = 'แสดง';
            }
        }
    </script>
</head>
<body>

<div class="container">
    <h2>ข้อมูลส่วนตัว</h2>
    <form action="user_profile.php" method="POST">
        <input type="text" name="username" placeholder="ชื่อผู้ใช้" value="<?php echo $user_data[0]; ?>" required>
        <input type="email" name="email" placeholder="อีเมล" value="<?php echo $user_data[1]; ?>" required>
        <div style="position: relative;">
            <input type="password" id="password" name="password" placeholder="รหัสผ่าน" value="<?php echo $user_data[2]; ?>" required>
            <button type="button" class="toggle-password" id="toggle-password" onclick="togglePassword()">แสดง</button>
        </div>
        <textarea name="address" placeholder="ที่อยู่" rows="4" required><?php echo $user_data[3]; ?></textarea>
        <button type="submit">บันทึกข้อมูล</button>
    </form>
    <button class="back-button" onclick="window.location.href='index.php'">ย้อนกลับ</button>
</div>

</body>
</html>
