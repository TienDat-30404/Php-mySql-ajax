    <?php 
        include "../includes/config.php";
        session_start();
        $idProduct = isset($_GET['id_product']) ? $_GET['id_product'] : "";
        $sql = "SELECT * FROM products WHERE id = '$idProduct' LIMIT 1";
        $result = mysqli_query($connection, $sql);
        if(mysqli_num_rows($result) > 0)
        {
            $row = mysqli_fetch_array($result);
            $number = 1;
            if(isset($_SESSION['account']))
            {
                $idUser = $_SESSION['account']['id_user'];
                $sql_insert_cart = "SELECT * FROM carts WHERE user_id = '$idUser'";
                $result_insert_cart = mysqli_query($connection, $sql_insert_cart);
                if(mysqli_num_rows($result_insert_cart) == 0)
                {
                    $priceCart = $row['price'];
                    $insert_cart = "INSERT INTO carts(user_id, total_price) VALUES ('$idUser', '$priceCart')";
                    mysqli_query($connection, $insert_cart);
                }
                

                $sql_insert = "SELECT * FROM cart_products WHERE user_id = '$idUser' AND product_id = '$idProduct' ";
                $result_insert = mysqli_query($connection, $sql_insert);
                if(mysqli_num_rows($result_insert) == 0)
                {
                    $insert_cart_product = "INSERT INTO cart_products(user_id, product_id, quantity) VALUES ('$idUser', '$idProduct', '$number')";
                    mysqli_query($connection, $insert_cart_product);
                }
                else 
                {
                    echo json_encode(array("status" => "Sản phẩm đã được thêm vào giỏ hàng"));
                    
                }
                if(mysqli_num_rows($result_insert_cart) > 0) 
                {
                    $upate_total = "SELECT products.*, cart_products.quantity as cart_quantity FROM cart_products INNER JOIN products ON cart_products.product_id = products.id WHERE user_id = '$idUser' ";
                    $result_update_total = mysqli_query($connection, $upate_total);
                    
                    $totalPrice = 0;
                    while($row_update_total = mysqli_fetch_array($result_update_total))
                    {
                        $totalPrice = $totalPrice + ($row_update_total['cart_quantity'] * $row_update_total['price']) ;
                    }
                    $update_cart = "UPDATE carts SET total_price = '$totalPrice' WHERE user_id = '$idUser'";
                    mysqli_query($connection, $update_cart); 
                }
                
            }
        }

    ?>