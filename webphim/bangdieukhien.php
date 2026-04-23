<?php
session_start();
require_once 'ket_noi.php';

// 1. Kiểm tra quyền Admin
if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] != 'quan_tri') {
    header("Location: index.php");
    exit();
}

// 2. Lấy dữ liệu thống kê tổng quan
$total_movies = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM phim"));
$total_genres = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM the_loai"));
$total_users = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM nguoi_dung"));

// 3. Lấy danh sách 5 phim mới nhất
$sql_latest_movies = "SELECT phim.*, the_loai.ten_the_loai 
                      FROM phim 
                      LEFT JOIN the_loai ON phim.id_the_loai = the_loai.id 
                      ORDER BY phim.id DESC LIMIT 5";
$query_latest_movies = mysqli_query($conn, $sql_latest_movies);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bảng điều khiển | FilmLane Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #e50914; --bg: #000; --card: #1f1f1f; --text: #fff; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); margin: 0; display: flex; }
        
        /* CSS Sidebar (Giống các trang khác) */
        .sidebar { width: 260px; background: #141414; height: 100vh; position: fixed; padding-top: 30px; border-right: 1px solid #333; }
        .sidebar h2 { color: var(--primary); text-align: center; margin-bottom: 30px; letter-spacing: 2px;}
        .sidebar a { display: block; padding: 15px 25px; color: #b3b3b3; text-decoration: none; font-size: 16px; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #333; color: white; border-left: 4px solid var(--primary); }
        .sidebar a i { margin-right: 10px; width: 20px; }
        
        /* CSS Main Content */
        .main { margin-left: 260px; padding: 30px; width: calc(100% - 260px); box-sizing: border-box;}
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header-user { font-size: 16px; color: #ccc; }
        .header-user strong { color: white; }

        /* CSS Grid cho Thống kê */
        .dashboard-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .card { background: var(--card); padding: 25px; border-radius: 10px; border: 1px solid #333; display: flex; align-items: center; transition: 0.3s;}
        .card:hover { transform: translateY(-5px); border-color: var(--primary); box-shadow: 0 5px 15px rgba(229, 9, 20, 0.2);}
        .card-icon { font-size: 40px; color: var(--primary); margin-right: 20px; }
        .card-info h3 { margin: 0; font-size: 28px; color: white; }
        .card-info p { margin: 5px 0 0 0; color: #b3b3b3; font-size: 15px; text-transform: uppercase; letter-spacing: 1px;}
        
        /* CSS Bảng */
        .table-container { background: var(--card); padding: 20px; border-radius: 10px; border: 1px solid #333; }
        .table-title { margin-top: 0; margin-bottom: 20px; font-size: 18px; border-bottom: 1px solid #333; padding-bottom: 10px;}
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #333; }
        th { color: #b3b3b3; text-transform: uppercase; font-size: 12px; }
        .movie-thumb { width: 40px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #444;}
        .badge { background: #333; padding: 5px 10px; border-radius: 20px; font-size: 12px; color: #ccc; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>CHÓ HIỂN</h2>
        <a href="admin.php"><i class="fas fa-home"></i> Trang Chủ Admin</a>
         <a href="bangdieukhien.php"class="active"><i class="fas fa-tachometer-alt"></i> Bảng điều khiển</a>
        <a href="quanlyphim.php" ><i class="fas fa-film"></i> Quản lý phim</a>
        <a href="quanlytheloai.php"><i class="fas fa-list"></i> Quản lý thể loại</a>
        <a href="quanlynguoidung.php"><i class="fas fa-users"></i> Quản lý người dùng</a>
        <a href="index.php"><i class="fas fa-external-link-alt"></i> Xem trang chủ</a>
        <a href="logout.php" style="margin-top: 50px; color: #e50914;"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
    </div>

    <div class="main">
        <div class="header">
            <h1>Bảng điều khiển</h1>
            <div class="header-user">
                Xin chào Quản trị viên, <strong><?= isset($_SESSION['ho_ten']) ? $_SESSION['ho_ten'] : 'Admin' ?></strong>
            </div>
        </div>

        <div class="dashboard-cards">
            <div class="card">
                <div class="card-icon"><i class="fas fa-film"></i></div>
                <div class="card-info">
                    <h3><?= $total_movies ?></h3>
                    <p>Tổng số phim</p>
                </div>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-tags"></i></div>
                <div class="card-info">
                    <h3><?= $total_genres ?></h3>
                    <p>Thể loại</p>
                </div>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-users"></i></div>
                <div class="card-info">
                    <h3><?= $total_users ?></h3>
                    <p>Thành viên</p>
                </div>
            </div>
        </div>

        <div class="table-container">
            <h2 class="table-title">Phim mới được thêm gần đây</h2>
            <table>
                <thead>
                    <tr>
                        <th>Phim</th>
                        <th>Thể loại</th>
                        <th>Năm</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($query_latest_movies) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($query_latest_movies)): ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <?php 
                                        // Kiểm tra xem có ảnh không, nếu không thì dùng ảnh mặc định
                                        $hinh_anh = !empty($row['hinh_anh']) ? "images/".$row['hinh_anh'] : "images/default-movie.jpg";
                                    ?>
                                    <img src="<?= $hinh_anh ?>" class="movie-thumb" alt="<?= $row['ten_phim'] ?>">
                                    <strong><?= $row['ten_phim'] ?></strong>
                                </div>
                            </td>
                            <td><span class="badge"><?= !empty($row['ten_the_loai']) ? $row['ten_the_loai'] : 'Chưa phân loại' ?></span></td>
                            <td><?= $row['nam_phat_hanh'] ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center; color: #888;">Chưa có phim nào trong cơ sở dữ liệu.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>