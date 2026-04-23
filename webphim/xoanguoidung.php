<?php
session_start();
require_once 'ket_noi.php';

// Kiểm tra quyền Admin
if (isset($_SESSION['user_id']) && $_SESSION['vai_tro'] == 'quan_tri' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Ngăn chặn Admin tự xóa chính mình
    if ($id == $_SESSION['user_id']) {
        echo "<script>alert('Lỗi: Bạn không thể tự xóa tài khoản của chính mình!'); window.location.href='quanlynguoidung.php';</script>";
        exit();
    }

    // Thực hiện xóa người dùng
    $sql = "DELETE FROM nguoi_dung WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        header("Location: quanlynguoidung.php");
        exit();
    } else {
        echo "Lỗi khi xóa: " . mysqli_error($conn);
    }
} else {
    header("Location: index.php");
    exit();
}
?>