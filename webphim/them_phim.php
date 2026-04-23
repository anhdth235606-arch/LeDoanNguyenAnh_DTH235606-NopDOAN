<?php
session_start();
require_once 'ket_noi.php';

// Kiểm tra quyền Admin
if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] != 'quan_tri') {
    header("Location: index.php");
    exit();
}

$thong_bao = "";

// Xử lý khi người dùng nhấn nút "Thêm phim"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten_phim = mysqli_real_escape_string($conn, $_POST['ten_phim']);
    $nam_phat_hanh = (int)$_POST['nam_phat_hanh'];
    $mo_ta = mysqli_real_escape_string($conn, $_POST['mo_ta']);

    // Nhận mảng các thể loại được đánh dấu (tick)
    $id_the_loai_arr = isset($_POST['id_the_loai']) ? $_POST['id_the_loai'] : [];

    // Kiểm tra xem đã chọn ít nhất 1 thể loại chưa
    if (empty($id_the_loai_arr)) {
        $thong_bao = "Lỗi: Vui lòng chọn ít nhất 1 thể loại!";
    } else {
        // Xử lý tải ảnh lên
        $hinh_anh = $_FILES['hinh_anh']['name'];
        $target = "images/" . basename($hinh_anh);

        if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $target)) {
            // Câu lệnh SQL CŨ (Đã bỏ id_the_loai)
            $sql = "INSERT INTO phim (ten_phim, nam_phat_hanh, hinh_anh, mo_ta) 
                    VALUES ('$ten_phim', $nam_phat_hanh, '$hinh_anh', '$mo_ta')";
            
            if (mysqli_query($conn, $sql)) {
                // Lấy ID của bộ phim vừa được thêm vào
                $new_id = mysqli_insert_id($conn); 
                
                // Vòng lặp để lưu từng thể loại vào bảng trung gian phim_the_loai
                foreach ($id_the_loai_arr as $id_tl) {
                    $tl = (int)$id_tl;
                    mysqli_query($conn, "INSERT INTO phim_the_loai (id_phim, id_the_loai) VALUES ($new_id, $tl)");
                }

                $thong_bao = "Thêm phim mới và gán thể loại thành công!";
            } else {
                $thong_bao = "Lỗi Database: " . mysqli_error($conn);
            }
        } else {
            $thong_bao = "Lỗi khi tải ảnh lên!";
        }
    }
}

// Lấy danh sách thể loại để hiện dạng Checkbox
$sql_the_loai = "SELECT * FROM the_loai";
$query_the_loai = mysqli_query($conn, $sql_the_loai);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm phim mới | FilmLane Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #e50914; --bg: #000; --card: #1f1f1f; --text: #fff; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
        
        .form-container { background: var(--card); padding: 40px; border-radius: 12px; width: 100%; max-width: 600px; border: 1px solid #333; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        h2 { color: var(--primary); text-align: center; margin-bottom: 30px; }
        
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #b3b3b3; font-size: 14px; }
        input, select, textarea { width: 100%; padding: 12px; background: #2b2b2b; border: 1px solid #444; border-radius: 6px; color: white; box-sizing: border-box; outline: none; }
        input:focus, textarea:focus { border-color: var(--primary); }
        
        textarea { height: 100px; resize: none; }
        
        .btn-submit { width: 100%; padding: 15px; background: var(--primary); border: none; border-radius: 6px; color: white; font-weight: bold; cursor: pointer; font-size: 16px; transition: 0.3s; margin-top: 10px; }
        .btn-submit:hover { background: #ff0a16; transform: translateY(-2px); }
        
        .back-link { display: block; text-align: center; margin-top: 20px; color: #aaa; text-decoration: none; }
        .alert { background: rgba(46, 204, 113, 0.2); color: #2ecc71; padding: 15px; border-radius: 6px; text-align: center; margin-bottom: 20px; border: 1px solid #2ecc71; }
        
        /* CSS cho vùng chọn Thể loại */
        .checkbox-container { display: flex; flex-wrap: wrap; gap: 15px; background: #2b2b2b; padding: 15px; border-radius: 6px; border: 1px solid #444; max-height: 150px; overflow-y: auto; }
        .checkbox-item { display: flex; align-items: center; gap: 8px; cursor: pointer; color: white; font-size: 14px; margin-bottom: 0; min-width: 30%; }
        .checkbox-item input { width: 16px; height: 16px; margin: 0; cursor: pointer; }
    </style>
</head>
<body>

    <div class="form-container">
        <h2><i class="fas fa-plus-circle"></i> Thêm phim mới</h2>

        <?php if ($thong_bao != ""): ?>
            <div class="alert" style="<?php if(strpos($thong_bao, 'Lỗi') !== false) echo 'background: rgba(229,9,20,0.2); color: #e50914; border-color: #e50914;'; ?>"><?php echo $thong_bao; ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            
            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 2;">
                    <label>Tên phim</label>
                    <input type="text" name="ten_phim" required placeholder="Nhập tên phim...">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Năm phát hành</label>
                    <input type="number" name="nam_phat_hanh" placeholder="VD: 2024" required>
                </div>
            </div>

            <div class="form-group">
                <label>Thể loại (Có thể chọn nhiều)</label>
                <div class="checkbox-container">
                    <?php while($row = mysqli_fetch_assoc($query_the_loai)): ?>
                        <label class="checkbox-item">
                            <input type="checkbox" name="id_the_loai[]" value="<?= $row['id'] ?>"> 
                            <?= htmlspecialchars($row['ten_the_loai']) ?>
                        </label>
                    <?php endwhile; ?>
                </div>
            </div>

            <div class="form-group">
                <label>Hình ảnh Poster (Chọn file ảnh)</label>
                <input type="file" name="hinh_anh" accept="image/*" required>
            </div>

            <div class="form-group">
                <label>Mô tả ngắn</label>
                <textarea name="mo_ta" placeholder="Nhập tóm tắt nội dung phim..."></textarea>
            </div>

            <button type="submit" class="btn-submit">XÁC NHẬN THÊM</button>
            <a href="quanlyphim.php" class="back-link"><i class="fas fa-arrow-left"></i> Quay lại danh sách</a>
        </form>
    </div>

</body>
</html>