<?php
session_start();
require_once 'ket_noi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("Đường dẫn không hợp lệ!");
}

// 1. Cập nhật lượt xem mỗi lần truy cập
mysqli_query($conn, "UPDATE phim SET luot_xem = luot_xem + 1 WHERE id = $id");

// 2. Lấy thông tin phim (Đã fix lỗi hiển thị nhiều Thể loại)
$sql_phim = "SELECT phim.*, GROUP_CONCAT(the_loai.ten_the_loai SEPARATOR ', ') as ten_the_loai 
             FROM phim 
             LEFT JOIN phim_the_loai ON phim.id = phim_the_loai.id_phim 
             LEFT JOIN the_loai ON phim_the_loai.id_the_loai = the_loai.id 
             WHERE phim.id = $id 
             GROUP BY phim.id";
$query_phim = mysqli_query($conn, $sql_phim);
$phim = mysqli_fetch_assoc($query_phim);

if (!$phim) {
    die("Không tìm thấy bộ phim này!");
}

// 3. Lấy danh sách tập phim
$sql_tap = "SELECT * FROM tap_phim WHERE id_phim = $id ORDER BY so_thu_tu ASC";
$query_tap = mysqli_query($conn, $sql_tap);

$danh_sach_tap = [];
$tap_hien_tai = null;
$id_tap_dang_xem = isset($_GET['tap']) ? (int)$_GET['tap'] : 0;

while ($row = mysqli_fetch_assoc($query_tap)) {
    $danh_sach_tap[] = $row;
    // Tìm tập người dùng đang click vào
    if ($id_tap_dang_xem > 0 && $row['id'] == $id_tap_dang_xem) {
        $tap_hien_tai = $row;
    }
}

// Nếu không truyền ID tập, mặc định phát tập đầu tiên
if (!$tap_hien_tai && count($danh_sach_tap) > 0) {
    $tap_hien_tai = $danh_sach_tap[0];
}

$hinh_anh = !empty($phim['hinh_anh']) ? "images/".$phim['hinh_anh'] : "images/default-movie.jpg";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($phim['ten_phim']) ?> | Xem Phim Mới</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #e50914; --bg: #141414; --text: #fff; --gray: #b3b3b3; --dark-gray: #2b2b2b; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--bg); color: var(--text); margin: 0; padding: 0; line-height: 1.6; }
        
        /* Navbar đơn giản */
        header { background: #000; padding: 15px 50px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #333; }
        .logo { color: var(--primary); font-size: 24px; font-weight: 900; text-decoration: none; text-transform: uppercase; letter-spacing: 2px; }
        .back-home { color: var(--text); text-decoration: none; font-weight: bold; }
        .back-home:hover { color: var(--primary); }

        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }

        /* Khung phát Video */
        .player-container { background: #000; width: 100%; aspect-ratio: 16/9; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.8); margin-bottom: 30px; position: relative; display: flex; justify-content: center; align-items: center; border: 1px solid #333;}
        .player-container iframe { width: 100%; height: 100%; border: none; }
        .no-video { color: var(--gray); font-size: 20px; text-align: center; }

        /* Thông tin phim */
        .movie-info-section { display: flex; gap: 30px; margin-bottom: 40px; background: #1a1a1a; padding: 30px; border-radius: 12px; }
        .movie-poster { width: 200px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.5); object-fit: cover; }
        .movie-details { flex: 1; }
        .movie-title { font-size: 32px; font-weight: bold; margin: 0 0 10px 0; color: #fff; }
        .movie-meta { display: flex; gap: 15px; margin-bottom: 20px; font-size: 14px; color: var(--gray); flex-wrap: wrap; }
        .meta-item { display: flex; align-items: center; gap: 5px; background: #333; padding: 5px 12px; border-radius: 20px; }
        .meta-item i { color: var(--primary); }
        .movie-desc { color: #ccc; font-size: 16px; margin-bottom: 20px; }

        /* Danh sách tập phim */
        .episode-section { margin-top: 30px; }
        .episode-section h3 { margin-bottom: 15px; font-size: 22px; border-left: 4px solid var(--primary); padding-left: 10px; }
        .episode-grid { display: flex; flex-wrap: wrap; gap: 10px; }
        .ep-btn { background: var(--dark-gray); color: var(--text); padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; transition: 0.3s; border: 1px solid #444; }
        .ep-btn:hover { background: #444; transform: translateY(-2px); }
        .ep-btn.active { background: var(--primary); border-color: var(--primary); box-shadow: 0 4px 15px rgba(229, 9, 20, 0.4); }

    </style>
</head>
<body>

    <header>
        <a href="index.php" class="logo">FilmLane</a>
        <a href="index.php" class="back-home"><i class="fas fa-home"></i> Trang chủ</a>
    </header>

    <div class="container">
       <div class="player-container">
            <?php if ($tap_hien_tai && !empty($tap_hien_tai['link_phim'])): ?>
                <?php 
                $link_phim = $tap_hien_tai['link_phim'];
                
                // Trường hợp 1: Nếu admin đã dán sẵn mã <iframe...> thì in ra luôn
                if (strpos($link_phim, '<iframe') !== false): 
                    echo $link_phim;
                else: 
                    // Trường hợp 2: Tự động phát hiện và chuyển đổi link YouTube (watch?v=... hoặc youtu.be/...)
                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $link_phim, $match)) {
                        $video_id = $match[1];
                        $embed_url = "https://www.youtube.com/embed/" . $video_id;
                        echo '<iframe src="' . htmlspecialchars($embed_url) . '" allowfullscreen></iframe>';
                    } 
                    // Trường hợp 3: Các link video từ nguồn khác (Google Drive, v.v.)
                    else {
                        echo '<iframe src="' . htmlspecialchars($link_phim) . '" allowfullscreen></iframe>';
                    }
                endif; 
                ?>
            <?php else: ?>
                <div class="no-video">
                    <i class="fas fa-video-slash" style="font-size: 50px; margin-bottom: 15px; color: #555; display:block;"></i>
                    Phim hiện tại chưa cập nhật tập nào hoặc link hỏng.
                </div>
            <?php endif; ?>
        </div>

        <div class="movie-info-section">
            <img src="<?= $hinh_anh ?>" alt="<?= htmlspecialchars($phim['ten_phim']) ?>" class="movie-poster">
            <div class="movie-details">
                <h1 class="movie-title"><?= htmlspecialchars($phim['ten_phim']) ?></h1>
                
                <div class="movie-meta">
                    <div class="meta-item"><i class="fas fa-calendar-alt"></i> Năm: <?= $phim['nam_phat_hanh'] ?></div>
                    <div class="meta-item"><i class="fas fa-eye"></i> Lượt xem: <?= number_format($phim['luot_xem'], 0, ',', '.') ?></div>
                    <div class="meta-item"><i class="fas fa-tags"></i> Thể loại: <?= !empty($phim['ten_the_loai']) ? htmlspecialchars($phim['ten_the_loai']) : 'Đang cập nhật' ?></div>
                </div>

                <div class="movie-desc">
                    <strong>Nội dung phim:</strong><br>
                    <?= !empty($phim['mo_ta']) ? nl2br(htmlspecialchars($phim['mo_ta'])) : 'Đang cập nhật nội dung...' ?>
                </div>
            </div>
        </div>

        <div class="episode-section">
            <h3><i class="fas fa-list-ul"></i> Danh Sách Tập</h3>
            <?php if (count($danh_sach_tap) > 0): ?>
                <div class="episode-grid">
                    <?php foreach ($danh_sach_tap as $tap): ?>
                        <a href="xemphim.php?id=<?= $id ?>&tap=<?= $tap['id'] ?>" 
                           class="ep-btn <?= ($tap_hien_tai && $tap_hien_tai['id'] == $tap['id']) ? 'active' : '' ?>">
                            <?= htmlspecialchars($tap['ten_tap']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="color: var(--gray); font-style: italic;">Phim đang được cập nhật tập mới, vui lòng quay lại sau!</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>