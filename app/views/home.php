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
</head>
    <body>
        
        <div class="container">
        
            <!-- <div class="introduce"></div>
            <div class="book-category"></div> -->
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
        </div>
        <script src="<?= BASE_URL ?>app/assets/js/jquery.min.js"></script>
        <script src="<?= BASE_URL ?>app/assets/js/owl.carousel.min.js"></script>
        <script src="<?= BASE_URL ?>app/assets/js/home.js"></script>
    </body>
</html>