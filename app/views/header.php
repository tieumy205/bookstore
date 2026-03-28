

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookiary</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Grape+Nuts&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= BASE_URL ?>app/assets/css/header-footer-style.css">
    <script>var BASE_URL = "<?= BASE_URL ?>";</script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>

    <header class="header">
        <div class="container d-flex">
            <div>
                <div class="menu d-flex">
                    <div><a href="<?= BASE_URL ?>home" class="logo-link">
                            <img src="<?= BASE_URL ?>app/assets/images/logo.png" alt="Bookiary Logo" class="logo">
                        </a>
                    </div>
                    
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            Thể loại
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" id= "categoryContainer">
                            
                        </ul>
                    </div>
                    <div><a href="#">Liên hệ</a></div>
                </div>
            </div>
        
            <div class="btn-group d-flex">
                <form action="#" method="GET" class="search-box">
                    <input type="text" name="search" placeholder="Tìm kiếm" id="searchInput">
                    <button type="submit" class="search-btn">
                        <i class="bi bi-search"></i>
                    </button>
                </form>

                <div class="cart-menu">
                    <a href="<?= BASE_URL ?>cart" class="cart-icon">
                        <i class="bi bi-cart3"></i>
                        
                    </a>

                </div>

                <div class="user-menu d-flex">
                    <i class="bi bi-person" id="user-icon"></i>
                    
                    <div class="user-dropdown" id="userDropdown">
                        
                    </div>
                </div>
            </div>
        </div>
    </header>

    <script src="<?= BASE_URL ?>app/assets/js/header.js"></script>
</body>
</html>