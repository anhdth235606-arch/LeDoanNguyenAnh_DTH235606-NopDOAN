<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebPhim - Xem phim trực tuyến miễn phí</title>
    
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
                <li><a href="#" style="color: #e50914;">Chào, <?php echo $_SESSION['ho_ten']; ?></a></li>
                <li><a href="logout.php">Đăng xuất</a></li>
            <?php else: ?>
                <li><a href="login.php">Đăng nhập</a></li>
            <?php endif; ?>
            
            <li><a href="#">Trang chủ</a></li>
            <li><a href="#">Phim Mới</a></li>
            <li><a href="#">Thể Loại</a></li>
        </ul>
        </nav>
    </header>
<section class="hero">
        <div class="hero-content">
            <h2>NARUTO SHIPPUDEN</h2>
            <p>Hành trình trở thành Hokage vĩ đại nhất của Uzumaki Naruto. Cùng đón xem những trận chiến nhẫn giả đỉnh cao và đầy cảm xúc!</p>
            <button class="btn-watch">▶ XEM NGAY</button>
            <button class="btn-info">ℹ THÔNG TIN</button>
        </div>
    </section>
    <main>
        <h2>Phim Mới Cập Nhật</h2>
        
        <div class="movie-grid">
            <div class="movie-card">
                <img src="images/phim1.jpg" alt="Code Geass">
                <h3 class="movie-title">Code Geass</h3> </div>
            
            <div class="movie-card">
                <img src="images/phim2.jpg" alt="Evangelion">
                <h3 class="movie-title">Evangelion</h3> </div>
            
            <div class="movie-card">
                <img src="images/phim3.jpg" alt="Naruto Shippuden">
                <h3 class="movie-title">Naruto Shippuden</h3> </div>
            
            <div class="movie-card">
                <img src="images/phim4.jpg" alt="Konosuba">
                <h3 class="movie-title">Konosuba</h3> </div>
            
            <div class="movie-card">
                <img src="images/phim5.jpg" alt="Mushoku Tensei">
                <h3 class="movie-title">Mushoku Tensei</h3> </div>
        </div>
    </main>

    <footer>
        <p>Chào mừng đến với web xem phim trực tuyến &copy; 2026</p>
    </footer>

</body>
</html>