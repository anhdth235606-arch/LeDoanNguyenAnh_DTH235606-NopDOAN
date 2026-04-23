<?php
session_start();
require_once 'ket_noi.php';
if (isset($_SESSION['vai_tro']) && $_SESSION['vai_tro'] == 'quan_tri' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    mysqli_query($conn, "DELETE FROM tap_phim WHERE id = $id");
    header("Location: quanlytap.php");
    exit();
}
header("Location: index.php");