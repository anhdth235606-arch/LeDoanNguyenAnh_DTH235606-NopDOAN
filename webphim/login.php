<?php
session_start();
require_once 'ket_noi.php';

$thong_bao = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten_dang_nhap = trim($_POST['username']);
    $mat_khau_nhap_vao = $_POST['password'];

    $sql = "SELECT * FROM nguoi_dung WHERE ten_dang_nhap = '$ten_dang_nhap'";
    $ket_qua = mysqli_query($conn, $sql);

    if ($ket_qua && mysqli_num_rows($ket_qua) > 0) {
        $nguoi_dung = mysqli_fetch_assoc($ket_qua);
        $mat_khau_trong_csdl = $nguoi_dung['mat_khau'];

        $dang_nhap_hop_le = password_verify($mat_khau_nhap_vao, $mat_khau_trong_csdl);

        // Fallback nếu mật khẩu chưa hash
        if (!$dang_nhap_hop_le && $mat_khau_nhap_vao === $mat_khau_trong_csdl) {
            $dang_nhap_hop_le = true;
        }

        if ($dang_nhap_hop_le) {
            $_SESSION['user_id'] = $nguoi_dung['id'];
            $_SESSION['ho_ten'] = $nguoi_dung['ho_ten'];
            $_SESSION['vai_tro'] = !empty($nguoi_dung['vai_tro']) ? $nguoi_dung['vai_tro'] : 'nguoi_dung';

            header("Location: index.php");
            exit();
        }

        $thong_bao = "Mật khẩu không chính xác!";
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

        .login-container {
            background-color: #141414;
            padding: 40px;
            border-radius: 8px;
            border: 1px solid #333;
            width: 350px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }

        .input-field {
            width: 100%;
            padding: 12px 40px 12px 12px; /* chừa chỗ cho icon */
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

        .register-text {
            margin-top: 20px;
            color: #ccc;
            font-size: 14px;
        }

        .register-link {
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
            
            <div class="password-wrapper">
                <input type="password" name="password" id="password" placeholder="Mật khẩu" class="input-field" required>
                <i class="far fa-eye toggle-password" id="togglePassword"></i>
            </div>
            
            <button type="submit" class="btn-submit">Đăng nhập</button>
        </form>

        <p class="register-text">
            Chưa có tài khoản? <a href="register.php" class="register-link">Đăng ký ngay</a>
        </p>

        <a href="index.php" class="back-home">&larr; Quay lại Trang chủ</a>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Đổi icon
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>