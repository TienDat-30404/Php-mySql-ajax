<?php 
    include "../includes/config.php";
    if(isset($_GET['id_cart']) && isset($_GET['id_user']));
    {
        $idCart = $_GET['id_cart'];
        $idUser = $_GET['id_user'];
        $sql = "DELETE FROM cart_products WHERE product_id = '$idCart'";
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
    }
?>