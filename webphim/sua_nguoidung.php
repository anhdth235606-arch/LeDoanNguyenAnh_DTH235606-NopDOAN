<?php
session_start();
require_once 'ket_noi.php';

// 1. Kiểm tra quyền Admin
if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] != 'quan_tri') {
    header("Location: index.php");
    exit();
}

$thong_bao = "";

// 2. Lấy thông tin người dùng hiện tại để đổ vào Form
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql_get = "SELECT * FROM nguoi_dung WHERE id = $id";
    $res = mysqli_query($conn, $sql_get);
    $user = mysqli_fetch_assoc($res);
    
    if (!$user) {
        die("Người dùng không tồn tại!");
    }
}

// 3. Xử lý khi nhấn nút "Cập nhật"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $ho_ten = $_POST['ho_ten'];
    $email = $_POST['email'];
    $ten_dang_nhap = $_POST['ten_dang_nhap'];
    $vai_tro = $_POST['vai_tro'];

    $sql_update = "UPDATE nguoi_dung SET 
                    ho_ten = '$ho_ten', 
                    email = '$email', 
                    ten_dang_nhap = '$ten_dang_nhap', 
                    vai_tro = '$vai_tro' 
                    WHERE id = $id";

    if (mysqli_query($conn, $sql_update)) {
        $thong_bao = "Cập nhật thành công!";
        // Làm mới dữ liệu để hiển thị lên form
        $user['ho_ten'] = $ho_ten;
        $user['email'] = $email;
        $user['ten_dang_nhap'] = $ten_dang_nhap;
        $user['vai_tro'] = $vai_tro;
    } else {
        $thong_bao = "Lỗi: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa thành viên | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #000; color: white; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .edit-container { background: #1f1f1f; padding: 40px; border-radius: 12px; width: 450px; border: 1px solid #333; box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
        h2 { color: #e50914; margin-bottom: 25px; text-align: center; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #b3b3b3; font-size: 14px; }
        input, select { width: 100%; padding: 12px; background: #2b2b2b; border: 1px solid #444; border-radius: 6px; color: white; box-sizing: border-box; outline: none; }
        input:focus { border-color: #e50914; }
        .btn-save { width: 100%; padding: 15px; background: #e50914; border: none; border-radius: 6px; color: white; font-weight: bold; cursor: pointer; font-size: 16px; transition: 0.3s; }
        .btn-save:hover { background: #ff0a16; transform: translateY(-2px); }
        .back-link { display: block; text-align: center; margin-top: 20px; color: #b3b3b3; text-decoration: none; font-size: 14px; }
        .back-link:hover { color: white; }
        .alert { background: rgba(46, 204, 113, 0.2); color: #2ecc71; padding: 10px; border-radius: 6px; text-align: center; margin-bottom: 20px; border: 1px solid #2ecc71; }
    </style>
</head>
<body>

    <div class="edit-container">
        <h2><i class="fas fa-user-edit"></i> Chỉnh sửa thành viên</h2>
        
        <?php if ($thong_bao != ""): ?>
            <div class="alert"><?php echo $thong_bao; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

            <div class="form-group">
                <label>Họ và tên</label>
                <input type="text" name="ho_ten" value="<?php echo $user['ho_ten']; ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>

            <div class="form-group">
                <label>Tên đăng nhập</label>
                <input type="text" name="ten_dang_nhap" value="<?php echo $user['ten_dang_nhap']; ?>" required>
            </div>

            <div class="form-group">
                <label>Vai trò</label>
                <select name="vai_tro">
                    <option value="nguoi_dung" <?php if($user['vai_tro'] == 'nguoi_dung') echo 'selected'; ?>>Người dùng (Member)</option>
                    <option value="quan_tri" <?php if($user['vai_tro'] == 'quan_tri') echo 'selected'; ?>>Quản trị viên (Admin)</option>
                </select>
            </div>

            <button type="submit" class="btn-save">LƯU THAY ĐỔI</button>
            <a href="quanlynguoidung.php" class="back-link"><i class="fas fa-arrow-left"></i> Quay lại danh sách</a>
        </form>
    </div>

</body>
</html>