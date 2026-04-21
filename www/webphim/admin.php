<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] != 'quan_tri') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bang dieu khien Admin</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; }
        .sidebar { width: 250px; background-color: #141414; height: 100vh; position: fixed; color: white; padding-top: 20px; }
        .sidebar a { display: block; color: white; padding: 15px; text-decoration: none; }
        .sidebar a:hover { background-color: #e50914; }
        .content { margin-left: 250px; padding: 20px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2 style="text-align: center;">ADMIN PANEL</h2>
        <a href="admin.php">Bang dieu khien</a>
        <a href="quanlyphim.php">Quan ly Phim</a>
        <a href="#">Quan ly The loai</a>
        <a href="#">Quan ly Nguoi dung</a>
        <a href="index.php">&larr; Ve trang web ngoai</a>
    </div>

    <div class="content">
        <h1>Xin chao Quan tri vien: <?php echo $_SESSION['ho_ten']; ?>!</h1>
        <p>Day la khu vuc danh rieng cho ban de quan ly toan bo website.</p>
    </div>
</body>
</html>
