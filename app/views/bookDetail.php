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
    <link rel="stylesheet" href="<?= BASE_URL ?>app/assets/css/bookDetail.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>var BASE_URL = "<?= BASE_URL ?>"</script>
</head>
<body>
    
<div class="product-detail-container">
    <div class="container">
        <div class="row product-card-detail">
            
            <div class="col-md-4">
                <div class="product-images">
                    <div class="main-image">
                        <img id="productImage" src="" alt="" class="img-fluid">
                    </div>
                    
                </div>
            </div>

            
            <div class="col-md-8">
                <div class="product-info-detail">
                    <h1 id="productName"></h1>
                    
                    <div id="productPrice"></div>
                    
                    <div class="product-description">
                        
                        <p id="productDescription"></p>
                    </div>

                    <div class="product-actions">
                        <div class="button">
                            <button class="btn btn-primary btn-addCart" id="add-to-cart" data-product-id="">THÊM VÀO GIỎ HÀNG</button>
                            <button class="btn btn-success btn-buyNow" id="buy-now" data-product-id="">MUA NGAY</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="product-details-tabs mt-5">
            <ul class="nav nav-tabs nav-underline" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">Mô tả </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ingredients-tab" data-bs-toggle="tab" data-bs-target="#ingredients" type="button" role="tab">Thông tin chi tiết</button>
                </li>
                
            </ul>
            <div class="tab-content" id="productTabsContent">
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    
                </div>
                <div class="tab-pane fade " id="info" role="tabpanel">
                    
                </div>
                
            </div>
        </div>

        
        <div class="related-products mt-5">
            <h3>Sản phẩm liên quan</h3>
            <div id="related-products" class="owl-carousel owl-theme products">
            
            </div>
        </div>
    </div>
</div>
<script src="<?= BASE_URL ?>app/assets/js/jquery.min.js"></script>
<script src="<?= BASE_URL ?>app/assets/js/owl.carousel.min.js"></script>
<script src="<?= BASE_URL ?>app/assets/js/bookDetail.js"></script>
</body>
</html>