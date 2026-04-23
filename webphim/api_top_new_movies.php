<?php
require_once 'ket_noi.php';
header('Content-Type: application/json');

$sql = "SELECT id, ten_phim, hinh_anh, nam_phat_hanh 
        FROM phim 
        ORDER BY id DESC 
        LIMIT 5";
$result = mysqli_query($conn, $sql);

$movies = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['hinh_anh'] = !empty($row['hinh_anh']) ? 'images/' . $row['hinh_anh'] : 'images/default-movie.jpg';
    $movies[] = $row;
}

echo json_encode($movies);