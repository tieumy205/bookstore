<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>app/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>app/assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>app/assets/css/base.css">

    <link rel="stylesheet" href="<?= BASE_URL ?>app/assets/css/home.css">
</head>
    <body>
                
        <div class="container">
         <div class="introduce"></div>
            <div class="book-category"></div>
            <div class="best-seller">
                <div class="heading">
                    <p class="heading">Top 10 sách bán chạy nhất</p>
                    <div class="line"></div>
                </div>
                <div class="container">
                    <div class="text">
                        <button class="viewBtn">Xem tất cả</button>
                    </div>
                    <div class=" owl-carousel owl-theme products" id="best-seller-books">
                        
                    </div>
                </div>
            </div>
            <div class="quotes">
                <div class="quote-container">
                    <div class="icon-quote"></div>
                    <div class="quote-content"></div>
                </div>
    
                <div class="book-image">
                    <img src="" alt="">
                </div>
            </div>
            <div class="fashsale"></div>

            <div class="newly-published">
                <div class="heading">
                    <p class="heading">Sách mới xuất bản</p>
                    <div class="line"></div>
                </div>
                <div class="container">
                    <div class="text">
                        <button class="viewBtn">Xem tất cả</button>
                    </div>
                    <div class=" owl-carousel owl-theme products" id="newly-published-books">
                        
                    </div>
                </div>
            </div>

            <div class="allProduct">
                <div class="heading">
                    <p class="heading">Tất cả sản phẩm</p>
                    <div class="line"></div>
                </div>
                <div class="container">
                    <div class="text">
                        <button class="viewBtn">Xem tất cả</button>
                    </div>
                    <div class="products" id="all-books">
                        
                    </div>
                    <div id="pagination">

                    </div>
                </div>  
            </div>

            
        </div>
        </div> 
       



        <!-- <main class="main-content">
            <div class="main-content-container">
                <section class="hero-section">
                    <div class="grid-row">
                        <div class="grid_column">
                            <div class="hero-text">
                                <h1 class="brand-title">BOOKIARY</h1>

                                <h2 class="brand-slogan">
                                    Một góc nhỏ cho những tâm hồn yêu sách và những câu chuyện đẹp
                                </h2>

                                <div class="brand-description">
                                    <p>Không chỉ là cửa hàng sách trực tuyến,</p>
                                    <p>Bookiary mong muốn trở thành nơi bạn tìm thấy sự bình yên</p>
                                    <p>giữa nhịp sống bận rộn, nơi mỗi cuốn sách đều mang theo cảm xúc,</p>
                                    <p>giá trị và sự đồng điệu.</p>
                                </div>
                            </div>
                            
                            <div class="hero-button">
                                <button type="button" class="explore-btn">KHÁM PHÁ NGAY</button>
                            </div>
                        </div>
                        <div class="grid_column">
                            <img src="app/assets/images/picture1.png" class="picture1">
                        </div>    
                    </div>
                </section>

                <section class="about-section">
                    <div class="grid-row">

                        <div class="grid_column">
                            <img src="app/assets/images/picture2.png" class="picture2">
                        </div>

                        <div class="grid_column">
                            <h2 class="about-title"><a href="#">GIỚI THIỆU</a></h2>

                            <h3 class="about-subtitle">
                                Bookiary - Cửa hàng sách cho những người yêu thích đọc sách.
                            </h3>

                            <p class="description">
                                Bookiary là cửa hàng sách trực tuyến, nơi bạn có thể dễ dàng tìm thấy 
                                những cuốn sách hay và phù hợp với nhu cầu đọc của mình. Từ văn học,
                                kiến thức đến kỹ năng và phát triển bản thân, chúng tôi chọn lọc từng đầu 
                                sách với mong muốn mang đến trải nghiệm mua sách đơn giản, thoải mái 
                                và gần gũi — để việc đọc trở thành một phần quen thuộc trong cuộc sống 
                                hằng ngày của bạn.
                            </p>

                        </div>
                    </div>
                </section>

                <section class="category-section">
                    <div class="grid-row">
                        <div class="grid_column">

                            <h3 class="section-title-1">Thể loại</h3>

                            <ul class="category-list">
                                <li class="category-item">
                                    <a href="#">
                                        <div class="icon-circle">
                                            <img src="app/assets/images/vanhoc.png" alt="Văn học">
                                        </div>
                                        <span class="category-name">Văn học</span>
                                    </a>
                                </li>

                                <li class="category-item">
                                    <a href="#">
                                        <div class="icon-circle">
                                            <img src="app/assets/images/trinhtham.png" alt="Trinh thám">
                                        </div>
                                        <span class="category-name">Trinh thám</span>
                                    </a>
                                </li>

                                <li class="category-item">
                                    <a href="#">
                                        <div class="icon-circle">
                                            <img src="app/assets/images/kinhdi.png" alt="Kinh dị">
                                        </div>
                                        <span class="category-name">Kinh dị</span>
                                    </a>
                                </li>

                                <li class="category-item">
                                    <a href="#">
                                        <div class="icon-circle">
                                            <img src="app/assets/images/langman.png" alt="Lãng mạn">
                                        </div>
                                        <span class="category-name">Lãng mạn</span>
                                    </a>
                                </li>

                                <li class="category-item">
                                    <a href="#">
                                        <div class="icon-circle">
                                            <img src="app/assets/images/truyentranh.png" alt="Truyện tranh">
                                        </div>
                                        <span class="category-name">Truyện tranh</span>
                                    </a>
                                </li>

                                <li class="category-item">
                                    <a href="#">
                                        <div class="icon-circle">
                                            <img src="app/assets/images/selfhelp.png" alt="Self Help">
                                        </div>
                                        <span class="category-name">Self Help</span>
                                    </a>
                                </li>
                            </ul>

                        </div>
                    </div>
                </section>

                <section class="top-selling-section">
                    <div class="section-title-container">
                        <div class="grid-row">
                            <div class="grid_column">

                                <h3 class="section-title">Top 10 sách bán chạy nhất</h3>
                                <div class="slider-wrapper">

                                    <button class="slider-btn prev">&#10094;</button>

                                    <div class="slider-container">
                                        <div class="slider-track">

                                            

                                        </div>
                                    </div>
                                    
                                    <button class="slider-btn next">&#10095;</button>

                                </div>

                            </div>
                        </div>
                    </div>
                </section>

                <section class="book-quote-section">
                    <div class="grid-row">
                        <div class="grid_column">
                            <div class="quote-container">
                                <div class="quote-left">
                                    <div class="quote-icon">“</div>
                                    <p class="quote-text">
                                        
                                    </p>
                                    <div class="quote-dots">
                                        <?php foreach ($quotes as $index => $q): ?>
                                            <span class="dot <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>"></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="quote-right">

                                    <a href="<?php echo $quotes[0]['link']; ?>" id="quoteBookLink">
                                        <img 
                                        src="app/assets/images/books/<?php echo $quotes[0]['img']; ?>" 
                                        class="quote-book-img"
                                        id="quoteBookImg">

                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class></section>

                <section class="product-section">
                    <div class="grid-row">
                        <div class="grid_column">

                            <h3 class="section-title">Sách mới xuất bản</h3>

                            <div class="slider-wrapper">

                                <button class="slider-btn prev">&#10094;</button>

                                <div class="slider-container">
                                    <div class="slider-track">

                                

                                    </div>
                                </div>
                                
                                <button class="slider-btn next">&#10095;</button>

                            </div>
                        </div>
                    </div>
                </section>
                <div class="main-content-rectangle-7">

                </div>
                <div class="main-content-rectangle-8">

                </div>
            </div>
        </main> -->



        <script src="<?= BASE_URL ?>app/assets/js/jquery.min.js"></script>
        <script src="<?= BASE_URL ?>app/assets/js/owl.carousel.min.js"></script>
        <script src="<?= BASE_URL ?>app/assets/js/home.js"></script>
        <script src="<?= BASE_URL ?>app/assets/js/bookDetail.js"></script>
    </body>
</html>