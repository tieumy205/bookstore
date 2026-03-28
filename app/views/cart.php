<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script>var BASE_URL = "<?= BASE_URL ?>"</script>
    <link rel="stylesheet" href="<?= BASE_URL ?>/app/assets/css/cart.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container-shopping">
        <h1 class="mb-4" style="text-align: center;">GIỎ HÀNG CỦA BẠN</h1>
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div id="cartHeader" class="cart-header align-items-center mb-2" style="padding-left: 35px;">
                    <input type="checkbox" id="selectAll" class="me-2 item-checkbox" onchange="toggleSelectAll(this)">
                    <label for="selectAll" class="mb-0">Chọn tất cả</label>
                </div>
                <div class="cart-items" id="cartItemsList"></div>
            </div>
            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="order-summary">
                    <h3>Thông tin đơn hàng</h4>
                    <div class="summary-item">
                        <span>Tạm tính</span>
                        <span id="subtotal">0đ</span>
                    </div>
                    
                    <div class="summary-item total">
                        <span>Tổng</span>
                        <span id="total">0đ</span>
                    </div>
                    <button class="btn-checkout" onclick="getCheckout()">THANH TOÁN NGAY</button>
                </div>
            </div>
    </div>

    <script src="<?=BASE_URL?>/app/assets/js/cart.js"></script>
    <script src="<?= BASE_URL ?>app/assets/js/header.js"></script>
</body>
</html>