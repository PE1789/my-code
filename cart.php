<?php
session_start(); // เริ่มต้น session

// ตรวจสอบว่ามีสินค้าที่อยู่ในตะกร้าหรือไม่
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $cartEmpty = true; // สถานะตะกร้าว่าง
} else {
    $cartEmpty = false; // สถานะตะกร้ามีสินค้า
}

// การลบสินค้าออกจากตะกร้า
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $removeID = $_POST['remove_id'];

    // ค้นหาสินค้าในตะกร้าและลบออก
    foreach ($_SESSION['cart'] as $key => $cartItem) {
        if (isset($cartItem['id']) && $cartItem['id'] === $removeID) {
            unset($_SESSION['cart'][$key]); // ลบสินค้านั้นออก
            $_SESSION['cart'] = array_values($_SESSION['cart']); // รีเซ็ตคีย์ของตะกร้า
            break; // ออกจาก loop
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า - Ad-Protech</title>
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

        .cart-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .item-quantity {
            margin-top: 5px;
            color: #666;
        }

        .remove-button {
            background-color: #e74c3c;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .remove-button:hover {
            background-color: #c0392b;
        }

        .empty-cart {
            text-align: center;
            color: #999;
        }

        .checkout-button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            text-align: center;
            text-decoration: none;
            margin-top: 20px;
            font-size: 18px;
            transition: background-color 0.3s;
        }

        .checkout-button:hover {
            background-color: #2980b9;
        }

        .cancel-button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #e0e0e0;
            color: black;
            border: none;
            border-radius: 4px;
            text-align: center;
            text-decoration: none;
            margin-top: 10px;
            font-size: 18px;
            transition: background-color 0.3s;
        }

        .cancel-button:hover {
            background-color: #d0d0d0;
        }

        /* CSS สำหรับป๊อปอัป */
        .modal {
            display: none; /* ปิดโดยเริ่มต้น */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5); /* พื้นหลังทึบ */
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* ความกว้างของป๊อปอัป */
            max-width: 400px; /* ความกว้างสูงสุด */
            text-align: center;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .qr-code {
            margin-top: 10px;
        }
    </style>
</head>
<body>

<header>
    <h1>ตะกร้าสินค้า</h1>
</header>

<div class="cart-container">
    <?php if ($cartEmpty): ?>
        <div class="empty-cart">
            <p>ตะกร้าของคุณว่างเปล่า</p>
            <a href="index.php">กลับไปเลือกสินค้า</a>
        </div>
    <?php else: ?>
        <?php foreach ($_SESSION['cart'] as $cartItem): ?>
            <div class="cart-item">
                <div class="item-info">
                    <div class="item-name"><?= htmlspecialchars($cartItem['name']) ?></div>
                    <div class="item-quantity">จำนวน: <?= htmlspecialchars($cartItem['quantity']) ?></div>
                </div>
                <form method="POST" action="cart.php" style="margin: 0;">
                    <input type="hidden" name="remove_id" value="<?= htmlspecialchars($cartItem['id']) ?>">
                    <button type="submit" class="remove-button">ลบสินค้าออกจากตะกร้า</button>
                </form>
            </div>
        <?php endforeach; ?>
        <button id="checkoutButton" class="checkout-button">ชำระเงิน</button>
    <?php endif; ?>
    
    <!-- ปุ่มยกเลิกการชำระเงิน -->
    <form method="POST" action="index.php">
        <button type="submit" class="cancel-button">ยกเลิกการชำระเงิน</button>
    </form>
</div>

<!-- ป๊อปอัปสำหรับ QR Code -->
<div id="qrModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <h2>QR Code สำหรับการชำระเงิน</h2>
        <img src="images/my_qr_code.png" alt="QR Code" class="qr-code"> <!-- แทรกรูป QR Code -->
    </div>
</div>

<footer>
    <p>&copy; 2024 Ad-Protech. All Rights Reserved.</p>
</footer>

<script>
    // JavaScript สำหรับเปิดป๊อปอัป
    document.getElementById('checkoutButton').onclick = function() {
        document.getElementById('qrModal').style.display = "block";
    }

    // JavaScript สำหรับปิดป๊อปอัป
    document.getElementById('closeModal').onclick = function() {
        document.getElementById('qrModal').style.display = "none";
    }

    // ปิดป๊อปอัปเมื่อคลิกนอกป๊อปอัป
    window.onclick = function(event) {
        if (event.target == document.getElementById('qrModal')) {
            document.getElementById('qrModal').style.display = "none";
        }
    }
</script>

</body>
</html>
