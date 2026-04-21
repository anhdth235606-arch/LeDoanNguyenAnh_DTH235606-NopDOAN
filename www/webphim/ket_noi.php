<?php
$may_chu = "localhost";
$tai_khoan = "root";
$mat_khau = "vertrigo"; // Vertrigo mặc định mật khẩu MySQL là "vertrigo"
$ten_csdl = "db_webphim";

// Tạo kết nối
$conn = mysqli_connect($may_chu, $tai_khoan, $mat_khau, $ten_csdl);

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Đặt font chữ tiếng Việt để không bị lỗi font
mysqli_set_charset($conn, "utf8mb4");
?>