<?php
session_start();
require_once 'ket_noi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] != 'quan_tri') {
    header("Location: index.php");
    exit();
}

// Xử lý tìm kiếm và LẤY NHIỀU THỂ LOẠI
$tu_khoa = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : "";
$sql = "SELECT phim.*, GROUP_CONCAT(the_loai.ten_the_loai SEPARATOR ', ') as ten_the_loai 
        FROM phim 
        LEFT JOIN phim_the_loai ON phim.id = phim_the_loai.id_phim 
        LEFT JOIN the_loai ON phim_the_loai.id_the_loai = the_loai.id 
        WHERE phim.ten_phim LIKE '%$tu_khoa%' 
        GROUP BY phim.id 
        ORDER BY phim.id DESC";
$query = mysqli_query($conn, $sql);

// Thống kê nhanh
$total_movies = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM phim"));
$total_genres = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM the_loai"));
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý phim | FilmLane</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #e50914; --bg: #000; --card: #1f1f1f; --text: #fff; --gray: #b3b3b3; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); margin: 0; display: flex; }
        
        /* Sidebar */
        .sidebar { width: 260px; background: #141414; height: 100vh; position: fixed; padding-top: 30px; border-right: 1px solid #333; }
        .sidebar h2 { color: var(--primary); text-align: center; margin-bottom: 40px; letter-spacing: 2px; }
        .sidebar a { display: block; color: var(--gray); padding: 15px 25px; text-decoration: none; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: rgba(229, 9, 20, 0.1); color: var(--primary); border-left: 4px solid var(--primary); }

        /* Main Content */
        .main { margin-left: 260px; padding: 40px; width: calc(100% - 260px); }
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
        /* Search */
        .search-box { position: relative; width: 300px; }
        .search-box input { width: 100%; background: #222; border: 1px solid #444; padding: 10px 15px; border-radius: 25px; color: white; outline: none; }
        .search-box button { position: absolute; right: 10px; top: 8px; background: none; border: none; color: var(--gray); cursor: pointer; }

        /* Stats */
        .stats { display: flex; gap: 20px; margin-bottom: 30px; }
        .stat-card { background: var(--card); padding: 20px; border-radius: 12px; flex: 1; display: flex; align-items: center; gap: 15px; border: 1px solid #333; }
        .stat-card i { font-size: 30px; color: var(--primary); }
        .stat-info h3 { margin: 0; font-size: 24px; }
        .stat-info p { margin: 0; color: var(--gray); font-size: 14px; }

        /* Table */
        .table-container { background: var(--card); border-radius: 12px; border: 1px solid #333; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background: #2a2a2a; padding: 15px; color: var(--gray); font-size: 13px; text-transform: uppercase; }
        td { padding: 15px; border-bottom: 1px solid #333; }
        tr:hover { background: rgba(255,255,255,0.02); }

        .movie-thumb { width: 50px; height: 70px; object-fit: cover; border-radius: 4px; }
        .badge { background: rgba(255,255,255,0.1); padding: 4px 10px; border-radius: 4px; font-size: 12px; line-height: 1.5; display: inline-block; }
        
        .btn-add { background: var(--primary); color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .action-icons a { color: var(--gray); margin-right: 10px; transition: 0.2s; }
        .action-icons a:hover { color: var(--primary); }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>FILMLANE</h2>
        <a href="bangdieukhien.php"><i class="fas fa-tachometer-alt"></i> Bảng điều khiển</a>
        <a href="admin.php"><i class="fas fa-home"></i> Trang Chủ Admin</a>
        <a href="quanlyphim.php" class="active"><i class="fas fa-film"></i> Quản lý phim</a>
        <a href="quanlytheloai.php"><i class="fas fa-list"></i> Quản lý thể loại</a>
        <a href="quanlynguoidung.php"><i class="fas fa-users"></i> Quản lý người dùng</a>
         <a href="quanlytap.php"><i class="fas fa-list-ol"></i> Quản lý tập phim</a>
        <a href="index.php"><i class="fas fa-external-link-alt"></i> Xem trang chủ</a>
    </div>

    <div class="main">
        <div class="header-flex">
            <h1>Danh sách phim</h1>
            <div class="search-box">
                <form method="GET">
                    <input type="text" name="search" placeholder="Tìm tên phim..." value="<?= htmlspecialchars($tu_khoa) ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <a href="them_phim.php" class="btn-add"><i class="fas fa-plus"></i> Thêm phim</a>
        </div>

        <div class="stats">
            <div class="stat-card">
                <i class="fas fa-video"></i>
                <div class="stat-info"><h3><?= $total_movies ?></h3><p>Tổng số phim</p></div>
            </div>
            <div class="stat-card">
                <i class="fas fa-tags"></i>
                <div class="stat-info"><h3><?= $total_genres ?></h3><p>Thể loại</p></div>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Mã phim</th>
                        <th>Phim</th>
                        <th>Thể loại</th>
                        <th>Năm</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($query) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td><span class="badge" style="background: #333; font-weight: bold; letter-spacing: 1px;">PHIM-<?= sprintf('%03d', $row['id']) ?></span></td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <img src="images/<?= htmlspecialchars($row['hinh_anh']) ?>" class="movie-thumb">
                                    <strong><?= htmlspecialchars($row['ten_phim']) ?></strong>
                                </div>
                            </td>
                            <td><span class="badge"><?= !empty($row['ten_the_loai']) ? htmlspecialchars($row['ten_the_loai']) : 'Chưa phân loại' ?></span></td>
                            <td><?= $row['nam_phat_hanh'] ?></td>
                            <td class="action-icons">
                                <a href="sua_phim.php?id=<?= $row['id'] ?>" title="Sửa"><i class="fas fa-edit"></i></a>
                                <a href="xoaphim.php?id=<?= $row['id'] ?>" title="Xóa" onclick="return confirm('Xóa phim này?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align:center; padding:30px; color:#aaa;">Không tìm thấy phim nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>