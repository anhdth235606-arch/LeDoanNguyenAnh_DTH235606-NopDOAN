<?php 
session_start(); 
require_once 'ket_noi.php';

// Lấy danh sách tất cả thể loại
$sql_theloai = "SELECT * FROM the_loai ORDER BY ten_the_loai";
$query_theloai = mysqli_query($conn, $sql_theloai);

// Lọc theo thể loại
$id_tl = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$ten_tl_dang_chon = "Tất cả phim";

if ($id_tl > 0) {
    $sql_phim = "SELECT * FROM phim WHERE id_the_loai = $id_tl ORDER BY id DESC";
    $query_ten = mysqli_query($conn, "SELECT ten_the_loai FROM the_loai WHERE id = $id_tl");
    if($row_ten = mysqli_fetch_assoc($query_ten)){
        $ten_tl_dang_chon = "Phim " . htmlspecialchars($row_ten['ten_the_loai']);
    }
} else {
    $sql_phim = "SELECT * FROM phim ORDER BY id DESC";
}
$query_phim = mysqli_query($conn, $sql_phim);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thể loại phim | WebPhim</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Cải thiện giao diện thể loại */
        body {
            background: #000;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding-top: 80px; /* để tránh header cố định */
        }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 5%;
            background: #000;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-sizing: border-box;
            border-bottom: 1px solid #222;
        }
        .header-left {
            display: flex;
            align-items: center;
        }
        .logo {
            height: 40px;
        }
        nav ul {
            display: flex;
            gap: 20px;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: 0.2s;
        }
        nav ul li a:hover {
            color: #e50914;
        }

        /* Bộ lọc thể loại */
        .category-filter {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 30px 5% 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #333;
        }
        .cat-btn {
            background: #1f1f1f;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 30px;
            transition: 0.3s;
            border: 1px solid #333;
            font-size: 14px;
        }
        .cat-btn:hover, .cat-btn.active {
            background: #e50914;
            border-color: #e50914;
        }

        /* Tiêu đề */
        h2 {
            margin-left: 5%;
            margin-top: 20px;
            color: white;
        }

        /* Grid phim - tối ưu hình ảnh */
        .movie-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
            padding: 20px 5%;
        }
        .movie-card {
            background: #1f1f1f;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            text-decoration: none;
            color: white;
            display: block;
        }
        .movie-card:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 20px rgba(229, 9, 20, 0.2);
        }
        .movie-card img {
            width: 100%;
            height: 270px;          /* Chiều cao cố định */
            object-fit: cover;      /* Cắt ảnh vừa khung, không méo */
            display: block;
        }
        .movie-title {
            padding: 12px 8px;
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            background: #1f1f1f;
        }

        /* Thông báo không có phim */
        .no-movie {
            color: #aaa;
            text-align: center;
            padding: 50px 0;
            font-size: 16px;
            grid-column: 1 / -1;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .movie-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .category-filter {
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-left">
            <img src="images/logo.png" alt="Logo Web Phim" class="logo">
        </div>
        <nav>
            <ul>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="#" style="color: #e50914;">Chào, <?= htmlspecialchars($_SESSION['ho_ten']) ?></a></li>
                    <?php if ($_SESSION['vai_tro'] == 'quan_tri'): ?>
                        <li><a href="admin.php">Quản trị viên</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Đăng xuất</a></li>
                <?php else: ?>
                    <li><a href="login.php">Đăng nhập</a></li>
                <?php endif; ?>
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="#">Phim mới</a></li>
                <li><a href="theloai.php" style="color: #e50914;">Thể Loại</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2><i class="fas fa-film" style="color:#e50914; margin-right:10px;"></i>Duyệt phim theo thể loại</h2>
        
        <!-- Bộ lọc thể loại -->
        <div class="category-filter">
            <a href="theloai.php" class="cat-btn <?= ($id_tl == 0) ? 'active' : '' ?>">Tất cả</a>
            <?php while($tl = mysqli_fetch_assoc($query_theloai)): ?>
                <a href="theloai.php?id=<?= $tl['id'] ?>" 
                   class="cat-btn <?= ($id_tl == $tl['id']) ? 'active' : '' ?>">
                   <?= htmlspecialchars($tl['ten_the_loai']) ?>
                </a>
            <?php endwhile; ?>
        </div>

        <h2><?= $ten_tl_dang_chon ?></h2>
        
        <div class="movie-grid">
            <?php if(mysqli_num_rows($query_phim) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($query_phim)): ?>
                    <?php $hinh = !empty($row['hinh_anh']) ? "images/".$row['hinh_anh'] : "images/default-movie.jpg"; ?>
                    <a href="xemphim.php?id=<?= $row['id'] ?>" class="movie-card">
                        <img src="<?= $hinh ?>" alt="<?= htmlspecialchars($row['ten_phim']) ?>">
                        <h3 class="movie-title"><?= htmlspecialchars($row['ten_phim']) ?></h3>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-movie">
                    <i class="fas fa-box-open" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                    Không tìm thấy phim nào thuộc thể loại này.
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer đơn giản -->
    <footer style="text-align:center; padding:30px; color:#666; border-top:1px solid #222; margin-top:50px;">
        <p>CHÀO MỪNG ĐẾN VỚI WebPhim TRỰC TUYẾN &copy; 2026</p>
    </footer>
</body>
</html>