<?php
session_start();
require_once 'ket_noi.php';

// Kiểm tra có phải quản trị viên và có truyền id không
if (isset($_SESSION['user_id']) && $_SESSION['vai_tro'] == 'quan_tri' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // 1. XÓA BẢNG TRUNG GIAN THỂ LOẠI TRƯỚC
    mysqli_query($conn, "DELETE FROM phim_the_loai WHERE id_phim = $id");
    
    // 2. XÓA CÁC TẬP PHIM LIÊN QUAN TRƯỚC
    mysqli_query($conn, "DELETE FROM tap_phim WHERE id_phim = $id");
    
    // 3. CUỐI CÙNG LÀ XÓA PHIM CHÍNH
    $sql = "DELETE FROM phim WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        header("Location: quanlyphim.php");
        exit();
    }

    echo "Lỗi khi xóa phim: " . mysqli_error($conn);
} else {
    header("Location: index.php");
    exit();
}
?>