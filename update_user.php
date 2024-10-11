<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// ตรวจสอบการส่งข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // รับค่ารหัสผ่าน
    $address = $_POST['address']; // รับค่าที่อยู่

    // URL ของ Google Sheets สำหรับเก็บข้อมูลผู้ใช้
    $csv_url = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vQ5qLRcBXn8eKBjFHurukyCr5TjT904Qgi5-n5mXJoSSsRfdWOZf1a6CD8elrkofzO7mLDU9jr6WWXm/pub?output=csv';
    $data = array_map('str_getcsv', file($csv_url));

    // อัปเดตข้อมูลใน array
    foreach ($data as &$row) {
        if ($row[0] === $user_id) {
            $row[0] = $username; // อัปเดตชื่อผู้ใช้
            $row[1] = $email;    // อัปเดตอีเมล
            $row[2] = $password; // อัปเดตรหัสผ่าน
            $row[3] = $address;  // อัปเดตที่อยู่
            break;
        }
    }

    // แปลงข้อมูลกลับไปเป็น CSV และบันทึก
    $fp = fopen('data.csv', 'w'); // สร้างไฟล์ใหม่
    foreach ($data as $row) {
        fputcsv($fp, $row);
    }
    fclose($fp);

    // อัปโหลดไฟล์ CSV ไปยัง Google Drive (ใช้ API ของ Google Drive หรือวิธีการที่คุณเลือก)
    // ...

    header("Location: edit_users.php"); // เปลี่ยนเส้นทางกลับไปยังหน้ารายการผู้ใช้
    exit();
}
?>
