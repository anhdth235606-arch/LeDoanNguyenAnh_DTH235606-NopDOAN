<?php
session_start(); // Bắt đầu phiên làm việc để lưu trạng thái đăng nhập
require_once 'ket_noi.php';

$thong_bao = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten_dang_nhap = $_POST['username'];
    $mat_khau_nhap_vao = $_POST['password'];

    // Lệnh SQL để tìm người dùng có tên đăng nhập này
    $sql = "SELECT * FROM nguoi_dung WHERE ten_dang_nhap = '$ten_dang_nhap'";
    $ket_qua = mysqli_query($conn, $sql);

    // Nếu tìm thấy tài khoản
    if (mysqli_num_rows($ket_qua) > 0) {
        $nguoi_dung = mysqli_fetch_assoc($ket_qua);
        
        // Kiểm tra mật khẩu (Vì lúc đăng ký ta đã mã hóa mật khẩu)
        if (password_verify($mat_khau_nhap_vao, $nguoi_dung['mat_khau'])) {
            // Nếu đúng mật khẩu -> Lưu thông tin vào Session
            $_SESSION['user_id'] = $nguoi_dung['id'];
            $_SESSION['ho_ten'] = $nguoi_dung['ho_ten'];
            $_SESSION['vai_tro'] = $nguoi_dung['vai_tro'];
            
            // Chuyển hướng người dùng về trang chủ
            header("Location: index.php");
            exit();
        } else {
            $thong_bao = "Mật khẩu không chính xác!";
        }
    } else {
        $thong_bao = "Tài khoản không tồn tại!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Web Phim</title>
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        /* CSS viết riêng cho trang đăng nhập để căn giữa màn hình */
        body {
            background-color: #000; /* Nền đen giống web phim */
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Chiều cao bằng 100% màn hình */
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .login-container {
            background-color: #141414;
            padding: 40px;
            border-radius: 8px;
            border: 1px solid #333;
            width: 350px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
        }

        .input-field {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #555;
            border-radius: 4px;
            background-color: #333;
            color: white;
            box-sizing: border-box;
        }

        .btn-submit {
            width: 100%;
            background-color: #e50914; /* Màu đỏ */
            color: white;
            padding: 12px;
            margin-top: 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
        }

        .btn-submit:hover {
            background-color: #f40612;
        }

        .register-text {
            margin-top: 20px;
            color: #ccc;
            font-size: 14px;
        }

        .register-link {
            color: #e50914;
            text-decoration: underline; /* Gạch chân chữ */
            font-weight: bold;
        }
        
        .back-home {
            display: inline-block;
            margin-top: 20px;
            color: #aaa;
            text-decoration: none;
            font-size: 14px;
        }
        
        .back-home:hover {
            color: white;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2 style="margin-bottom: 25px;">Đăng Nhập</h2>
        <p style="color: yellow; font-weight: bold;"><?php echo $thong_bao; ?></p>
        
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Tài khoản" class="input-field" required>
            <input type="password" name="password" placeholder="Mật khẩu" class="input-field" required>
            <button type="submit" class="btn-submit">Đăng nhập</button>
        </form>

        <p class="register-text">
            Chưa có tài khoản? <a href="register.php" class="register-link">Đăng ký ngay</a>
        </p>

        <a href="index.php" class="back-home">&larr; Quay lại Trang chủ</a>
    </div>

</body>
</html>