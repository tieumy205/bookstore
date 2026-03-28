

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
    <script>var BASE_URL = "<?= BASE_URL ?>";</script>
    <link rel="stylesheet" href="<?= BASE_URL ?>app/assets/css/checkout.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>

<div id="checkout-container" class="container mt-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="items">
                <div class="item-info">
                    <div id="user-info">
                        <h2 style="font-size: 20px;">Thông tin nhận hàng</h2>
                        <p><strong>Họ tên:</strong> <span id="consigneeNameCheckout"></span></p>
                        <p><strong>Số điện thoại:</strong> <span id="numberPhoneCheckout"></span></p>
                        <p><strong>Địa chỉ:</strong> <span id="addressCheckout"></span></p>
                    </div>
                    <button type="button" id="edit-address" class="btn btn-link-primary">
                        Thay đổi
                    </button>
                </div>
                
                    
                
                <div class="item checkout-section" >
                    <h3>Đơn hàng của bạn</h3>

                    <div id="productContainer">
                        
                    </div>
                </div>

                <div class="item checkout-section">
                        <h3>Phương thức thanh toán</h3>

                        <select id="paymentMethod">
                            <option value="cash">Thanh toán khi nhận hàng</option>
                            <option value="bank-transfer">Chuyển khoản</option>
                            <option value="online">Thanh toán trực tuyến</option>
                        </select>
                </div>

            </div>
        </div>
        <div class="col-lg-4">
          <div class="order-summary">
            <h3>Thông tin đơn hàng</h4>
            <div class="summary-item">
                <span>Tạm tính</span>
                <span id="subtotal">0đ</span>
            </div>
            <div class="summary-item">
                <span>Phí giao hàng</span>
                <span>50.000đ</span>
            </div>
            <div class="summary-item total">
                <span>Tổng</span>
                <span id="total">0đ</span>
            </div>
            <button class="button-checkout" onclick="checkout()">THANH TOÁN NGAY</button>
        </div>
        </div>
    </div>
    

</div>




<!-- Modal -->
<div class="modal fade" id="modalEditAddress">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Thay đổi thông tin nhận hàng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalBody">
        
        <div id="formAddress">
            
        </div>
      </div>
      <div class="modal-footer">
       
        <button type="button" class="btn btn-primary" id="addAddress" data-bs-target="#addInfo" data-bs-toggle="modal"><i class="bi bi-plus-lg"></i>Thêm địa chỉ</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="updateInfo"  >
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Cập nhật địa chỉ</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="updateAddress">
        
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" data-bs-target="#modalEditAddress" data-bs-toggle="modal">Trở về</button>
        <button class="btn btn-secondary" id="saveAddress" onclick="updateAddress()">Lưu thay đổi</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="addInfo">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Thêm địa chỉ</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="addInfoForm">
        <div class="formInfo" style = "display: flex; justify-content: space-between">
                        <input value="" placeholder="Họ và tên" type="text" class="form-control" id="consigneeNameCheck" required>
                        <input value="" placeholder="Số điện thoại" type="tel" class="form-control" id="numberPhoneCheck" required>
                    </div>
                    <div class="formSelect" style = "display: flex; justify-content: space-between">
                        <select name="" id="provinceCheck">
                            <option value="">Chọn tỉnh thành phố</option>
                        </select>

                        <select name="" id="districtCheck">
                            <option value="">Chọn quận/huyện</option>
                        </select>
                    </div>

                    <input value="" placeholder="Địa chỉ chi tiết" type="text" class="form-control" id="detailAddressCheck" required>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" data-bs-target="#modalEditAddress" data-bs-toggle="modal">Trở về</button>
        <button class="btn btn-secondary" id="addBtn" onclick="addAddress()">Hoàn thành</button>
      </div>
    </div>
  </div>
</div>

<script src="<?= BASE_URL ?>app/assets/js/checkout.js"></script>
<script src="<?= BASE_URL ?>app/assets/js/header.js"></script>

</body>
</html>









