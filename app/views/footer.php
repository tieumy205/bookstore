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

    <footer class="footer">
        <div class="grid">
            <div class="grid-row">
                <div class="grid_column-2-3">
                    <ul class="footer_list" style="text-align: center;">
                        <li class="footer_item">
                            <a href="#" class="logo-link">
                                <img src="app/assets/images/logo-footer.png" alt="Bookiary Logo" class="logo-footer">
                            </a>
                        </li>
                        <li class="footer_item">
                            <a href="#" class="logo-link">
                                <span class="logo-text">Đại học Sài Gòn</span>
                            </a>
                        </li>
                        <li class="footer_item">
                            <a href="#" class="footer_item-link">SĐT: 123456789</a>
                        </li>
                        <li class="footer_item">
                            <a href="#" class="footer_item-link">Email: bookiary@gmail.com</a>
                        </li>
                    </ul>
                </div>
                <div class="grid_column-2-3" style="min-height: 130px; align-items: start;">
                    <h3 class="footer_heading">Khám phá</h3>
                    <ul class="footer_list" style="text-align: center;">
                        <li class="footer_item">
                            <a href="<?= BASE_URL ?>home" class="footer_item-link">Trang chủ</a>
                        </li>
                        <li class="footer_item">
                            <a href="#" class="footer_item-link">Giới thiệu</a>
                        </li>
                        <li class="footer_item">
                            <a href="#" class="footer_item-link">Thể loại</a>
                        </li>
                    </ul>
                </div>
                <div class="grid_column-2-3" style="min-height: 130px; align-items: start;">
                    <h3 class="footer_heading" style="text-align: center;">Hỗ trợ</h3>
                    <ul class="footer_list" style="text-align: center;">
                        <li class="footer_item">
                            <a href="#" class="footer_item-link">Điều khoản sử dụng</a>
                        </li>
                        <li class="footer_item">
                            <a href="#" class="footer_item-link">Chính sách đổi trả - hoàn tiền</a>
                        </li>
                        <li class="footer_item">
                            <a href="#" class="footer_item-link">Chính sách vận chuyển</a>
                        </li>
                        <li class="footer_item">
                            <a href="#" class="footer_item-link">Phương thức thanh toán</a>
                        </li>
                    </ul>
                </div>
                <!-- <div class="grid_column-2-3" style="min-height: 130px; align-items: start;">
                    <h3 class="footer_heading">Liên hệ</h3>
                    <ul class="footer_list">
                        
                    </ul>
                </div> -->
            </div>
        </div>
        <script src="home.js"></script>
        <script>
            window.bookQuotes = <?php echo json_encode($quotes); ?>;
        </script>
    </footer>
</body>
</html>