
<?php session_start(); ?>
<?php require_once 'ket_noi.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebPhim - Xem phim trực tuyến miễn phí </title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="header-left">
            <img src="images/logo.png" alt="Logo Web Phim" class="logo">
        </div>

        <nav>
            <ul>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="#" style="color: #e50914;">Chao, <?php echo $_SESSION['ho_ten']; ?></a></li>
                    <?php if (isset($_SESSION['vai_tro']) && $_SESSION['vai_tro'] == 'quan_tri'): ?>
                        <li><a href="admin.php">Quản trị viên</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Đăng xuất</a></li>
                <?php else: ?>
                    <li><a href="login.php">Đăng nhập</a></li>
                <?php endif; ?>

                <li><a href="trangchu.php">Trang chủ</a></li>
                <li><a href="#">Phim mới</a></li>
                <li><a href="theloai.php">Thể Loại</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h2>NARUTO SHIPPUDEN</h2>
            <p>Tiếp nối phần I sau khi cậu bé cùng sư phụ Jiraiya của mình đi tập luyện xa trở về làng Lá. Sau khi huấn luyện 2 năm rưỡi với Jiraiya, Naruto trở về làng Lá, đoàn tụ với những người bạn cậu đã chia tay, và lập lại Nhóm 7, giờ được gọi là Nhóm Kakashi, với Sai thế chỗ Sasuke. Tất cả những người bạn của Naruto đều đã trưởng thành và thăng cấp, có người hơn người khác. Không giống như phần đầu khi chúng chỉ đóng vai trò phụ, tổ chức Akatsuki chiếm lấy vai trò đối nghịch chính trong tham vọng thống trị thế giới.</p>
            <button class="btn-watch">&#9654; XEM NGAY</button>
            <button class="btn-info">THONG TIN</button>
        </div>
    </section>
   
    <main>
        <h2>Phim Mới Cập Nhật </h2>

        <div class="movie-grid">
            <div class="movie-card">
                <img src="images/phim1.jpg" alt="Code Geass">
                <h3 class="movie-title">Code Geass</h3>
            </div>

            <div class="movie-card">
                <img src="images/phim2.jpg" alt="Evangelion">
                <h3 class="movie-title">Evangelion</h3>
            </div>

            <div class="movie-card">
                <img src="images/phim3.jpg" alt="Naruto Shippuden">
                <h3 class="movie-title">Naruto Shippuden</h3>
            </div>

            <div class="movie-card">
                <img src="images/phim4.jpg" alt="Konosuba">
                <h3 class="movie-title">Konosuba</h3>
            </div>

            <div class="movie-card">
                <img src="images/phim5.jpg" alt="Mushoku Tensei">
                <h3 class="movie-title">Mushoku Tensei</h3>
            </div>
        </div>
    </main>

    <footer>
        <p>CHÀO MỨNG ĐẾN VỚI WebPhim TRỰC TUYẾN  &copy; 2026</p>
    </footer>
</body>
</html>
