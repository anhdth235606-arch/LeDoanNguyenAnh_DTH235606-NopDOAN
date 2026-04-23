<?php
require_once 'ket_noi.php';

if (isset($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);
    $sql = "SELECT id, ten_phim, nam_phat_hanh, hinh_anh 
            FROM phim 
            WHERE ten_phim LIKE '%$keyword%' 
            ORDER BY ten_phim 
            LIMIT 8";
    $result = mysqli_query($conn, $sql);
    
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $row['hinh_anh'] = !empty($row['hinh_anh']) ? 'images/' . $row['hinh_anh'] : 'images/default-movie.jpg';
        $data[] = $row;
    }
    
    header('Content-Type: application/json');
    echo json_encode($data);
}
?>