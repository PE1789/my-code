<?php
// ตรวจสอบว่า session ยังไม่ได้เริ่มต้น
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // เริ่มต้น session ที่นี่
}

// URL ของ CSV ที่ได้จาก Google Sheets
$csv_url = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vRXQmdaIKvb89PLzVihdl2NDgRE2zTTcS56Hcc_WAuZT_GRZilhFxOp_tjweVtAQgsiE0GFB9oht2Bi/pub?output=csv';

// ดึงข้อมูล CSV
$data = array_map('str_getcsv', file($csv_url));

// กำหนดสินค้าที่ต้องการแสดง
$productsToShow = [
    ['31-1340', 'FRU, Kit, Overmolded Filter Screen w/ Front Panel', 10],
    ['31-0986', 'FRU, Asm, Door Magnets', 12],
    ['31-0012', 'Cartridge Holder, ProJet 2500', 5],
    ['31-1371', 'Asm, Cover, Rigid, Drive, Y-Axis', 8],
];

// เริ่มต้นตะกร้าสินค้า
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ตรวจสอบการเพิ่มสินค้าลงในตะกร้า
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productID = $_POST['product_id'];

    // ค้นหาข้อมูลสินค้าจากรหัสสินค้า
    foreach ($productsToShow as &$product) {
        if ($product[0] === $productID) {
            // เพิ่มสินค้าลงในตะกร้า
            $found = false;
            foreach ($_SESSION['cart'] as &$cartItem) {
                if ($cartItem['id'] === $product[0]) {
                    $cartItem['quantity']++; // เพิ่มจำนวนในตะกร้า
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $_SESSION['cart'][] = [
                    'id' => $product[0],
                    'name' => $product[1],
                    'quantity' => 1 // เริ่มต้นจำนวน 1 ชิ้น
                ];
            }

            // ลดจำนวนสินค้าในกรณีที่มีการซื้อ
            $product[2]--; // ลดจำนวนในตัวแปรสินค้า
            break; // ออกจาก loop
        }
    }

    // ออกไปยังหน้าตะกร้า
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ad-Protech</title>
    <link rel="stylesheet" href="Front Panel.css">
    <style>
        /* CSS สำหรับการจัดรูปแบบ */
        .grid-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* 2 คอลัมน์ */
            grid-template-rows: repeat(2, 1fr); /* 2 แถว */
            gap: 10px; /* เว้นระยะห่างระหว่างสินค้า */
            padding: 20px; /* เว้นพื้นที่รอบๆ */
            max-width: 800px; /* จำกัดความกว้าง */
            margin: 0 auto; /* จัดกลาง */
        }

        .product-item {
            border: 1px solid #ccc; /* ขอบของแต่ละสินค้า */
            padding: 10px; /* เว้นพื้นที่รอบๆ */
            text-align: center; /* จัดกลางข้อความ */
            background-color: #f9f9f9; /* สีพื้นหลัง */
            overflow: hidden; /* ซ่อนสิ่งที่ล้นออกมา */
            border-radius: 5px; /* ขอบโค้ง */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* เงาเล็กน้อย */
        }

        .product-image {
            max-width: 100%; /* ให้รูปภาพไม่เกินขนาดของกล่อง */
            height: auto; /* ปรับอัตโนมัติเพื่อรักษาสัดส่วน */
            object-fit: contain; /* ปรับให้ภาพอยู่ในกรอบโดยไม่เสียสัดส่วน */
            margin-bottom: 10px; /* เว้นระยะห่างจากข้อความด้านล่าง */
        }

        .stock {
            font-weight: bold; /* ทำให้จำนวนสินค้าดูเด่นขึ้น */
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<header>
        <div class="logo-container">
            <img src="logo.png" alt="Ad-Protech Logo" class="logo">
            <span class="brand-name">AD-PROTECH</span>
            <form action="#" method="GET" class="search-form">
                <input type="text" placeholder="ค้นหา..." name="search">
                <button type="submit">ค้นหา</button>
            </form>
        </div>
        <nav class="navbar">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="materials.pdf" target="_blank">Materials</a></li>
                <?php if (isset($_SESSION['user'])): ?>
                    <li><a href="cart.php">ตะกร้าสินค้า</a></li>
                    <li><a href="logout.php">ออกจากระบบ</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
                <li><a href="ChatBot.html">Contact Us</a></li>
            </ul>
        </nav>
    </header>
    
    <!-- Product Section -->
    <section class="product-section">
        <h1>Materials</h1>
        <div class="grid-container">
            <?php
            // แสดงข้อมูลสินค้า
            foreach ($productsToShow as $product) {
                $productID = htmlspecialchars($product[0]); // รหัสสินค้า
                $productName = htmlspecialchars($product[1]); // ชื่อสินค้า
                $stockQuantity = htmlspecialchars($product[2]); // จำนวนสินค้า

                echo "<div class='product-item'>";
                // ใช้ชื่อไฟล์จากตำแหน่งที่เก็บรูป
                $imagePath = 'image/' . $productID . '.png'; // แก้ไขชื่อไฟล์ให้ตรงกับรหัสอะไหล่
                echo "<img src='" . $imagePath . "' alt='" . $productName . "' class='product-image'>"; // รูปภาพของสินค้า
                echo "<div class='product-info'>";
                echo "<p>" . $productName . "</p>"; // ชื่อสินค้า
                echo "<div class='stock'>จำนวนสินค้า: " . $stockQuantity . "</div>"; // จำนวนสินค้า
                echo "<form method='POST' action='product_detail.php'>"; // เริ่มแบบฟอร์ม
                echo "<input type='hidden' name='product_id' value='" . $productID . "'>"; // ซ่อนฟิลด์รหัสสินค้า
                echo "<button type='submit' class='add-to-cart'>เพิ่มลงในตะกร้า</button>"; // ปุ่มเพิ่มลงในตะกร้า
                echo "</form>"; // ปิดแบบฟอร์ม
                echo "</div>"; // ปิด product-info
                echo "</div>"; // ปิด product-item
            }
            ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Ad-Protech. All Rights Reserved.</p>
    </footer>
</body>
</html>
