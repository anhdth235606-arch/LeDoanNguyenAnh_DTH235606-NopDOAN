<?php
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] != 'quan_tri') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ Admin | CHó Hiển</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Đồng bộ màu sắc với các trang khác trong hệ thống */
        :root { 
            --primary: #e50914; 
            --bg: #000; 
            --card: #1f1f1f; 
            --text: #fff; 
            --gray: #b3b3b3; 
        }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: var(--bg); 
            color: var(--text); 
            margin: 0; 
            display: flex; 
        }
        
        /* Cấu trúc Sidebar đồng bộ */
        .sidebar { 
            width: 260px; 
            background: #141414; 
            height: 100vh; 
            position: fixed; 
            padding-top: 30px; 
            border-right: 1px solid #333; 
            box-shadow: 2px 0 5px rgba(0,0,0,0.5);
        }
        .sidebar h2 { 
            color: var(--primary); 
            text-align: center; 
            margin-bottom: 40px; 
            letter-spacing: 2px;
        }
        .sidebar a { 
            display: block; 
            color: var(--gray); 
            padding: 15px 25px; 
            text-decoration: none; 
            transition: 0.3s; 
            font-size: 16px;
        }
        .sidebar a:hover, .sidebar a.active { 
            background: rgba(229, 9, 20, 0.1); 
            color: var(--primary); 
            border-left: 4px solid var(--primary); 
        }
        .sidebar a i { 
            margin-right: 12px; 
            width: 20px; 
            text-align: center; 
        }

        /* Khu vực Nội dung chính */
        .main-content { 
            margin-left: 260px; 
            padding: 40px; 
            width: calc(100% - 260px); 
            box-sizing: border-box;
        }

        /* Thẻ chào mừng */
        .welcome-card {
            background: linear-gradient(135deg, #1f1f1f 0%, #2a2a2a 100%);
            padding: 40px;
            border-radius: 15px;
            border-left: 5px solid var(--primary);
            margin-bottom: 40px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }
        .welcome-card h1 {
            margin-top: 0;
            color: #fff;
            font-size: 28px;
        }
        .welcome-card h1 span {
            color: var(--primary);
        }
        .welcome-card p {
            color: var(--gray);
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 0;
        }

        /* Truy cập nhanh */
        .section-title {
            font-size: 20px;
            margin-bottom: 20px;
            color: #fff;
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
        }
        .quick-actions { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); 
            gap: 20px; 
        }
        .action-card { 
            background: var(--card); 
            padding: 30px 20px; 
            border-radius: 12px; 
            border: 1px solid #333; 
            text-align: center; 
            text-decoration: none; 
            color: var(--text); 
            transition: all 0.3s ease; 
            display: block; 
        }
        .action-card:hover { 
            transform: translateY(-5px); 
            border-color: var(--primary); 
            box-shadow: 0 8px 15px rgba(229, 9, 20, 0.15); 
        }
        .action-card i { 
            font-size: 40px; 
            color: var(--primary); 
            margin-bottom: 15px; 
        }
        .action-card h3 { 
            margin: 0; 
            font-size: 18px; 
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>CHÓ HIỂN</h2>
        <a href="admin.php" class="active"><i class="fas fa-home"></i> Trang chủ Admin</a>
        <a href="bangdieukhien.php"><i class="fas fa-tachometer-alt"></i> Bảng điều khiển</a>
        <a href="quanlyphim.php"><i class="fas fa-film"></i> Quản lý phim</a>
        <a href="quanlytheloai.php"><i class="fas fa-list"></i> Quản lý thể loại</a>
        <a href="quanlynguoidung.php"><i class="fas fa-users"></i> Quản lý người dùng</a>
       
        
        <div style="margin-top: 50px;">
            <a href="index.php"><i class="fas fa-external-link-alt"></i> Xem trang khách</a>
            <a href="logout.php" style="color: var(--primary);"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
        </div>
    </div>

    <div class="main-content">
        
        <div class="welcome-card">
            <h1>Xin chào, <span><?php echo isset($_SESSION['ho_ten']) ? $_SESSION['ho_ten'] : 'Quản trị viên'; ?></span>! 👋</h1>
            <p>Chào mừng bạn đến với trung tâm điều khiển của hệ thống. Tại đây, bạn có toàn quyền kiểm soát dữ liệu website. <br>
            Hãy sử dụng menu bên trái để điều hướng hoặc truy cập nhanh các chức năng phổ biến ở bên dưới.</p>
        </div>

        <h2 class="section-title">Phím tắt truy cập nhanh</h2>
        
        <div class="quick-actions">
            <a href="them_phim.php" class="action-card">
                <i class="fas fa-plus-circle"></i>
                <h3>Thêm phim mới</h3>
            </a>
            <a href="bangdieukhien.php" class="action-card">
                <i class="fas fa-chart-line"></i>
                <h3>Xem thống kê</h3>
            </a>
            <a href="quanlynguoidung.php" class="action-card">
                <i class="fas fa-user-shield"></i>
                <h3>Kiểm duyệt User</h3>
            </a>
            <a href="quanlytheloai.php" class="action-card">
                <i class="fas fa-tags"></i>
                <h3>Quản lý danh mục</h3>
            </a>
        </div>

    </div>

</body>
</html>