<?php 
// รวมไฟล์ connect.php
require_once 'connect.php';  // รวมไฟล์เพื่อเชื่อมต่อฐานข้อมูล

$room_code_error = "";
$show_name_form = false;
$id = "1";  // ตัวแปรเก็บ id ของห้อง

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['room_code'])) {
        // ตรวจสอบรหัสห้อง
        $room_code = $_POST['room_code'];

        // ตรวจสอบรหัสห้องในฐานข้อมูล
        $sql = "SELECT * FROM RoomDetails WHERE room_code = '$room_code'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // ถ้ารหัสห้องถูกต้อง, แสดงฟอร์มกรอกชื่อ
            $show_name_form = true;
            // เก็บ id จากฐานข้อมูล
            $row = $result->fetch_assoc();
            $title = $row['title'];  // สมมติว่าคอลัมน์ที่ใช้ชื่อว่า title
            $id = $row['id'];   // เก็บ id ห้อง
        } else {
            // ถ้ารหัสห้องไม่ถูกต้อง
            $room_code_error = "รหัสห้องไม่ถูกต้อง กรุณาลองใหม่";
        }
    } elseif (isset($_POST['name'])) {
        // เมื่อผู้ใช้กรอกชื่อ
        $name = $_POST['name'];
        // Redirect ไปยังหน้าถัดไป พร้อมกับส่งชื่อและ room_id ผ่าน URL
        header("Location: welcome.php?name=" . urlencode($name) . "&id=" . urlencode($id));
        exit(); // หยุดการทำงานของโค้ดหลังจาก redirect
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>กรอกรหัสห้อง</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- เริ่มต้น Header -->
    <header class="header">
        <div class="header-logo-container">
            <img src="https://bangkok.traffy.in.th/assets/ic_traffy1-3adc9d55.png" alt="Logo 1" class="header-logo">
            <img src="https://bangkok.traffy.in.th/assets/ic_traffy2-b99cb1d3.png" alt="Logo 2" class="header-logo">
            <img src="https://bangkok.traffy.in.th/assets/ic_bangkok-5e09ba9f.png" alt="Logo 3" class="header-logo">
        </div>
    </header>

    <?php if (!$show_name_form): ?>
    <!-- ฟอร์มกรอกรหัสห้อง -->
    <form method="POST" action="">
        <img src="https://bangkok.traffy.in.th/assets/ic_traffy1-3adc9d55.png" alt="Traffy Logo" class="form-logo">
        <label for="room_code">รหัสห้อง</label>
        <div class="password-container">
            <input type="password" id="room_code" name="room_code" placeholder="กรอกรหัสห้อง" required>
            <i id="togglePassword" class="fas fa-eye"></i>
        </div>
        <button type="submit">ยืนยัน</button>

        <?php if ($room_code_error): ?>
            <p style="color: red;"><?php echo $room_code_error; ?></p>
        <?php endif; ?>
    </form>
    <?php else: ?>
    <!-- ฟอร์มกรอกชื่อผู้ใช้ -->
    <form method="POST" action="">
        <label for="name">กรุณาใส่ชื่อของคุณ</label>
        <input type="text" id="name" name="name" placeholder="กรุณากรอกชื่อ" required>
        <button type="submit">ยืนยัน</button>
    </form>
    <?php endif; ?>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const roomCode = document.getElementById('room_code');

        togglePassword.addEventListener('click', () => {
            const type = roomCode.getAttribute('type') === 'password' ? 'text' : 'password';
            roomCode.setAttribute('type', type);
            togglePassword.classList.toggle('fa-eye');
            togglePassword.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
