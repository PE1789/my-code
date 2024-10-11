<?php
session_start();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ad-Protech</title>
    <link rel="stylesheet" href="styles.css">
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <img src="MJP 2500.png" alt="MJP 2500">
        </div>
    </section>

    <!-- 3D Printers Section -->
    <section class="product-section">
        <h2>Materials</h2>
        <div class="grid-container">
            <?php
            // ข้อมูลผลิตภัณฑ์ (สินค้า)
            $data = array(
                array("FRU, Kit, Overmolded Filter Screen w/ Front Panel", "image/31-1340.png"),
                array("FRU, Asm, Door Magnets", "image/31-0986.png"),
                array("Cartridge Holder, ProJet 2500", "image/31-0012.png"),
                array("Asm, Cover, Rigid, Drive, Y-Axis", "image/31-1371.png"),
                // เพิ่มรายการสินค้าอื่น ๆ ที่นี่
            );

            // แสดงข้อมูลสินค้า
            foreach ($data as $row) {
                echo "<div class='product-item'>";
                echo "<img src='" . $row[1] . "' alt='" . $row[0] . "'>"; // แสดงรูปภาพ
                // เชื่อมต่อไปยังหน้ารายละเอียดสินค้า
                echo "<a href='product_detail.php?products=" . urlencode($row[0]) . "'><p>" . $row[0] . "</p></a>"; // ชื่อสินค้า
                echo "</div>";
            }
            ?>
        </div>
        <div class="learn-more">
            <a href="materials.pdf" class="learn-more-btn">Learn More</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>© 2024 Ad-Protech. All Rights Reserved.</p>
    </footer>

    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/67098e4d4304e3196ad07e55/1i9ul7o84';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <!--End of Tawk.to Script-->

</body>
</html>
