
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book List</title>
    <script>var BASE_URL = "<?= BASE_URL ?>";</script>
    <link rel="stylesheet" href="<?= BASE_URL ?>app/assets/css/base.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>app/assets/css/home.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>app/assets/css/bookList.css">
</head>
<body>
    <div class="container booklist-layout">
        <aside id="filter" class="booklist-filter">
            <div class="filter-group">
                <h3 class="filter-title">Lọc theo giá tiền</h3>
                <div class="price-input-group" id="price">
                    <select id="priceSelect" class="price-select">
                        <option value="">Tất cả mức giá</option>
                        <option value="0-50000">0 - 50.000đ</option>
                        <option value="50000-100000">50.000 - 100.000đ</option>
                        <option value="100000-500000">100.000 - 500.000đ</option>
                        <option value="500000-1000000">500.000 - 1.000.000đ</option>
                    </select>
                </div>
            </div>
            
            <div class="filter-group">
                <h3 class="filter-title">Thể loại</h3>
                <ul class="category-list" id="categoryList"></ul>
            </div>
        </aside>

        <main id="bookList" class="booklist-main">
            <header class="booklist-header">
                <div class="header-left">
                    <p class="header-sub">Kết quả hiển thị: <span id="resultCount">—</span> sản phẩm</p>
                </div>

                <h1 class="header-title">Tất cả sản phẩm</h1>

                <div class="header-right">
                    <label class="sort-label" for="sortSelect">Lọc theo sản phẩm mới nhất</label>
                    <select id="sortSelect" class="sort-select">
                        <option value="newest">Lọc theo sản phẩm mới nhất</option>
                        <option value="popular">Lọc theo sản phẩm bán chạy nhất</option>
                        <option value="price_asc">Lọc theo giá từ thấp đến cao</option>
                        <option value="price_desc">Lọc theo giá từ cao đến thấp</option>
                    </select>
                </div>
            </header>

            <section class="booklist-grid products" id="list">

            </section>
            <nav id="pagination" class="booklist-pagination" aria-label="Pagination">
                
            </nav>
        </main>
            
    </div>

        
   

    <script src="<?= BASE_URL ?>app/assets/js/bookList.js"></script>
    <script src="<?= BASE_URL ?>app/assets/js/bookDetail.js"></script>
    <script src="<?= BASE_URL ?>app/assets/js/header.js"></script>
</body>
</html>