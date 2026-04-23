<?php
require_once 'ket_noi.php';

$thong_bao = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ho_ten = $_POST['fullname'];
    $email = $_POST['email'];
    $ten_dang_nhap = $_POST['username'];
    $mat_khau_nhap_vao = $_POST['password'];
    $xac_nhan_mat_khau = $_POST['confirm_password'];

    if ($mat_khau_nhap_vao != $xac_nhan_mat_khau) {
        $thong_bao = "Mật khẩu xác nhận không khớp!";
    } else {
        $mat_khau_ma_hoa = password_hash($mat_khau_nhap_vao, PASSWORD_DEFAULT);

        $sql = "INSERT INTO nguoi_dung (ho_ten, email, ten_dang_nhap, mat_khau) 
                VALUES ('$ho_ten', '$email', '$ten_dang_nhap', '$mat_khau_ma_hoa')";

        if (mysqli_query($conn, $sql)) {
            $thong_bao = "Đăng ký thành công! Bạn có thể đăng nhập ngay.";
        } else {
            $thong_bao = "Lỗi: Tài khoản hoặc Email đã tồn tại!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký thành viên - Web Phim</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #000;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .register-container {
            background-color: #141414;
            padding: 40px;
            border-radius: 8px;
            border: 1px solid #333;
            width: 380px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
        }

        .input-field {
            width: 100%;
            padding: 12px 40px 12px 12px;
            margin: 10px 0;
            border: 1px solid #555;
            border-radius: 4px;
            background-color: #333;
            color: white;
            box-sizing: border-box;
        }

        .password-wrapper {
            position: relative;
            width: 100%;
        }

        .password-wrapper .input-field {
            padding-right: 40px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
            transition: 0.2s;
        }
        .toggle-password:hover {
            color: white;
        }

        .btn-submit {
            width: 100%;
            background-color: #e50914;
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

        .login-text {
            margin-top: 20px;
            color: #ccc;
            font-size: 14px;
        }

        .login-link {
            color: #e50914;
            text-decoration: underline;
            font-weight: bold;
        }

        .back-home {
            display: inline-block;
            margin-top: 20px;
            color: #aaa;
            text-decoration: none;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2 style="margin-bottom: 25px;">Đăng Ký Tài Khoản</h2>
        <p style="color: yellow; font-weight: bold;"><?php echo $thong_bao; ?></p>
        
        <form action="" method="POST">
            <input type="text" name="fullname" placeholder="Họ và tên" class="input-field" required>
            <input type="email" name="email" placeholder="Email" class="input-field" required>
            <input type="text" name="username" placeholder="Tên đăng nhập" class="input-field" required>
            
            <div class="password-wrapper">
                <input type="password" name="password" id="password" placeholder="Mật khẩu" class="input-field" required>
                <i class="far fa-eye toggle-password" id="togglePassword"></i>
            </div>
            
            <div class="password-wrapper">
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Xác nhận mật khẩu" class="input-field" required>
                <i class="far fa-eye toggle-password" id="toggleConfirmPassword"></i>
            </div>
            
            <button type="submit" class="btn-submit">Đăng ký tài khoản</button>
        </form>

        <p class="login-text">
            Đã có tài khoản? <a href="login.php" class="login-link">Đăng nhập tại đây</a>
        </p>

        <a href="index.php" class="back-home">&larr; Quay lại Trang chủ</a>
    </div>

    <script>
        // Hàm toggle chung
        function setupPasswordToggle(toggleId, inputId) {
            const toggle = document.getElementById(toggleId);
            const input = document.getElementById(inputId);
            toggle.addEventListener('click', function () {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }

        setupPasswordToggle('togglePassword', 'password');
        setupPasswordToggle('toggleConfirmPassword', 'confirm_password');
    </script>
</body>
</html>