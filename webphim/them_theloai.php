<?php
session_start();
require_once 'ket_noi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten = $_POST['ten_the_loai'];
    $sql = "INSERT INTO the_loai (ten_the_loai) VALUES ('$ten')";
    if (mysqli_query($conn, $sql)) {
        header("Location: quanlytheloai.php");
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm thể loại</title>
    <style>
        body { background: #000; color: white; font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .box { background: #1f1f1f; padding: 30px; border-radius: 10px; width: 400px; border: 1px solid #333; }
        input { width: 100%; padding: 12px; margin: 10px 0; background: #2b2b2b; border: 1px solid #444; color: white; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #e50914; border: none; color: white; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Thêm thể loại phim</h2>
        <form method="POST">
            <label>Tên thể loại:</label>
            <input type="text" name="ten_the_loai" required placeholder="VD: Hành động, Viễn tưởng...">
            <button type="submit">LƯU LẠI</button>
            <a href="quanlytheloai.php" style="color: #aaa; display:block; text-align:center; margin-top:15px; text-decoration:none;">Hủy bỏ</a>
        </form>
    </div>
</body>
</html>