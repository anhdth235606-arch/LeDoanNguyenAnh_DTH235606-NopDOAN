<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebPhim - Xem phim truc tuyen mien phi</title>
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
                        <li><a href="admin.php">Quan tri vien</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Dang xuat</a></li>
                <?php else: ?>
                    <li><a href="login.php">Dang nhap</a></li>
                <?php endif; ?>

                <li><a href="#">Trang chu</a></li>
                <li><a href="#">Phim Moi</a></li>
                <li><a href="#">The Loai</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h2>NARUTO SHIPPUDEN</h2>
            <p>Hanh trinh tro thanh Hokage vi dai nhat cua Uzumaki Naruto. Cung don xem nhung tran chien nhan gia dinh cao va day cam xuc!</p>
            <button class="btn-watch">&#9654; XEM NGAY</button>
            <button class="btn-info">THONG TIN</button>
        </div>
    </section>

    <main>
        <h2>Phim Moi Cap Nhat</h2>

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
        <p>Chao mung den voi web xem phim truc tuyen &copy; 2026</p>
    </footer>
</body>
</html>
