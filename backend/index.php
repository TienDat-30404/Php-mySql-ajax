<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/left.css">
    <link rel="stylesheet" href="css/right.css">
    <link rel="stylesheet" href="css/addProduct.css">
    <link rel="stylesheet" href="css/editProduct.css">
    <link rel="stylesheet" href="css/editCategory.css">
    <link rel="stylesheet" href="css/detailOrder.css">

</head>
<body>
    <?php 
        include "crud/check_loginAdmin.php";
    ?>
    <div class = "container_content">
        <?php
            include "pages/left.php"; 
            include "pages/right.php";
        ?>
        
    </div>
</body>
</html>