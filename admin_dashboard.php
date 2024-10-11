<?php
// เริ่มต้น session
session_start();

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php"); // หากไม่ได้เข้าสู่ระบบให้ไปที่หน้าเข้าสู่ระบบ
    exit();
}

// ดึงข้อมูลคำสั่งซื้อจาก Google Sheets
$csv_url = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vQ5qLRcBXn8eKBjFHurukyCr5TjT904Qgi5-n5mXJoSSsRfdWOZf1a6CD8elrkofzO7mLDU9jr6WWXm/pub?output=csv';
$order_data = array_map('str_getcsv', file($csv_url));

// ตรวจสอบว่ามีข้อมูลคำสั่งซื้อหรือไม่
$has_orders = count($order_data) > 1; // นับจำนวนแถว ถ้ามีมากกว่าหนึ่งแถวหมายถึงมีข้อมูลคำสั่งซื้อ
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แดชบอร์ดแอดมิน</title>
    <link rel="stylesheet" href="Front Panel.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
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
        .button {
            display: inline-block;
            margin: 10px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #0056b3;
        }
        /* สไตล์สำหรับ popup */
        .modal {
            display: none; /* ซ่อน modal โดยเริ่มต้น */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 8px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: red;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <script>
        // ฟังก์ชันสำหรับเปิด modal
        function showModal() {
            document.getElementById("myModal").style.display = "block";
        }
        // ฟังก์ชันสำหรับปิด modal
        function closeModal() {
            document.getElementById("myModal").style.display = "none";
        }
        // ปิด modal เมื่อคลิกนอก modal
        window.onclick = function(event) {
            const modal = document.getElementById("myModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</head>
<body>

<div class="container">
    <h1>แดชบอร์ดแอดมิน</h1>
    <p>ยินดีต้อนรับ, <?php echo $_SESSION['username']; ?>!</p>
    
    <h2>จัดการข้อมูล</h2>
    <a class="button" href="edit_products.php">แก้ไขข้อมูลสินค้า</a>
    <a class="button" href="edit_users.php">แก้ไขข้อมูลผู้ใช้</a>

    <?php if ($has_orders): ?>
        <a class="button" href="edit_orders.php">แก้ไขข้อมูลคำสั่งซื้อ</a>
    <?php else: ?>
        <a class="button" class="button" onclick="showModal()">แก้ไขข้อมูลคำสั่งซื้อ</a>
    <?php endif; ?>
    
    <h2>ออกจากระบบ</h2>
    <a class="button" href="logout.php">ออกจากระบบ</a> <!-- ปุ่มออกจากระบบ -->
</div>

<!-- Modal สำหรับแจ้งเตือน -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p>ยังไม่มีการสั่งซื้อ</p>
    </div>
</div>

</body>
</html>
