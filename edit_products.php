<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลสินค้า</title>
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
        input[type="number"] {
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
        .back-button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff; /* สีพื้นหลัง */
            color: white; /* สีข้อความ */
            text-decoration: none; /* ไม่ให้มีขีดเส้นใต้ */
            border-radius: 4px; /* มุมป rounded */
            border: 1px solid #007bff; /* ขอบของปุ่ม */
            transition: background-color 0.3s, color 0.3s, border-color 0.3s; /* เอฟเฟกต์การเปลี่ยนสี */
            font-weight: bold; /* ทำให้ข้อความหนาขึ้น */
            margin-top: 10px; /* เว้นระยะด้านบน */
            text-align: center; /* จัดข้อความให้อยู่กลาง */
        }
        .back-button:hover {
            background-color: #0056b3; /* สีเมื่อเมาส์อยู่เหนือ */
            color: #fff; /* สีข้อความเมื่อเมาส์อยู่เหนือ */
            border-color: #0056b3; /* สีขอบเมื่อเมาส์อยู่เหนือ */
            transform: translateY(-2px); /* ยกปุ่มขึ้นเมื่อเมาส์อยู่เหนือ */
        }
    </style>
</head>
<body>

<div class="container">
    <h1>แก้ไขข้อมูลสินค้า</h1>
    <form action="update_products.php" method="POST">
        <!-- เพิ่มฟิลด์สำหรับแก้ไขข้อมูลสินค้า -->
        <label for="product_id">รหัสสินค้า:</label>
        <input type="text" name="product_id" required>

        <label for="product_name">ชื่อสินค้า:</label>
        <input type="text" name="product_name" required>

        <label for="product_quantity">จำนวนสินค้า:</label>
        <input type="number" name="product_quantity" min="0" required> <!-- จำนวนสินค้าเป็นจำนวนเต็ม -->

        <button type="submit">อัปเดตข้อมูล</button>
    </form>
    <!-- ปุ่มย้อนกลับไปยังแดชบอร์ดแอดมิน -->
    <a href="admin_dashboard.php" class="back-button">ย้อนกลับ</a>
</div>

</body>
</html>
