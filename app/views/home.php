<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookiary - Nơi cảm hứng bắt đầu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>app/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>app/assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>app/assets/css/base.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>app/assets/css/home.css">
</head>

<body>
    <div class="container-fluid hero-section">
        <div class="container hero-content">
            <div class="hero-text">
                <h1 class="hero-title">Bookiary – Nơi cảm hứng bắt đầu</h1>
                <h2 class="hero-subtitle">
                    Mỗi cuốn sách là một hành trình
                </h2>
                <div class="hero-desc">
                    <p>Khám phá không gian tri thức tuyệt vời với bộ sưu tập sách đặc sắc được chọn lọc kỹ càng, đồng
                        hành cùng bạn trên mọi nẻo đường cảm xúc.</p>
                </div>
                <div class="hero-button">
                    <a href="#explore" class="explore-btn">Khám phá bộ sưu tập</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="<?= BASE_URL ?>app/assets/images/hero_library.png" alt="Library Background">
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Story Section -->
        <div class="story-section" id="explore">
            <h3 class="section-title">Câu chuyện Bookiary</h3>
            <div class="story-cards">
                <div class="story-card">
                    <div class="icon-wrapper"><i class="fas fa-seedling"></i></div>
                    <h4>Nguồn gốc</h4>
                    <p>Bắt đầu từ một tiệm sách nhỏ với ước mơ lan tỏa văn hóa đọc, chúng tôi không ngừng nỗ lực mang
                        lại những giá trị bền vững.</p>
                </div>
                <div class="story-card">
                    <div class="icon-wrapper"><i class="fas fa-heart"></i></div>
                    <h4>Sứ mệnh</h4>
                    <p>Trở thành người bạn đồng hành tin cậy, cung cấp nguồn cảm hứng và kiến thức phong phú cho mọi thế
                        hệ độc giả.</p>
                </div>
                <div class="story-card">
                    <div class="icon-wrapper"><i class="fas fa-users"></i></div>
                    <h4>Cộng đồng</h4>
                    <p>Nơi giao lưu, chia sẻ niềm đam mê, để mỗi người đọc tìm được sự đồng điệu trong hàng ngàn tác
                        phẩm.</p>
                </div>
            </div>
        </div>

        <div class="best-seller">
            <div class="heading-wrapper">
                <p class="heading">Top Sách Bán Chạy</p>
                <div class="line"></div>
                <button class="viewBtn">Xem tất cả</button>
            </div>
            <div class="owl-carousel owl-theme products" id="best-seller-books">
                <!-- Data populated by JS -->
            </div>
        </div>

        <!-- Khuyến mãi & Bundle  -->
        <div class="promo-section">
            <div class="heading-wrapper">
                <p class="heading">Ưu đãi & Bộ sưu tập</p>
                <div class="line"></div>
            </div>
            <div class="promo-cards">
                <div class="promo-card"
                    style="background-image: linear-gradient(rgba(20,54,115,0.7), rgba(20,54,115,0.7)), url('<?= BASE_URL ?>app/assets/images/promo1.jpg');">
                    <div class="promo-content">
                        <h4>Mùa Trinh Thám</h4>
                        <p>Giảm 25% cho tất cả tiểu thuyết trinh thám tháng này.</p>
                        <a href="<?= BASE_URL ?>category/trinh-tham" class="btn-promo"
                            style="display:inline-block; text-decoration:none;">Khám phá ngay</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="newly-published">
            <div class="heading-wrapper">
                <p class="heading">Mới xuất bản</p>
                <div class="line"></div>
                <button class="viewBtn">Xem tất cả</button>
            </div>
            <div class="owl-carousel owl-theme products" id="newly-published-books">
                <!-- Data populated by JS -->
            </div>
        </div>

        <div class="quotes">
            <h3 class="section-title">Trích dẫn từ Sách</h3>
            <div class="quote-container">
                <div class="quote-left">
                    <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                    <p class="quote-text" id="quoteText">
                        <?php
                        if (!empty($quotes)) {
                            $firstQuote = $quotes[0]['quote'];
                            echo htmlspecialchars(mb_substr($firstQuote, 0, 250)) . '...';
                        } else {
                            echo "Những cuốn sách tại Bookiary luôn mang đến cho tôi những góc nhìn mới và vô vàn xúc cảm khó phai.";
                        }
                        ?>
                    </p>
                    <div class="author-info">
                        <div class="author-avatar"><i class="fas fa-book-reader"></i></div>
                        <span class="author-name">Trích dẫn ấn tượng</span>
                    </div>
                    <div class="quote-dots">
                        <?php if (!empty($quotes)): ?>
                            <?php foreach ($quotes as $index => $q): ?>
                                <span class="dot <?php echo $index === 0 ? 'active' : ''; ?>"
                                    data-index="<?php echo $index; ?>"></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="quote-right">
                    <?php if (!empty($quotes) && isset($quotes[0]['img'])): ?>
                        <a href="index.php?controller=book&action=detail&id=<?php echo $quotes[0]['id']; ?>"
                            id="quoteBookLink">
                            <img src="<?= BASE_URL ?><?php echo $quotes[0]['img']; ?>" class="quote-book-img"
                                id="quoteBookImg">
                        </a>
                    <?php else: ?>
                        <!-- Placeholder hình ảnh minh họa -->
                        <div class="quote-placeholder-img">
                            <i class="fas fa-book-open"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <script>
            const quotesData = <?php echo json_encode($quotes ?? []); ?>;
            const APP_BASE_URL = "<?= BASE_URL ?>";
        </script>

        <div class="allProduct">
            <div class="heading-wrapper">
                <p class="heading">Tất cả sản phẩm</p>
                <div class="line"></div>
                <button class="viewBtn">Xem tất cả</button>
            </div>
            <div class="products" id="all-books">
                <!-- Data populated by JS -->
            </div>
            <div id="pagination"></div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>app/assets/js/jquery.min.js"></script>
    <script src="<?= BASE_URL ?>app/assets/js/owl.carousel.min.js"></script>
    <script src="<?= BASE_URL ?>app/assets/js/home.js?v=2"></script>
    <script src="<?= BASE_URL ?>app/assets/js/bookDetail.js?v=2"></script>
</body>

</html>