<?php
session_start();
require_once 'ket_noi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] != 'quan_tri') { 
    header("Location: index.php"); 
    exit(); 
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$thong_bao = "";

// Lấy thông tin tập hiện tại
$sql_get = "SELECT * FROM tap_phim WHERE id = $id";
$res = mysqli_query($conn, $sql_get);
$tap = mysqli_fetch_assoc($res);
if (!$tap) die("Không tìm thấy tập.");

// Lấy danh sách phim
$sql_phim = "SELECT id, ten_phim FROM phim ORDER BY ten_phim";
$ds_phim = mysqli_query($conn, $sql_phim);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_phim = (int)$_POST['id_phim'];
    $ten_tap = mysqli_real_escape_string($conn, $_POST['ten_tap']);
    $so_thu_tu = (int)$_POST['so_thu_tu'];
    $link_phim = mysqli_real_escape_string($conn, $_POST['link_phim']);
    $mo_ta = mysqli_real_escape_string($conn, $_POST['mo_ta']);

    $sql_update = "UPDATE tap_phim SET 
                   id_phim=$id_phim, ten_tap='$ten_tap', so_thu_tu=$so_thu_tu, link_phim='$link_phim', mo_ta='$mo_ta' 
                   WHERE id=$id";
    if (mysqli_query($conn, $sql_update)) {
        header("Location: quanlytap.php?id_phim=$id_phim");
        exit();
    } else {
        $thong_bao = "Lỗi cập nhật: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Tập Phim | FilmLane</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #e50914; --bg: #000; --card: #1f1f1f; --text: #fff; --gray: #b3b3b3; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); padding: 40px; display: flex; justify-content: center; }
        .form-container { background: var(--card); padding: 40px; border-radius: 12px; width: 100%; max-width: 600px; border: 1px solid #333; }
        h2 { color: var(--primary); text-align: center; margin-top: 0; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: var(--gray); font-weight: bold; }
        .form-group input[type="text"], .form-group input[type="number"], .form-group select, .form-group textarea { width: 100%; padding: 12px; background: #2b2b2b; border: 1px solid #444; border-radius: 6px; color: white; box-sizing: border-box; }
        .form-group textarea { height: 100px; resize: vertical; }
        .btn-submit { background: var(--primary); color: white; padding: 12px 20px; width: 100%; border: none; border-radius: 6px; font-weight: bold; font-size: 16px; cursor: pointer; margin-top: 10px; }
        .back-link { display: block; text-align: center; margin-top: 20px; color: var(--gray); text-decoration: none; }
        .back-link:hover { color: white; }
        .alert { background: #7f1d1d; color: #fca5a5; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #ef4444; text-align: center; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2><i class="fas fa-edit"></i> Sửa Tập Phim</h2>
        <?php if($thong_bao != ""): ?>
            <div class="alert"><?= $thong_bao ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Chọn phim</label>
                <select name="id_phim" required>
                    <option value="">-- Chọn phim --</option>
                    <?php while($p = mysqli_fetch_assoc($ds_phim)): ?>
                        <option value="<?= $p['id'] ?>" <?= ($p['id'] == $tap['id_phim']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['ten_phim']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div style="display: flex; gap: 15px;">
                <div class="form-group" style="flex: 2;">
                    <label>Tên tập</label>
                    <input type="text" name="ten_tap" required value="<?= htmlspecialchars($tap['ten_tap']) ?>">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Số thứ tự</label>
                    <input type="number" name="so_thu_tu" min="1" required value="<?= $tap['so_thu_tu'] ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Link phim (URL hoặc mã nhúng)</label>
                <input type="text" name="link_phim" required value="<?= htmlspecialchars($tap['link_phim']) ?>">
            </div>

            <div class="form-group">
                <label>Mô tả tập (tùy chọn)</label>
                <textarea name="mo_ta" rows="3"><?= htmlspecialchars($tap['mo_ta']) ?></textarea>
            </div>

            <button type="submit" class="btn-submit">CẬP NHẬT TẬP PHIM</button>
            <a href="quanlytap.php" class="back-link"><i class="fas fa-arrow-left"></i> Quay lại quản lý tập</a>
        </form>
    </div>
</body>
</html>