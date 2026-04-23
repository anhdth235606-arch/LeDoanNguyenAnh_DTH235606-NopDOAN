<?php
session_start();
require_once 'ket_noi.php';

if (isset($_SESSION['vai_tro']) && $_SESSION['vai_tro'] == 'quan_tri' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Lưu ý: Nếu có phim đang thuộc thể loại này, bạn nên xử lý (xóa phim hoặc đổi thể loại phim) 
    // Ở đây chúng ta thực hiện xóa đơn giản
    $sql = "DELETE FROM the_loai WHERE id = $id";
    mysqli_query($conn, $sql);
    header("Location: quanlytheloai.php");
}
?>