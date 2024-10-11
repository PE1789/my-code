<?php
session_start();

// ตรวจสอบว่าผู้ใช้งานล็อกอินหรือไม่
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php'); // เปลี่ยนเส้นทางไปหน้าล็อกอินถ้าไม่ได้ล็อกอิน
    exit;
}

// ตัวอย่างข้อมูลสินค้าสำหรับแสดงในหน้าแอดมิน
$products = [
    ['id' => 1, 'name' => 'สินค้า A', 'price' => 100],
    ['id' => 2, 'name' => 'สินค้า B', 'price' => 200],
];

// ฟังก์ชันสำหรับลบสินค้าจากระบบ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteID = $_POST['delete_id'];
    // ลบสินค้าจากอาร์เรย์ (ในที่นี้สามารถปรับให้ลบจากฐานข้อมูลได้)
    foreach ($products as $key => $product) {
        if ($product['id'] == $deleteID) {
            unset($products[$key]);
            break;
        }
    }
    // รีเซ็ตคีย์ของอาร์เรย์
    $products = array_values($products);
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าแอดมิน - Ad-Protech</title>
    <link rel="stylesheet" href="Front Panel.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .admin-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .delete-button {
            background-color: #e74c3c;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .delete-button:hover {
            background-color: #c0392b;
        }

        .logout-button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            margin-top: 20px;
        }

        .logout-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<header>
    <h1>หน้าแอดมิน</h1>
</header>

<div class="admin-container">
    <h2>รายการสินค้า</h2>
    <table>
        <thead>
            <tr>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>ราคา</th>
                <th>ดำเนินการ</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['id']) ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['price']) ?></td>
                    <td>
                        <form method="POST" action="admin.php" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?= htmlspecialchars($product['id']) ?>">
                            <button type="submit" class="delete-button">ลบ</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="logout.php" class="logout-button">ออกจากระบบ</a>
</div>

<footer>
    <p>&copy; 2024 Ad-Protech. All Rights Reserved.</p>
</footer>
</body>
</html>
