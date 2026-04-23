<?php
session_start();
require_once 'ket_noi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] != 'quan_tri') {
    header("Location: index.php");
    exit();
}

// Lấy danh sách phim để làm dropdown lọc
$sql_phim = "SELECT id, ten_phim FROM phim ORDER BY ten_phim";
$ds_phim = mysqli_query($conn, $sql_phim);

// Xử lý lọc theo phim
$id_phim_loc = isset($_GET['id_phim']) ? (int)$_GET['id_phim'] : 0;
$where = $id_phim_loc > 0 ? "WHERE tap_phim.id_phim = $id_phim_loc" : "";

$sql = "SELECT tap_phim.*, phim.ten_phim 
        FROM tap_phim 
        JOIN phim ON tap_phim.id_phim = phim.id 
        $where 
        ORDER BY phim.ten_phim, tap_phim.so_thu_tu ASC";
$query = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý tập phim | FilmLane</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #e50914; --bg: #000; --card: #1f1f1f; --text: #fff; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); margin: 0; display: flex; }
        .sidebar { width: 260px; background: #141414; height: 100vh; position: fixed; padding-top: 30px; border-right: 1px solid #333; }
        .sidebar h2 { color: var(--primary); text-align: center; margin-bottom: 40px; }
        .sidebar a { display: block; color: #b3b3b3; padding: 15px 25px; text-decoration: none; }
        .sidebar a:hover, .sidebar a.active { background: rgba(229,9,20,0.1); color: var(--primary); border-left: 4px solid var(--primary); }
        .main { margin-left: 260px; padding: 40px; width: calc(100% - 260px); }
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px; }
        .filter-box { display: flex; gap: 10px; align-items: center; }
        .filter-box select { padding: 10px; background: #222; border: 1px solid #444; color: white; border-radius: 6px; }
        .btn-add { background: var(--primary); color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; }
        .table-container { background: var(--card); border-radius: 12px; border: 1px solid #333; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #2a2a2a; padding: 15px; text-align: left; color: #b3b3b3; }
        td { padding: 15px; border-bottom: 1px solid #333; }
        .action-icons a { color: #b3b3b3; margin-right: 12px; transition: 0.2s; }
        .action-icons a:hover { color: var(--primary); }
        .badge { background: #333; padding: 4px 10px; border-radius: 20px; font-size: 13px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>FILMLANE</h2>
        <a href="bangdieukhien.php"><i class="fas fa-tachometer-alt"></i> Bảng điều khiển</a>
        <a href="quanlyphim.php"><i class="fas fa-film"></i> Quản lý phim</a>
        <a href="quanlytheloai.php"><i class="fas fa-list"></i> Quản lý thể loại</a>
        <a href="quanlynguoidung.php"><i class="fas fa-users"></i> Quản lý người dùng</a>
        <a href="quanlytap.php" class="active"><i class="fas fa-list-ol"></i> Quản lý tập phim</a>
        <a href="index.php"><i class="fas fa-external-link-alt"></i> Xem trang chủ</a>
    </div>

    <div class="main">
        <div class="header-flex">
            <h1>Danh sách tập phim</h1>
            <div class="filter-box">
                <form method="GET" style="display: flex; gap: 10px;">
                    <select name="id_phim" onchange="this.form.submit()">
                        <option value="0">-- Tất cả phim --</option>
                        <?php while($p = mysqli_fetch_assoc($ds_phim)): ?>
                            <option value="<?= $p['id'] ?>" <?= $id_phim_loc == $p['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['ten_phim']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </form>
                <a href="them_tap.php" class="btn-add"><i class="fas fa-plus"></i> Thêm tập mới</a>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Phim</th>
                        <th>Tên tập</th>
                        <th>Thứ tự</th>
                        <th>Link</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td>#<?= $row['id'] ?></td>
                        <td><strong><?= htmlspecialchars($row['ten_phim']) ?></strong></td>
                        <td><?= htmlspecialchars($row['ten_tap']) ?></td>
                        <td><span class="badge">Tập <?= $row['so_thu_tu'] ?></span></td>
                        <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <?= htmlspecialchars(substr($row['link_phim'], 0, 30)) ?>...
                        </td>
                        <td class="action-icons">
                            <a href="sua_tap.php?id=<?= $row['id'] ?>" title="Sửa"><i class="fas fa-edit"></i></a>
                            <a href="xoa_tap.php?id=<?= $row['id'] ?>" title="Xóa" onclick="return confirm('Xóa tập này?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>