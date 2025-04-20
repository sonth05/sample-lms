<?php
$host = 'localhost';
$user = 'root';        // Mặc định của XAMPP
$pass = '';            // XAMPP thường không có mật khẩu
$db   = 'quanlykhoahoc'; // Tên database bạn đã tạo

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Lỗi kết nối: ' . $conn->connect_error);
}
?>
