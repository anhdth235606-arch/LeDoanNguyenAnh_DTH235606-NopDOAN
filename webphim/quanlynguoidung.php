<?php
session_start();
require_once 'ket_noi.php';

// 1. Kiểm tra quyền Admin
if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] != 'quan_tri') {
    header("Location: index.php");
    exit();
}

// 2. Xử lý tìm kiếm
$tu_khoa = isset($_GET['search']) ? $_GET['search'] : "";
$sql = "SELECT * FROM nguoi_dung 
        WHERE ho_ten LIKE '%$tu_khoa%' OR ten_dang_nhap LIKE '%$tu_khoa%' 
        ORDER BY id DESC";
$query = mysqli_query($conn, $sql);

// 3. Thống kê nhanh
$total_users = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM nguoi_dung"));
$total_admins = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM nguoi_dung WHERE vai_tro = 'quan_tri'"));
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý người dùng | FilmLane Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #e50914;
            --bg-dark: #141414;
            --bg-card: #1f1f1f;
            --text-white: #ffffff;
            --text-gray: #b3b3b3;
        }

        body { font-family: 'Segoe UI', sans-serif; background-color: #000; color: var(--text-white); margin: 0; display: flex; }
        
        /* Sidebar */
        .sidebar { width: 260px; background-color: var(--bg-dark); height: 100vh; position: fixed; padding-top: 30px; border-right: 1px solid #333; }
        .sidebar h2 { color: var(--primary-color); text-align: center; margin-bottom: 40px; letter-spacing: 1px; }
        .sidebar a { display: block; color: var(--text-gray); padding: 15px 25px; text-decoration: none; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background-color: rgba(229, 9, 20, 0.1); color: var(--primary-color); border-left: 4px solid var(--primary-color); }

        /* Main Content */
        .main-content { margin-left: 260px; padding: 40px; width: calc(100% - 260px); }
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
        /* Search Box */
        .search-box { position: relative; width: 300px; }
        .search-box input { width: 100%; background: #222; border: 1px solid #444; padding: 10px 15px; border-radius: 25px; color: white; outline: none; }
        .search-box button { position: absolute; right: 10px; top: 8px; background: none; border: none; color: var(--text-gray); cursor: pointer; }

        /* Stats Cards */
        .stats-container { display: flex; gap: 20px; margin-bottom: 30px; }
        .stat-card { background: var(--bg-card); padding: 20px; border-radius: 12px; flex: 1; display: flex; align-items: center; gap: 15px; border: 1px solid #333; }
        .stat-card i { font-size: 30px; color: var(--primary-color); }
        .stat-info h3 { margin: 0; font-size: 24px; }
        .stat-info p { margin: 0; color: var(--text-gray); font-size: 14px; }

        /* Table Design */
        .table-container { background: var(--bg-card); border-radius: 12px; overflow: hidden; border: 1px solid #333; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background: #2a2a2a; color: var(--text-gray); padding: 15px 20px; font-size: 13px; text-transform: uppercase; }
        td { padding: 15px 20px; border-bottom: 1px solid #333; }
        tr:hover { background: rgba(255,255,255,0.02); }

        /* Badge & Buttons */
        .badge { padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .badge-admin { background: rgba(229, 9, 20, 0.2); color: #ff4d4d; }
        .badge-user { background: rgba(255, 255, 255, 0.1); color: #ccc; }

        .btn-action { color: var(--text-gray); text-decoration: none; margin-right: 10px; transition: 0.2s; }
        .btn-action:hover { color: var(--primary-color); }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>FILMLANE</h2>
       <a href="bangdieukhien.php"><i class="fas fa-tachometer-alt"></i> Bảng điều khiển</a>
        <a href="admin.php"><i class="fas fa-home"></i> Trang Chủ Admin</a>
        <a href="quanlyphim.php" ><i class="fas fa-film"></i> Quản lý phim</a>
        <a href="quanlytheloai.php"><i class="fas fa-list"></i> Quản lý thể loại</a>
        <a href="quanlynguoidung.php"class="active"><i class="fas fa-users"></i> Quản lý người dùng</a>
        <a href="index.php"><i class="fas fa-external-link-alt"></i> Xem trang chủ</a>
    </div>

    <div class="main-content">
        <div class="header-flex">
            <h1>Quản lý người dùng</h1>
            <div class="search-box">
                <form method="GET">
                    <input type="text" name="search" placeholder="Tìm tên hoặc tài khoản..." value="<?= $tu_khoa ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <div class="stat-info">
                    <h3><?= $total_users ?></h3>
                    <p>Tổng thành viên</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="fas fa-user-shield"></i>
                <div class="stat-info">
                    <h3><?= $total_admins ?></h3>
                    <p>Quản trị viên</p>
                </div>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Thông tin thành viên</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td>#<?= $row['id'] ?></td>
                        <td>
                            <div style="font-weight: bold;"><?= $row['ho_ten'] ?></div>
                            <div style="font-size: 12px; color: var(--text-gray);">@<?= $row['ten_dang_nhap'] ?></div>
                        </td>
                        <td><?= $row['email'] ?></td>
                        <td>
                            <?php if ($row['vai_tro'] == 'quan_tri'): ?>
                                <span class="badge badge-admin">Quản trị viên</span>
                            <?php else: ?>
                                <span class="badge badge-user">Thành viên</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="sua_nguoidung.php?id=<?= $row['id'] ?>" class="btn-action" title="Sửa"><i class="fas fa-edit"></i></a>
                            
                            <?php if ($row['id'] != $_SESSION['user_id']): ?>
                            <a href="xoanguoidung.php?id=<?= $row['id'] ?>" class="btn-action" title="Xóa" 
                               onclick="return confirm('Bạn có chắc muốn xóa người dùng này?')">
                                <i class="fas fa-trash"></i>
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>