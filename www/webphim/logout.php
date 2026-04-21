<?php
// Bắt đầu hoặc kết nối lại với phiên làm việc hiện tại
session_start();

// Xóa toàn bộ dữ liệu của phiên làm việc (xóa trạng thái đã đăng nhập)
session_destroy();

// Chuyển hướng người dùng quay trở lại trang chủ
header("Location: index.php");
exit();
?>