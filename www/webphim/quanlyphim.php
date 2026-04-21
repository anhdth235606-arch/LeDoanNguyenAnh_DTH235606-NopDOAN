<?php
session_start();
require_once 'ket_noi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] != 'quan_tri') {
    header("Location: index.php");
    exit();
}

$tu_khoa = "";
if (isset($_GET['search'])) {
    $tu_khoa = $_GET['search'];
    $sql = "SELECT phim.*, the_loai.ten_the_loai
            FROM phim
            LEFT JOIN the_loai ON phim.id_the_loai = the_loai.id
            WHERE ten_phim LIKE '%$tu_khoa%'";
} else {
    $sql = "SELECT phim.*, the_loai.ten_the_loai
            FROM phim
            LEFT JOIN the_loai ON phim.id_the_loai = the_loai.id";
}
$query = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quan ly phim</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #333; color: white; }
        .btn { padding: 5px 10px; text-decoration: none; border-radius: 3px; color: white; }
        .btn-edit { background: orange; }
        .btn-delete { background: red; }
        .search-box { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h2>DANH SACH PHIM</h2>

    <div class="search-box">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Nhap ten phim..." value="<?php echo $tu_khoa; ?>">
            <button type="submit">Tim kiem</button>
            <a href="quanlyphim.php">Tat ca</a>
        </form>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Ten phim</th>
            <th>The loai</th>
            <th>Nam</th>
            <th>Thao tac</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($query)): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['ten_phim']; ?></td>
            <td><?php echo $row['ten_the_loai']; ?></td>
            <td><?php echo $row['nam_phat_hanh']; ?></td>
            <td>
                <a href="sua_phim.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Sua</a>
                <a href="xoaphim.php?id=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Ban co chac muon xoa phim nay?')">Xoa</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <a href="admin.php">Quay lai Bang dieu khien</a>
</body>
</html>
