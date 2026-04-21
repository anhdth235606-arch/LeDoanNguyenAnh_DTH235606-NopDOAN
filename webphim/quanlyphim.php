<?php
session_start();
require_once 'ket_noi.php';

// Bảo mật: Chỉ admin mới được vào
if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] != 'quan_tri') {
    header("Location: index.php");
    exit();
}

// Xử lý Tìm kiếm
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
    <title>Quản lý phim</title>
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
    <h2>DANH SÁCH PHIM</h2>
    
    <div class="search-box">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Nhập tên phim..." value="<?php echo $tu_khoa; ?>">
            <button type="submit">Tìm kiếm</button>
            <a href="quan_ly_phim.php">Tất cả</a>
        </form>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Tên phim</th>
            <th>Thể loại</th>
            <th>Năm</th>
            <th>Thao tác</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($query)): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['ten_phim']; ?></td>
            <td><?php echo $row['ten_the_loai']; ?></td>
            <td><?php echo $row['nam_phat_hanh']; ?></td>
            <td>
                <a href="sua_phim.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Sửa</a>
                <a href="xoa_phim.php?id=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Bạn có chắc muốn xóa phim này?')">Xóa</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <a href="admin.php"> Quay lại Bảng điều khiển</a>
</body>
</html>