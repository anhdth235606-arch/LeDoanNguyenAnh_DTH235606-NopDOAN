<?php
session_start();
require_once 'ket_noi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] != 'quan_tri') {
    header("Location: index.php");
    exit();
}

// Xử lý tìm kiếm
$tu_khoa = isset($_GET['search']) ? trim($_GET['search']) : "";
$sql = "SELECT * FROM the_loai WHERE ten_the_loai LIKE '%$tu_khoa%' ORDER BY id DESC";
$query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý thể loại | FilmLane</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #e50914; --bg: #000; --card: #1f1f1f; --text: #fff; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); margin: 0; display: flex; }
        
        .sidebar { width: 260px; background: #141414; height: 100vh; position: fixed; padding-top: 30px; border-right: 1px solid #333; }
        .sidebar h2 { color: var(--primary); text-align: center; margin-bottom: 40px; }
        .sidebar a { display: block; color: #b3b3b3; padding: 15px 25px; text-decoration: none; }
        .sidebar a:hover, .sidebar a.active { background: rgba(229, 9, 20, 0.1); color: var(--primary); border-left: 4px solid var(--primary); }

        .main { margin-left: 260px; padding: 40px; width: 100%; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px; }
        
        /* Ô tìm kiếm */
        .search-box {
            display: flex;
            align-items: center;
            background: #1f1f1f;
            border: 1px solid #444;
            border-radius: 30px;
            padding: 5px 10px 5px 20px;
        }
        .search-box input {
            background: transparent;
            border: none;
            color: white;
            padding: 10px 5px;
            outline: none;
            width: 250px;
            font-size: 14px;
        }
        .search-box button {
            background: var(--primary);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.2s;
        }
        .search-box button:hover {
            background: #ff0a16;
        }
        
        .btn-add { background: var(--primary); color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        
        .table-container { background: var(--card); border-radius: 12px; border: 1px solid #333; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #2a2a2a; padding: 15px; text-align: left; color: #b3b3b3; }
        td { padding: 15px; border-bottom: 1px solid #333; }
        
        .btn-edit { color: #3498db; margin-right: 15px; text-decoration: none; }
        .btn-delete { color: #e74c3c; text-decoration: none; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>FILMLANE</h2>
        <a href="bangdieukhien.php"><i class="fas fa-tachometer-alt"></i> Bảng điều khiển</a>
        <a href="quanlyphim.php"><i class="fas fa-film"></i> Quản lý phim</a>
        <a href="quanlytheloai.php" class="active"><i class="fas fa-list"></i> Quản lý thể loại</a>
        <a href="quanlynguoidung.php"><i class="fas fa-users"></i> Quản lý người dùng</a>
        <a href="quanlytap.php"><i class="fas fa-list-ol"></i> Quản lý tập phim</a> <!-- Thêm nếu cần -->
        <a href="index.php" style="margin-top: 20px;"><i class="fas fa-external-link-alt"></i> Xem trang chủ</a>
    </div>

    <div class="main">
        <div class="header">
            <h1>Quản lý thể loại</h1>
            <div style="display: flex; gap: 15px; align-items: center;">
                <!-- Form tìm kiếm -->
                <form method="GET" class="search-box">
                    <input type="text" name="search" placeholder="Tìm tên thể loại..." value="<?= htmlspecialchars($tu_khoa) ?>">
                    <button type="submit"><i class="fas fa-search"></i> Tìm</button>
                </form>
                <a href="them_theloai.php" class="btn-add"><i class="fas fa-plus"></i> Thêm thể loại mới</a>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên thể loại</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($query) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td>#<?= $row['id'] ?></td>
                            <td style="font-weight: bold;"><?= htmlspecialchars($row['ten_the_loai']) ?></td>
                            <td>
                                <a href="sua_theloai.php?id=<?= $row['id'] ?>" class="btn-edit"><i class="fas fa-edit"></i> Sửa</a>
                                <a href="xoa_theloai.php?id=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Xóa thể loại này sẽ ảnh hưởng đến các phim thuộc thể loại này. Tiếp tục?')"><i class="fas fa-trash"></i> Xóa</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 30px; color: #aaa;">
                                <i class="fas fa-search" style="margin-right: 8px;"></i> Không tìm thấy thể loại nào
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>