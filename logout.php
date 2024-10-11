<?php
// เริ่มต้น session
session_start();

// ทำลายเซสชัน
session_unset();  // ล้างข้อมูลในเซสชัน
session_destroy(); // ทำลายเซสชัน

// เปลี่ยนเส้นทางไปยังหน้า index.php
header("Location: index.php"); 
exit(); // หยุดการทำงานของสคริปต์
?>
