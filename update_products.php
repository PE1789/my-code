<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// รับข้อมูลจากแบบฟอร์ม
$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$product_quantity = (int)$_POST['product_quantity']; // แปลงจำนวนสินค้าเป็นจำนวนเต็ม

// URL ของ Google Sheets สำหรับเก็บข้อมูลสินค้า
$csv_url = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vRXQmdaIKvb89PLzVihdl2NDgRE2zTTcS56Hcc_WAuZT_GRZilhFxOp_tjweVtAQgsiE0GFB9oht2Bi/pub?output=csv';
$data = array_map('str_getcsv', file($csv_url));

// แปลงข้อมูลเป็นฟอร์แมตที่สามารถอัปเดตได้
$updated_data = [];
$found = false;

foreach ($data as $row) {
    if ($row[0] == $product_id) {
        // ถ้าพบสินค้าที่ต้องการแก้ไข
        $updated_data[] = [$product_id, $product_name, $product_quantity];
        $found = true;
    } else {
        // เพิ่มข้อมูลเดิม
        $updated_data[] = $row;
    }
}

// ถ้าไม่พบสินค้าตามรหัสที่ระบุ
if (!$found) {
    $updated_data[] = [$product_id, $product_name, $product_quantity]; // เพิ่มข้อมูลใหม่
}

// สร้างไฟล์ CSV ใหม่
$file = fopen('ฐานข้อมูล AD-Protech.csv', 'w'); // เปลี่ยนชื่อไฟล์ให้ตรงกับ Google Sheets ที่ต้องการ
foreach ($updated_data as $line) {
    fputcsv($file, $line);
}
fclose($file);

// เพิ่มโค้ดที่นี่เพื่ออัปโหลดไฟล์ CSV ไปยัง Google Sheets (เช่น ใช้ Google Sheets API)

// เปลี่ยนเส้นทางไปยังหน้าเดิมหรือหน้าอื่นตามที่ต้องการ
header("Location: edit_products.php");
exit();
?>
