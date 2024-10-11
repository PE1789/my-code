<?php
// เริ่มต้น session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// เชื่อมต่อ Google Sheets สำหรับบันทึกข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email']; // รับอีเมล
    $password = $_POST['password'];
    $address = $_POST['address']; // รับที่อยู่

    // URL ของ Google Apps Script ที่ใช้ในการบันทึกข้อมูล
    $post_url = 'https://script.google.com/macros/s/AKfycby3RKNxY-V8DiXiycEckYAURMIjk4qQNO6ldyHzOVhjQzxtLuE0WlxaL-TZVfZWfzEZyw/exec';

    // ข้อมูลที่ต้องการส่ง
    $data = array(
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'address' => $address,
    );

    // แปลงข้อมูลเป็น query string
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ),
    );

    // ส่งข้อมูลไปยัง Google Apps Script
    $context  = stream_context_create($options);
    $result = file_get_contents($post_url, false, $context);
    
    if ($result) {
        echo "<script>alert('สมัครสมาชิกสำเร็จ'); window.location.href='login.php';</script>";
        exit();
    } else {
        $error = "เกิดข้อผิดพลาดในการสมัครสมาชิก";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    <link rel="stylesheet" href="Front Panel.css">
    <style>
        /* สไตล์ต่างๆ */
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
        input[type="email"],
        input[type="password"] {
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
        }
        button:hover {
            background-color: #218838;
        }
        .error {
            color: red;
            text-align: center;
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
    <h2>สมัครสมาชิก</h2>
    <?php if (isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <form action="register.php" method="POST">
        <input type="text" name="username" placeholder="ชื่อผู้ใช้" required>
        <input type="email" name="email" placeholder="อีเมล" required>
        <input type="password" name="password" placeholder="รหัสผ่าน" required>
        <input type="text" name="address" placeholder="ที่อยู่" required>
        <button type="submit">สมัครสมาชิก</button>
    </form>
    <button class="back-button" onclick="window.location.href='index.php'">ย้อนกลับ</button>
</div>

</body>
</html>
