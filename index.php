<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="frontend/css/category.css">
    <link rel="stylesheet" href="frontend/css/content.css">
    <link rel="stylesheet" href="frontend/css/detail.css">
    <link rel="stylesheet" href="frontend/css/header.css">
    <link rel="stylesheet" href="frontend/css/banner.css">
    <link rel="stylesheet" href="frontend/css/login.css">
    <link rel="stylesheet" href="frontend/css/cart.css">
    <link rel="stylesheet" href="frontend/css/payment.css">
    <link rel="stylesheet" href="frontend/css/footer.css">
</head>
<body>
    <div class = "container">
    <div class = "detail_block"></div>
        <?php 

            include "frontend/includes/config.php";
            include "frontend/pages/header.php";
            include "frontend/pages/content.php";
         ?>
         
    </div>
    <script src = "frontend/js/style.js"></script>
    <script src = "frontend/js/banner.js"></script>
    <script src = "frontend/js/quantity_cart.js"></script>
</body>
</html>