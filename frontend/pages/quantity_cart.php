<?php 
    include "../includes/config.php";
    $idProduct = isset($_GET['id_product']) ? $_GET['id_product'] : "";
    $quantity = isset($_GET['quantity']) ? $_GET['quantity'] : "";
    $idUser = isset($_GET['id_user']) ? $_GET['id_user'] : "";
    
    $sql = "UPDATE cart_products SET quantity = '$quantity' WHERE product_id = '$idProduct'";
    mysqli_query($connection, $sql);
    

    $upate_total = "SELECT products.*, cart_products.quantity as cart_quantity FROM cart_products INNER JOIN products ON cart_products.product_id = products.id WHERE user_id = '$idUser' ";
    $result_update_total = mysqli_query($connection, $upate_total);
    
    $totalPrice = 0;
    while($row_update_total = mysqli_fetch_array($result_update_total))
    {
        $totalPrice = $totalPrice + ($row_update_total['cart_quantity'] * $row_update_total['price']) ;
    }
    $update_cart = "UPDATE carts SET total_price = '$totalPrice' WHERE user_id = '$idUser'";
    mysqli_query($connection, $update_cart); 
    header('location: ../../index.php?title=cart');
?>