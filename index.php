<?php
    ob_start();
    require "config/config.php";
    require "config/db.php";
    require "app/core/controller.php";
    require "app/core/Router.php";


    $url = $_GET['url'] ?? '';
    $router = new Router();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <base href="http://localhost/bookstore/"> -->
    <script>var BASE_URL = "<?= BASE_URL ?>";</script>
    <link rel="stylesheet" href="<?= BASE_URL ?>app/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>app/assets/css/owl.theme.default.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    
    

</head>
<body>
    <?php require "app/views/header.php"; ?>

    <?php $router->dispatch($url); ?>

    <?php require "app/views/footer.php"; ?>
</body>
</html>
