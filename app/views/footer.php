<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookiary</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= BASE_URL ?>app/assets/css/header-footer-style.css">
</head>

<body>

    <footer class="footer">
        <div class="grid">
            <div class="grid-row">
                <div class="grid_column">
                    <ul class="footer_list" style="text-align: center;">
                        <li class="footer_item">
                            <a href="<?= BASE_URL ?>home" class="logo-link">
                                <img src="<?= BASE_URL ?>app/assets/images/logo-footer.png" alt="Bookiary Logo"
                                    class="logo-footer">
                            </a>
                        </li>
                        <li class="footer_item">
                            <a href="#" class="logo-link">
                                <span class="logo-text">Về Bookiary</span>
                            </a>
                        </li>
                        <li class="footer_item">
                            <span class="footer_item-link">SĐT: 0123 456 789</span>
                        </li>
                        <li class="footer_item">
                            <span class="footer_item-link">Email: contact@bookiary.com</span>
                        </li>
                    </ul>
                </div>
                <div class="grid_column" style="min-height: 130px;">
                    <h3 class="footer_heading" style="text-align: center;">Khám phá</h3>
                    <ul class="footer_list" style="text-align: center;">
                        <li class="footer_item">
                            <a href="<?= BASE_URL ?>home" class="footer_item-link">Trang chủ</a>
                        </li>
                        <li class="footer_item">
                            <a href="#" class="footer_item-link">Câu chuyện của chúng tôi</a>
                        </li>
                        <li class="footer_item">
                            <a href="#" class="footer_item-link">Thể loại sách</a>
                        </li>
                        <li class="footer_item">
                            <a href="#" class="footer_item-link">Sách bán chạy</a>
                        </li>
                    </ul>
                </div>
                <div class="grid_column" style="min-height: 130px;">
                    <h3 class="footer_heading" style="text-align: center;">Hỗ trợ khách hàng</h3>
                    <ul class="footer_list" style="text-align: center;">
                        <li class="footer_item">
                            <a href="#" class="footer_item-link">Điều khoản sử dụng</a>
                        </li>
                        <li class="footer_item">
                            <a href="#" class="footer_item-link">Chính sách đổi trả - hoàn tiền</a>
                        </li>
                        <li class="footer_item">
                            <a href="#" class="footer_item-link">Chính sách giao hàng</a>
                        </li>
                        <li class="footer_item">
                            <a href="#" class="footer_item-link">Phương thức thanh toán</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom"
                style="text-align: center; border-top: 1px solid rgba(255,255,255,0.1); margin-top: 30px; padding-top: 20px;">
                <p>&copy; 2026 Bookiary. Đã bảo lưu mọi quyền.</p>
                <div class="social-icons" style="margin-top: 10px; font-size: 20px;">
                    <a href="#" style="color: #fff; margin: 0 10px;"><i class="bi bi-facebook"></i></a>
                    <a href="#" style="color: #fff; margin: 0 10px;"><i class="bi bi-instagram"></i></a>
                    <a href="#" style="color: #fff; margin: 0 10px;"><i class="bi bi-twitter"></i></a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>