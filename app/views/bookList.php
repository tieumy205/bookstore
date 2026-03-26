
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book List</title>
    <script>var BASE_URL = "<?= BASE_URL ?>";</script>
    <link rel="stylesheet" href="<?= BASE_URL ?>app/assets/css/base.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>app/assets/css/bookList.css">
    
</head>
<body>
    <div class="container booklist-layout">
        <aside id="filter" class="booklist-filter">
            <h3>Lọc theo giá tiền</h3>
            <div class="filter-price">

                <div id="price">
                    <input type="text"> -
                    <input type="text" >
                </div>
                <button id="btn-filter">Filter</button>
                
            </div>

            <h3 class="filter-title">Thể loại</h3>
            <ul class="category-list" id="categoryList">
                
            </ul>

            <h3 class="filter-title">Mạng xã hội</h3>
            <div class="social-row">
                
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
</body>
</html>