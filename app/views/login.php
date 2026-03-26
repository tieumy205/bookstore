<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>app/assets/css/login-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script>var BASE_URL = "<?= BASE_URL ?>";</script>
</head>
<body>
    <div class="no-wrap">
        <div class="container">
            <div class="login-container">
                <h2>Đăng nhập</h2>
                
                <form id="loginForm">
                    <input type="text" class="form-control" id="username" placeholder="Tên đăng nhập" required>
                    <input type="password" class="form-control" id="password" placeholder="Mật khẩu" required>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="check">
                            <label class="form-check-label" for="check">Nhớ mật khẩu</label>
                        </div>
                        <a href="#" class="forgot-link">Quên mật khẩu?</a>
                    </div>
                    
                    <button type="submit" class="btn-primary">Đăng nhập</button>
                    
                    <div class="signup-text">
                        Bạn chưa có tài khoản? <a href="<?= BASE_URL ?>/register">Đăng ký</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="<?= BASE_URL ?>app/assets/js/login.js"></script>
</body>
</html> 