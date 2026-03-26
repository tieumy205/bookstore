<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng</title>
    <script>var BASE_URL = "<?= BASE_URL ?>";</script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>app/assets/css/order.css">
</head>
<body>

    <div class="container mt-5">
        <h2>Chi tiết đơn hàng</h2>

        <!-- Thông tin người nhận -->
        <div class="card mb-3">
            <div class="card-header ">Thông tin nhận hàng</div>
            <div class="card-body" id="userInfo">
                
            </div>
        </div>

        <!-- Sản phẩm -->
        <div class="card mb-3">
            <div class="card-header ">Sản phẩm</div>
            <div class="card-body" id="orderItems">

            </div>
        </div>

        <!-- Thanh toán -->
        <div class="card">
            <div class="card-header">Thanh toán</div>
            <div class="card-body" id="paymentInfo">
                
            </div>
        </div>
    </div>
    <script src="<?= BASE_URL ?>app/assets/js/order.js"></script>
</body>
</html>