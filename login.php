<?php
// เริ่มต้น session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ตรวจสอบการเข้าสู่ระบบ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากแบบฟอร์ม
    $username = $_POST['username'];
    $password = $_POST['password'];

    // URL ของ Google Sheets สำหรับเก็บข้อมูลผู้ใช้
    $csv_url = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vQ5qLRcBXn8eKBjFHurukyCr5TjT904Qgi5-n5mXJoSSsRfdWOZf1a6CD8elrkofzO7mLDU9jr6WWXm/pub?output=csv';
    $data = array_map('str_getcsv', file($csv_url));
    
    // ตัวแปรสำหรับแอดมิน
    $admin_username = 'admin';
    $admin_password = 'password';

    // ตรวจสอบข้อมูลผู้ใช้
    foreach ($data as $row) {
        // สมมุติว่า username อยู่ในคอลัมน์แรก, password อยู่ในคอลัมน์ที่สอง, และ type (admin/customer) อยู่ในคอลัมน์ที่สาม
        if ($row[0] == $username && $row[1] == $password) { // ใช้รหัสผ่านจาก Google Sheets
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = $row[2]; // เก็บประเภทผู้ใช้ (admin/customer)
            
            // เปลี่ยนเส้นทางไปยังหน้าแตกต่างกันตามประเภทผู้ใช้
            if ($row[2] === 'admin') {
                header("Location: admin_dashboard.php"); // หน้าแดชบอร์ดของแอดมิน
            } else {
                header("Location: user_profile.php"); // หน้าโปรไฟล์ของลูกค้า
            }
            exit();
        }
    }

    // หากไม่มีข้อมูลจาก Google Sheets, ตรวจสอบสำหรับผู้ดูแลระบบ
    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['username'] = $admin_username;
        $_SESSION['user_type'] = 'admin'; // เก็บประเภทผู้ใช้เป็น admin
        header("Location: admin_dashboard.php"); // ไปที่หน้าแดชบอร์ดของแอดมิน
        exit();
    }

    // ถ้าไม่ตรงกับข้อมูลทั้งหมด
    $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link rel="stylesheet" href="Front Panel.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
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
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* ให้ขนาดพอดีกับกรอบ */
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px; /* เพิ่มระยะห่างระหว่างปุ่ม */
        }
        button:hover {
            background-color: #218838;
        }
        .error {
            color: red;
            text-align: center;
            margin: 10px 0;
        }
        .back-button {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>เข้าสู่ระบบ</h2>
    <?php if (isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="ชื่อผู้ใช้" required>
        <input type="password" name="password" placeholder="รหัสผ่าน" required>
        <button type="submit">เข้าสู่ระบบ</button>
    </form>
    <button class="back-button" onclick="window.location.href='index.php'">ย้อนกลับ</button>
</div>

</body>
</html>
