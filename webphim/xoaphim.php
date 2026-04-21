<?php
session_start();
require_once 'ket_noi.php';

if (isset($_SESSION['user_id']) && $_SESSION['vai_tro'] == 'quan_tri' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM phim WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        header("Location: quan_ly_phim.php");
    } else {
        echo "Lỗi khi xóa phim!";
    }
} else {
    header("Location: index.php");
}
?>