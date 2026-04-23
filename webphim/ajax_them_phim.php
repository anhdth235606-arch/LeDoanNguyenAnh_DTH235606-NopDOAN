<?php
session_start();
require_once 'ket_noi.php';

header('Content-Type: application/json');

// Kiểm tra quyền Admin
if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] != 'quan_tri') {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ.']);
    exit();
}

// Lấy dữ liệu
$ten_phim = mysqli_real_escape_string($conn, $_POST['ten_phim']);
$id_the_loai = (int)$_POST['id_the_loai'];
$nam_phat_hanh = (int)$_POST['nam_phat_hanh'];
$mo_ta = mysqli_real_escape_string($conn, $_POST['mo_ta']);

// Xử lý ảnh
if (!isset($_FILES['hinh_anh']) || $_FILES['hinh_anh']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng chọn ảnh poster.']);
    exit();
}

$file = $_FILES['hinh_anh'];
$allowed = ['image/jpeg', 'image/png', 'image/webp'];
if (!in_array($file['type'], $allowed)) {
    echo json_encode(['success' => false, 'message' => 'Chỉ chấp nhận file ảnh JPG, PNG, WEBP.']);
    exit();
}

// Tạo tên file duy nhất
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$new_name = time() . '_' . uniqid() . '.' . $ext;
$target = "images/" . $new_name;

if (!move_uploaded_file($file['tmp_name'], $target)) {
    echo json_encode(['success' => false, 'message' => 'Không thể tải ảnh lên.']);
    exit();
}

// Thêm vào database
$sql = "INSERT INTO phim (ten_phim, id_the_loai, nam_phat_hanh, hinh_anh, mo_ta) 
        VALUES ('$ten_phim', $id_the_loai, $nam_phat_hanh, '$new_name', '$mo_ta')";

if (mysqli_query($conn, $sql)) {
    $new_id = mysqli_insert_id($conn);
    
    // Lấy thêm tên thể loại
    $tl_query = mysqli_query($conn, "SELECT ten_the_loai FROM the_loai WHERE id = $id_the_loai");
    $tl = mysqli_fetch_assoc($tl_query);
    
    echo json_encode([
        'success' => true,
        'message' => 'Thêm phim thành công!',
        'data' => [
            'id' => $new_id,
            'ten_phim' => $ten_phim,
            'hinh_anh' => $new_name,
            'ten_the_loai' => $tl['ten_the_loai'] ?? '',
            'nam_phat_hanh' => $nam_phat_hanh
        ]
    ]);
} else {
    // Xóa ảnh vừa upload nếu insert thất bại
    unlink($target);
    echo json_encode(['success' => false, 'message' => 'Lỗi database: ' . mysqli_error($conn)]);
}
?>