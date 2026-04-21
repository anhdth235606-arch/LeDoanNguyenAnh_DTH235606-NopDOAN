<?php
session_start();

// BẢO MẬT: Kiểm tra nếu chưa đăng nhập HOẶC không phải admin thì đuổi về trang chủ
if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] != 'quan_tri') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bảng điều khiển Admin</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; }
        .sidebar { width: 250px; background-color: #141414; height: 100vh; position: fixed; color: white; padding-top: 20px;}
        .sidebar a { display: block; color: white; padding: 15px; text-decoration: none; }
        .sidebar a:hover { background-color: #e50914; }
        .content { margin-left: 250px; padding: 20px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2 style="text-align: center;">ADMIN PANEL</h2>
        <a href="admin.php">Bảng điều khiển</a>
        <a href="#">Quản lý Phim</a>
        <a href="#">Quản lý Thể loại</a>
        <a href="#">Quản lý Người dùng</a>
        <a href="index.php">&larr; Về trang web ngoài</a>
    </div>

    <div class="content">
        <h1>Xin chào Quản trị viên: <?php echo $_SESSION['ho_ten']; ?>!</h1>
        <p>Đây là khu vực dành riêng cho bạn để quản lý toàn bộ website.</p>
        
        </div>

</body>
</html>