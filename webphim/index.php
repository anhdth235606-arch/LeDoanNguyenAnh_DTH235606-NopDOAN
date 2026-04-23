<?php
session_start();
require_once 'ket_noi.php';

// Lấy danh sách 10 phim mới nhất cho phần "Phim Mới Cập Nhật"
$sql_phim_moi = "SELECT * FROM phim ORDER BY id DESC LIMIT 10";
$result_phim = mysqli_query($conn, $sql_phim_moi);

// Lấy 5 phim ngẫu nhiên cho banner slideshow
$sql_slides = "SELECT * FROM phim ORDER BY RAND() LIMIT 5";
$result_slides = mysqli_query($conn, $sql_slides);
$slides = [];
while ($row = mysqli_fetch_assoc($result_slides)) {
    $slides[] = $row;
}

// Dữ liệu mẫu nếu chưa có phim
if (count($slides) == 0) {
    $slides = [
        ['id' => 1, 'ten_phim' => 'Naruto Shippuden', 'mo_ta' => 'Tiếp nối phần I...', 'hinh_anh' => 'phim3.jpg'],
        ['id' => 2, 'ten_phim' => 'Code Geass', 'mo_ta' => 'Lelouch vi Britannia...', 'hinh_anh' => 'phim1.jpg'],
    ];
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebPhim - Xem phim trực tuyến miễn phí</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* ===== CSS TOÀN BỘ TRANG ===== */
        body {
            background: #000;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding-top: 70px;
        }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 5%;
            background: #000;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-sizing: border-box;
            border-bottom: 1px solid #222;
        }
        .header-left {
            display: flex;
            align-items: center;
        }
        .logo {
            height: 40px;
        }
        .search-wrapper {
            position: relative;
            flex: 1;
            max-width: 400px;
            margin: 0 20px;
        }
        #search-input {
            width: 100%;
            padding: 10px 40px 10px 20px;
            background: #1f1f1f;
            border: 1px solid #444;
            border-radius: 30px;
            color: white;
            font-size: 14px;
            outline: none;
        }
        #search-input:focus {
            border-color: #e50914;
        }
        .search-wrapper i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }
        .search-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #1f1f1f;
            border: 1px solid #333;
            border-radius: 0 0 12px 12px;
            max-height: 400px;
            overflow-y: auto;
            z-index: 1001;
            display: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.5);
            margin-top: 5px;
        }
        .search-item {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            color: white;
            text-decoration: none;
            border-bottom: 1px solid #333;
        }
        .search-item:hover {
            background: #333;
        }
        .search-item img {
            width: 40px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 15px;
        }
        .search-title {
            font-weight: bold;
        }
        .search-meta {
            font-size: 12px;
            color: #aaa;
        }
        nav ul {
            display: flex;
            gap: 20px;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            white-space: nowrap;
        }
        nav ul li a:hover {
            color: #e50914;
        }

        /* --- HIỆU ỨNG TRỎ CHUỘT HIỆN THỂ LOẠI --- */
        .dropdown-menu-item {
            position: relative;
            display: inline-block;
        }
        .dropdown-menu-item .dropdown-content {
            display: none; 
            position: absolute;
            background-color: #141414;
            min-width: 180px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.7);
            z-index: 1000;
            top: 100%;
            left: -50px; /* Canh qua trái một chút cho đẹp */
            border-radius: 4px;
            border: 1px solid #333;
            padding: 5px 0;
        }
        .dropdown-menu-item .dropdown-content a {
            color: #ccc;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
            font-size: 14px;
            border-bottom: 1px solid #222;
            transition: all 0.3s ease;
        }
        .dropdown-menu-item .dropdown-content a:last-child {
            border-bottom: none;
        }
        .dropdown-menu-item .dropdown-content a:hover {
            background-color: #e50914; 
            color: #fff;
            padding-left: 20px;
        }
        .dropdown-menu-item:hover .dropdown-content {
            display: block;
        }

        /* ===== BANNER SLIDESHOW ===== */
        .carousel-container {
            position: relative;
            width: 100%;
            height: 550px;
            overflow: hidden;
            margin-bottom: 40px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.5);
        }
        .carousel-slides {
            display: flex;
            height: 100%;
            transition: transform 0.5s ease-in-out;
        }
        .carousel-slide {
            min-width: 100%;
            height: 100%;
            position: relative;
            background-size: cover;
            background-position: center 20%;
        }
        .slide-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.4) 60%, transparent 100%);
            display: flex;
            align-items: center;
        }
        .slide-content {
            max-width: 600px;
            margin-left: 8%;
            color: white;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.8);
            animation: fadeInUp 0.8s;
        }
        .slide-content h2 {
            font-size: 48px;
            margin-bottom: 20px;
            font-weight: 800;
        }
        .slide-content p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .slide-buttons {
            display: flex;
            gap: 15px;
        }
        .btn-slide {
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: bold;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
            border: none;
            cursor: pointer;
        }
        .btn-primary {
            background: #e50914;
            color: white;
            box-shadow: 0 4px 10px rgba(229,9,20,0.3);
        }
        .btn-primary:hover {
            background: #ff0a16;
            transform: scale(1.05);
        }
        .btn-outline-light {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        .btn-outline-light:hover {
            background: white;
            color: black;
        }

        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0,0,0,0.5);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
            z-index: 10;
        }
        .carousel-btn:hover {
            background: rgba(229,9,20,0.8);
        }
        .btn-prev {
            left: 20px;
        }
        .btn-next {
            right: 20px;
        }

        .carousel-dots {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 12px;
            z-index: 10;
        }
        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255,255,255,0.5);
            cursor: pointer;
            transition: 0.3s;
        }
        .dot.active {
            background: #e50914;
            transform: scale(1.3);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== BẢNG XẾP HẠNG (Dùng chung cho cả 2 bảng) ===== */
        .ranking-section {
            margin: 20px 5% 40px;
            background: #111;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid #333;
        }
        .ranking-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            color: #e50914;
            font-size: 24px;
            font-weight: bold;
        }
        .ranking-header i {
            font-size: 28px;
        }
        .ranking-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .ranking-item {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #1a1a1a;
            padding: 10px 15px;
            border-radius: 12px;
            transition: 0.2s;
            text-decoration: none;
            color: white;
            border: 1px solid #2a2a2a;
        }
        .ranking-item:hover {
            background: #2a2a2a;
            border-color: #e50914;
            transform: translateX(5px);
        }
        .rank-number {
            font-size: 22px;
            font-weight: bold;
            color: #e50914;
            min-width: 30px;
            text-align: center;
        }
        .rank-thumb {
            width: 50px;
            height: 70px;
            object-fit: cover;
            border-radius: 6px;
        }
        .rank-info {
            flex: 1;
        }
        .rank-title {
            font-weight: 600;
            margin-bottom: 4px;
        }
        .rank-year {
            font-size: 13px;
            color: #aaa;
        }
        .rank-views {
            font-size: 14px;
            color: #e50914;
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: 600;
        }
        .rank-views i {
            font-size: 12px;
        }
        .rank-badge {
            background: #e50914;
            color: white;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        /* ===== MOVIE GRID ===== */
        .movie-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
            padding: 20px 5%;
        }
        .movie-card {
            background: #1f1f1f;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s;
        }
        .movie-card:hover {
            transform: scale(1.03);
        }
        .movie-card img {
            width: 100%;
            height: 270px;
            object-fit: cover;
        }
        .movie-title {
            padding: 12px;
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
        }

        h2 {
            margin-left: 5%;
            margin-top: 20px;
            color: white;
        }

        footer {
            text-align: center;
            padding: 30px;
            color: #666;
            border-top: 1px solid #222;
            margin-top: 50px;
        }

        @media (max-width: 768px) {
            .carousel-container { height: 400px; }
            .slide-content h2 { font-size: 32px; }
            .slide-content p { font-size: 14px; }
            .btn-slide { padding: 10px 20px; }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-left">
            <img src="images/logo.png" alt="Logo Web Phim" class="logo">
        </div>
        <div class="search-wrapper">
            <input type="text" id="search-input" placeholder="Tìm phim..." autocomplete="off">
            <i class="fas fa-search"></i>
            <div id="search-results" class="search-dropdown"></div>
        </div>
        <nav>
            <ul>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="#" style="color: #e50914;">Chào, <?= htmlspecialchars($_SESSION['ho_ten']) ?></a></li>
                    <?php if ($_SESSION['vai_tro'] == 'quan_tri'): ?>
                        <li><a href="admin.php">Quản trị viên</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Đăng xuất</a></li>
                <?php else: ?>
                    <li><a href="login.php">Đăng nhập</a></li>
                <?php endif; ?>
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="#">Phim mới</a></li>
                
                <li class="dropdown-menu-item">
                    <a href="theloai.php" style="cursor: pointer;">Thể loại <i class="fas fa-caret-down" style="font-size: 12px; margin-left: 3px;"></i></a>
                    <div class="dropdown-content">
                        <?php 
                        $sql_dropdown = "SELECT * FROM the_loai ORDER BY ten_the_loai ASC";
                        $query_dropdown = mysqli_query($conn, $sql_dropdown);
                        
                        if (mysqli_num_rows($query_dropdown) > 0) {
                            while($tl_menu = mysqli_fetch_assoc($query_dropdown)): 
                        ?>
                            <a href="theloai.php?id=<?= $tl_menu['id'] ?>"><?= htmlspecialchars($tl_menu['ten_the_loai']) ?></a>
                        <?php 
                            endwhile; 
                        } else {
                            echo '<a href="#">Chưa có thể loại</a>';
                        }
                        ?>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <div class="carousel-container">
        <div class="carousel-slides" id="carouselSlides">
            <?php foreach ($slides as $index => $phim): ?>
                <?php 
                    $bg_image = !empty($phim['hinh_anh']) ? 'images/'.$phim['hinh_anh'] : 'images/default-movie.jpg';
                    $mo_ta_ngan = isset($phim['mo_ta']) ? $phim['mo_ta'] : 'Chưa có mô tả.';
                    $mo_ta_ngan = strlen($mo_ta_ngan) > 200 ? substr($mo_ta_ngan, 0, 200).'...' : $mo_ta_ngan;
                ?>
                <div class="carousel-slide" style="background-image: url('<?= $bg_image ?>');">
                    <div class="slide-overlay">
                        <div class="slide-content">
                            <h2><?= htmlspecialchars($phim['ten_phim']) ?></h2>
                            <p><?= htmlspecialchars($mo_ta_ngan) ?></p>
                            <div class="slide-buttons">
                                <a href="xemphim.php?id=<?= $phim['id'] ?>" class="btn-slide btn-primary">
                                    <i class="fas fa-play"></i> XEM NGAY
                                </a>
                                <a href="xemphim.php?id=<?= $phim['id'] ?>" class="btn-slide btn-outline-light">
                                    <i class="fas fa-info-circle"></i> THÔNG TIN
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <button class="carousel-btn btn-prev" id="prevSlide"><i class="fas fa-chevron-left"></i></button>
        <button class="carousel-btn btn-next" id="nextSlide"><i class="fas fa-chevron-right"></i></button>

        <div class="carousel-dots" id="carouselDots">
            <?php for ($i = 0; $i < count($slides); $i++): ?>
                <span class="dot <?= $i === 0 ? 'active' : '' ?>" data-index="<?= $i ?>"></span>
            <?php endfor; ?>
        </div>
    </div>
       <h2><i class="fas fa-fire" style="color:#e50914; margin-right:10px;"></i>Phim Mới Cập Nhật</h2>
    <div class="movie-grid">
        <?php if (mysqli_num_rows($result_phim) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result_phim)): ?>
                <?php $poster = !empty($row['hinh_anh']) ? "images/".$row['hinh_anh'] : "images/default-movie.jpg"; ?>
                <div class="movie-card">
                    <a href="xemphim.php?id=<?= $row['id'] ?>">
                        <img src="<?= $poster ?>" alt="<?= htmlspecialchars($row['ten_phim']) ?>">
                        <h3 class="movie-title"><?= htmlspecialchars($row['ten_phim']) ?></h3>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="color:#fff; text-align:center; grid-column:1/-1;">Hiện chưa có phim nào.</p>
        <?php endif; ?>
    </div>
    <div class="ranking-section" id="liveRanking">
        <div class="ranking-header">
            <i class="fas fa-trophy"></i>
            <span>🔥 Bảng xếp hạng phim mới</span>
            <span style="margin-left: auto; font-size: 14px; color: #aaa;">
                <i class="fas fa-sync-alt fa-spin" id="ranking-spinner" style="display: none;"></i>
                <span id="last-update"></span>
            </span>
        </div>
        <div class="ranking-list" id="rankingList">
            <div style="color:#aaa; text-align:center; padding: 20px;">Đang tải bảng xếp hạng...</div>
        </div>
    </div>

    <div class="ranking-section">
    <div class="ranking-header">
        <i class="fas fa-fire" style="color: #ff9900;"></i> Top Lượt Xem
    </div>
    <div id="top-views-list" class="ranking-list">
        <div style="text-align:center; padding:20px; color:#aaa;">
            Đang tải dữ liệu...
        </div>
    </div>
    <div style="text-align: right; margin-top: 10px; font-size: 12px; color: #888;">
        <span id="top-views-update-time"></span>
    </div>
</div>

    

    <footer>
        <p>CHÀO MỪNG ĐẾN VỚI CÔNG TY TRÁCH NGHIỆM HỮU HẠN HAI MÌNH &copy; 2026</p>
    </footer>

    <script>
        (function() {
            // ---------- TÌM KIẾM REALTIME ----------
            const searchInput = document.getElementById('search-input');
            const resultsBox = document.getElementById('search-results');
            let debounceTimer;

            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    clearTimeout(debounceTimer);
                    const keyword = this.value.trim();
                    if (keyword.length < 2) {
                        resultsBox.style.display = 'none';
                        return;
                    }
                    debounceTimer = setTimeout(() => {
                        fetch('search_ajax.php?keyword=' + encodeURIComponent(keyword))
                            .then(response => response.json())
                            .then(data => {
                                if (data.length === 0) {
                                    resultsBox.innerHTML = '<div class="no-result" style="padding:15px;color:#aaa;">Không tìm thấy phim</div>';
                                } else {
                                    let html = '';
                                    data.forEach(phim => {
                                        html += `
                                            <a href="xemphim.php?id=${phim.id}" class="search-item">
                                                <img src="${phim.hinh_anh}" alt="${phim.ten_phim}">
                                                <div class="search-info">
                                                    <div class="search-title">${phim.ten_phim}</div>
                                                    <div class="search-meta">${phim.nam_phat_hanh}</div>
                                                </div>
                                            </a>
                                        `;
                                    });
                                    resultsBox.innerHTML = html;
                                }
                                resultsBox.style.display = 'block';
                            })
                            .catch(err => {
                                console.error(err);
                                resultsBox.innerHTML = '<div class="no-result" style="padding:15px;color:#aaa;">Lỗi tìm kiếm</div>';
                                resultsBox.style.display = 'block';
                            });
                    }, 300);
                });

                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
                        resultsBox.style.display = 'none';
                    }
                });

                searchInput.addEventListener('focus', function() {
                    if (this.value.trim().length >= 2) {
                        const event = new Event('keyup');
                        this.dispatchEvent(event);
                    }
                });
            }

            // ---------- CAROUSEL ----------
            const slidesContainer = document.getElementById('carouselSlides');
            const slides = document.querySelectorAll('.carousel-slide');
            const prevBtn = document.getElementById('prevSlide');
            const nextBtn = document.getElementById('nextSlide');
            const dots = document.querySelectorAll('.dot');
            let currentIndex = 0;
            const totalSlides = slides.length;
            let autoSlideInterval;

            function updateCarousel(index) {
                if (index < 0) index = totalSlides - 1;
                if (index >= totalSlides) index = 0;
                currentIndex = index;
                slidesContainer.style.transform = `translateX(-${currentIndex * 100}%)`;
                
                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === currentIndex);
                });
            }

            function nextSlide() {
                updateCarousel(currentIndex + 1);
            }

            function prevSlide() {
                updateCarousel(currentIndex - 1);
            }

            function startAutoSlide() {
                if (totalSlides > 1) {
                    autoSlideInterval = setInterval(nextSlide, 5000);
                }
            }

            function stopAutoSlide() {
                clearInterval(autoSlideInterval);
            }

            if (prevBtn) prevBtn.addEventListener('click', prevSlide);
            if (nextBtn) nextBtn.addEventListener('click', nextSlide);

            dots.forEach((dot, i) => {
                dot.addEventListener('click', () => {
                    updateCarousel(i);
                });
            });

            const carouselContainer = document.querySelector('.carousel-container');
            carouselContainer.addEventListener('mouseenter', stopAutoSlide);
            carouselContainer.addEventListener('mouseleave', startAutoSlide);

            if (totalSlides > 0) {
                startAutoSlide();
            } else {
                document.querySelector('.carousel-container').innerHTML = '<div style="color:white; text-align:center; padding:100px;">Đang cập nhật banner...</div>';
            }

            // ---------- HÀM ESCAPE HTML ----------
            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // ---------- BẢNG XẾP HẠNG PHIM MỚI NHẤT (REALTIME) ----------
            function updateNewMoviesRanking() {
                const rankingList = document.getElementById('rankingList');
                const spinner = document.getElementById('ranking-spinner');
                const lastUpdateSpan = document.getElementById('last-update');

                if (!rankingList) return;

                spinner.style.display = 'inline-block';

                fetch('api_top_new_movies.php')
                    .then(response => response.json())
                    .then(data => {
                        spinner.style.display = 'none';
                        
                        if (data.length === 0) {
                            rankingList.innerHTML = '<div style="color:#aaa; text-align:center; padding:20px;">Chưa có phim nào</div>';
                            return;
                        }

                        let html = '';
                        data.forEach((movie, index) => {
                            const rank = index + 1;
                            const medalIcon = rank === 1 ? '🥇' : (rank === 2 ? '🥈' : (rank === 3 ? '🥉' : `#${rank}`));
                            
                            html += `
                                <a href="xemphim.php?id=${movie.id}" class="ranking-item">
                                    <div class="rank-number">${medalIcon}</div>
                                    <img src="${movie.hinh_anh}" alt="${escapeHtml(movie.ten_phim)}" class="rank-thumb">
                                    <div class="rank-info">
                                        <div class="rank-title">${escapeHtml(movie.ten_phim)}</div>
                                        <div class="rank-year">${movie.nam_phat_hanh}</div>
                                    </div>
                                    ${rank === 1 ? '<span class="rank-badge">MỚI NHẤT</span>' : ''}
                                </a>
                            `;
                        });
                        rankingList.innerHTML = html;

                        const now = new Date();
                        lastUpdateSpan.textContent = `Cập nhật: ${now.toLocaleTimeString('vi-VN')}`;
                    })
                    .catch(err => {
                        console.error('Lỗi tải bảng xếp hạng phim mới:', err);
                        spinner.style.display = 'none';
                        rankingList.innerHTML = '<div style="color:#e50914; text-align:center; padding:20px;">Lỗi tải dữ liệu</div>';
                    });
            }

            // ---------- BẢNG XẾP HẠNG LƯỢT XEM (REALTIME) ----------
            function updateTopViews() {
                const listContainer = document.getElementById('topViewsList');
                const spinner = document.getElementById('views-spinner');
                const lastUpdateSpan = document.getElementById('views-last-update');

                if (!listContainer) return;

                spinner.style.display = 'inline-block';

                fetch('api_top_views.php')
                    .then(response => response.json())
                    .then(data => {
                        spinner.style.display = 'none';
                        
                        if (data.length === 0) {
                            listContainer.innerHTML = '<div style="color:#aaa; text-align:center; padding:20px;">Chưa có dữ liệu lượt xem</div>';
                            return;
                        }

                        let html = '';
                        data.forEach((movie, index) => {
                            const rank = index + 1;
                            const medalIcon = rank === 1 ? '🥇' : (rank === 2 ? '🥈' : (rank === 3 ? '🥉' : `#${rank}`));
                            
                            html += `
                                <a href="xemphim.php?id=${movie.id}" class="ranking-item">
                                    <div class="rank-number">${medalIcon}</div>
                                    <img src="${movie.hinh_anh}" alt="${escapeHtml(movie.ten_phim)}" class="rank-thumb">
                                    <div class="rank-info">
                                        <div class="rank-title">${escapeHtml(movie.ten_phim)}</div>
                                        <div class="rank-year">${movie.nam_phat_hanh}</div>
                                    </div>
                                    <div class="rank-views">
                                        <i class="fas fa-eye"></i> ${movie.luot_xem}
                                    </div>
                                </a>
                            `;
                        });
                        listContainer.innerHTML = html;

                        const now = new Date();
                        lastUpdateSpan.textContent = `Cập nhật: ${now.toLocaleTimeString('vi-VN')}`;
                    })
                    .catch(err => {
                        console.error('Lỗi tải top views:', err);
                        spinner.style.display = 'none';
                        listContainer.innerHTML = '<div style="color:#e50914; text-align:center; padding:20px;">Lỗi tải dữ liệu</div>';
                    });
            }

            // Khởi chạy cả hai bảng xếp hạng
            updateNewMoviesRanking();
            updateTopViews();

            // Cập nhật định kỳ
            setInterval(updateNewMoviesRanking, 10000); // mỗi 10 giây
            setInterval(updateTopViews, 15000);         // mỗi 15 giây
        })();

    </script>
    <script>
    // Hàm bảo mật chống XSS
    function escapeHtml(unsafe) {
        if (!unsafe) return '';
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Hàm gọi API và cập nhật bảng xếp hạng lượt xem
    function updateTopViews() {
        const listContainer = document.getElementById('top-views-list');
        const lastUpdateSpan = document.getElementById('top-views-update-time');

        fetch('api_top_views.php')
            .then(response => response.json())
            .then(data => {
                let html = '';
                if (data.length === 0) {
                    html = '<div style="color:#aaa; text-align:center; padding:20px;">Chưa có dữ liệu lượt xem.</div>';
                } else {
                    data.forEach((movie, index) => {
                        html += `
                            <a href="xemphim.php?id=${movie.id}" class="ranking-item">
                                <div class="rank-number">${index + 1}</div>
                                <img src="${escapeHtml(movie.hinh_anh)}" alt="${escapeHtml(movie.ten_phim)}" class="rank-thumb">
                                <div class="rank-info">
                                    <div class="rank-title">${escapeHtml(movie.ten_phim)}</div>
                                    <div class="rank-year">Năm: ${movie.nam_phat_hanh || 'Đang cập nhật'}</div>
                                </div>
                                <div class="rank-views">
                                    <i class="fas fa-eye"></i> ${Number(movie.luot_xem).toLocaleString('vi-VN')}
                                </div>
                            </a>
                        `;
                    });
                }
                listContainer.innerHTML = html;

                // Hiển thị thời gian cập nhật
                const now = new Date();
                lastUpdateSpan.textContent = `Cập nhật lúc: ${now.toLocaleTimeString('vi-VN')}`;
            })
            .catch(err => {
                console.error('Lỗi tải top views:', err);
                listContainer.innerHTML = '<div style="color:#e50914; text-align:center; padding:20px;">Lỗi tải dữ liệu. Hãy tải lại trang.</div>';
            });
    }

    // Gọi hàm ngay khi tải trang
    document.addEventListener("DOMContentLoaded", function() {
        updateTopViews();
        
        // Cập nhật lại mỗi 15 giây (tuỳ chọn, giống trong file index.php cũ của bạn)
        setInterval(updateTopViews, 15000); 
    });
</script>
</body>
</html>