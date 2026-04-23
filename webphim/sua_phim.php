<?php
session_start();
require_once 'ket_noi.php';

// Kiểm tra quyền Admin
if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] != 'quan_tri') {
    header("Location: index.php");
    exit();
}

$thong_bao = "";
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Lấy thông tin phim hiện tại
$sql_phim = "SELECT * FROM phim WHERE id = $id";
$result_phim = mysqli_query($conn, $sql_phim);
$phim = mysqli_fetch_assoc($result_phim);

if (!$phim) {
    die("Phim không tồn tại!");
}

// Lấy danh sách ID các thể loại mà phim này đang có
$sql_tl_phim = "SELECT id_the_loai FROM phim_the_loai WHERE id_phim = $id";
$query_tl_phim = mysqli_query($conn, $sql_tl_phim);
$current_genres = [];
while ($row_tl = mysqli_fetch_assoc($query_tl_phim)) {
    $current_genres[] = $row_tl['id_the_loai'];
}

// Lấy danh sách TẤT CẢ thể loại để in ra checkbox
$sql_the_loai = "SELECT * FROM the_loai ORDER BY ten_the_loai";
$query_the_loai = mysqli_query($conn, $sql_the_loai);

// Xử lý khi nhấn nút cập nhật
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten_phim = mysqli_real_escape_string($conn, $_POST['ten_phim']);
    $nam_phat_hanh = (int)$_POST['nam_phat_hanh'];
    $mo_ta = mysqli_real_escape_string($conn, $_POST['mo_ta']);
    $link_phim = isset($_POST['link_phim']) ? mysqli_real_escape_string($conn, $_POST['link_phim']) : '';
    
    // Nhận mảng các thể loại được đánh dấu (tick)
    $id_the_loai_arr = isset($_POST['id_the_loai']) ? $_POST['id_the_loai'] : [];

    if (empty($id_the_loai_arr)) {
        $thong_bao = "Lỗi: Vui lòng chọn ít nhất 1 thể loại!";
    } else {
        // Xử lý cập nhật ảnh (nếu có chọn ảnh mới)
        $hinh_anh = $phim['hinh_anh']; // Mặc định giữ ảnh cũ
        if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == 0) {
            $hinh_anh_moi = $_FILES['hinh_anh']['name'];
            $target = "images/" . basename($hinh_anh_moi);
            if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $target)) {
                $hinh_anh = $hinh_anh_moi;
            }
        }

        // Cập nhật thông tin cơ bản của phim
        $sql_update = "UPDATE phim SET 
            ten_phim = '$ten_phim', 
            nam_phat_hanh = $nam_phat_hanh, 
            mo_ta = '$mo_ta', 
            link_phim = '$link_phim', 
            hinh_anh = '$hinh_anh' 
            WHERE id = $id";

        if (mysqli_query($conn, $sql_update)) {
            // Cập nhật thể loại: Xóa các thể loại cũ đi
            mysqli_query($conn, "DELETE FROM phim_the_loai WHERE id_phim = $id");
            
            // Thêm các thể loại mới được tick
            foreach ($id_the_loai_arr as $id_tl) {
                $tl = (int)$id_tl;
                mysqli_query($conn, "INSERT INTO phim_the_loai (id_phim, id_the_loai) VALUES ($id, $tl)");
            }
            
            header("Location: quanlyphim.php");
            exit();
        } else {
            $thong_bao = "Lỗi khi cập nhật phim: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Phim | FilmLane</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #e50914; --bg: #000; --card: #1f1f1f; --text: #fff; --gray: #b3b3b3; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); padding: 40px; display: flex; justify-content: center; }
        .form-container { background: var(--card); padding: 40px; border-radius: 12px; width: 100%; max-width: 600px; border: 1px solid #333; }
        h2 { color: var(--primary); text-align: center; margin-top: 0; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: var(--gray); font-weight: bold; }
        .form-group input[type="text"], .form-group input[type="number"], .form-group textarea { width: 100%; padding: 12px; background: #2b2b2b; border: 1px solid #444; border-radius: 6px; color: white; box-sizing: border-box; }
        .form-group textarea { height: 100px; resize: vertical; }
        .btn-submit { background: var(--primary); color: white; padding: 12px 20px; width: 100%; border: none; border-radius: 6px; font-weight: bold; font-size: 16px; cursor: pointer; margin-top: 10px; }
        .back-link { display: block; text-align: center; margin-top: 20px; color: var(--gray); text-decoration: none; }
        .back-link:hover { color: white; }
        .alert { background: #7f1d1d; color: #fca5a5; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #ef4444; text-align: center; }
        
        /* CSS cho danh sách thể loại nhiều lựa chọn */
        .checkbox-container { display: flex; flex-wrap: wrap; gap: 10px; background: #2b2b2b; padding: 15px; border-radius: 6px; border: 1px solid #444; max-height: 150px; overflow-y: auto; }
        .checkbox-item { display: flex; align-items: center; gap: 8px; cursor: pointer; color: white; font-size: 14px; width: calc(50% - 10px); }
        .checkbox-item input { width: 16px; height: 16px; margin: 0; cursor: pointer; accent-color: var(--primary); }
        
        /* CSS cho ảnh poster hiện tại */
        .current-poster { display: flex; align-items: center; gap: 15px; margin-bottom: 10px; }
        .current-poster img { width: 60px; height: 85px; object-fit: cover; border-radius: 4px; border: 1px solid #444; }
        .hint { font-size: 12px; color: #888; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2><i class="fas fa-edit"></i> Sửa Phim</h2>
        <?php if($thong_bao != ""): ?>
            <div class="alert"><?= $thong_bao ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Tên phim</label>
                <input type="text" name="ten_phim" required value="<?= htmlspecialchars($phim['ten_phim']) ?>">
            </div>
            
            <div class="form-group">
                <label>Năm phát hành</label>
                <input type="number" name="nam_phat_hanh" required value="<?= $phim['nam_phat_hanh'] ?>">
            </div>

            <div class="form-group">
                <label>Thể loại (Có thể chọn nhiều)</label>
                <div class="checkbox-container">
                    <?php while($row = mysqli_fetch_assoc($query_the_loai)): ?>
                        <label class="checkbox-item">
                            <input type="checkbox" name="id_the_loai[]" value="<?= $row['id'] ?>" 
                                <?= in_array($row['id'], $current_genres) ? 'checked' : '' ?>> 
                            <?= htmlspecialchars($row['ten_the_loai']) ?>
                        </label>
                    <?php endwhile; ?>
                </div>
            </div>

            <div class="form-group">
                <label>Hình ảnh Poster</label>
                <div class="current-poster">
                    <?php $poster_path = !empty($phim['hinh_anh']) ? "images/" . $phim['hinh_anh'] : "images/default-movie.jpg"; ?>
                    <img src="<?= htmlspecialchars($poster_path) ?>" alt="Poster hiện tại">
                    <span style="color: #aaa;">Đang dùng: <?= htmlspecialchars($phim['hinh_anh']) ?></span>
                </div>
                <input type="file" name="hinh_anh" accept="image/*" style="color: white;">
                <div class="hint">Để trống nếu bạn không muốn đổi ảnh mới.</div>
            </div>

            <div class="form-group">
                <label>Link phim (URL hoặc mã nhúng)</label>
                <input type="text" name="link_phim" value="<?= htmlspecialchars(isset($phim['link_phim']) ? $phim['link_phim'] : '') ?>">
            </div>

            <div class="form-group">
                <label>Mô tả ngắn</label>
                <textarea name="mo_ta"><?= htmlspecialchars($phim['mo_ta']) ?></textarea>
            </div>

            <button type="submit" class="btn-submit">CẬP NHẬT PHIM</button>
            <a href="quanlyphim.php" class="back-link"><i class="fas fa-arrow-left"></i> Quay lại danh sách</a>
        </form>
    </div>
</body>
</html>