<?php
session_start();

$isLogin = isset($_SESSION['user']);
$username = $_SESSION['user']['name'] ?? '';

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookiary</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Grape+Nuts&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= BASE_URL ?>app/assets/css/header-footer-style.css">
</head>
<body>

    <header class="header">
        <div class="container d-flex">
            <div>
                <ul class="menu d-flex">
                    <li><a href="<?= BASE_URL ?>home" class="logo-link">
                            <img src="app/assets/images/logo.png" alt="Bookiary Logo" class="logo">
                        </a>
                    </li>
                    <li><a href="#">Giới thiệu</a></li>
                    <li><a href="#">Thể loại</a></li>
                    <li><a href="#">Liên hệ</a></li>
                </ul>
            </div>
        
            <div class="btn-group d-flex">
                <form action="#" method="GET" class="search-box">
                    <input type="text" name="search" placeholder="Tìm kiếm">
                    <button type="submit" class="search-btn">
                        <img src="app/assets/images/search.png" alt="Search">
                    </button>
                </form>

                <div class="cart-menu">
                    <a href="#" class="cart-icon">
                        <img src="app/assets/images/cart.png" alt="Giỏ hàng">
                        <!-- <span class="cart-count"></span>  hiển thị số lượng -->
                    </a>

                </div>

                <div class="user-menu d-flex">
                    <a href="#">
                    <img src="app/assets/images/user.png" alt="User Icon" class="user-icon">
                    </a>
                    <div class="user-dropdown" id="userDropdown">
                        <?php if ($isLogin): ?>
                            <a href="#">Xin chào, <?php echo htmlspecialchars($username); ?></a>
                            <a href="#">Thông tin cá nhân</a>
                            <a href="#">Đơn mua</a>
                            <a href="?logout=1">Đăng xuất</a>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/login">Đăng nhập</a>
                            <a href="<?= BASE_URL ?>/register">Đăng ký</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>
</body>
</html>