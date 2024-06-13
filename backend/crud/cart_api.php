<?php 
    $choice = $_POST['choice'];
    switch($choice)
    {
        case 'display_cart':
            DisplayCart();
            break;
        case 'quantity_cart':
            QuantityCart();
            break;
        case 'delete_cart':
            DeleteCart();
            break;
        case 'add_cart':
            AddCart();
            break;
    }
    function DisplayCart()
    {
        include "../../frontend/includes/config.php"; 
        session_start();
        $idUser = isset($_SESSION['account']['id_user']) ? $_SESSION['account']['id_user'] : null;
        if($idUser != null)
        {

            $sql = "SELECT users.id as idUser, users.fullname as nameUser, products.*, cart_products.product_id as idCartProduct, cart_products.quantity as cart_quantity 
            FROM users 
            INNER JOIN cart_products ON users.id = cart_products.user_id
            INNER JOIN products ON cart_products.product_id = products.id 
            WHERE user_id = '$idUser'";
            $result = mysqli_query($connection, $sql);
            $data = new stdClass();
            $informations = array();
            while($row = mysqli_fetch_array($result))
            {
                $informations[] = $row;
            }
            $data->informations = $informations;
            $data->idUser = $_SESSION['account']['id_user'];
            $data->nameUser = $_SESSION['account']['fullname'];
            echo json_encode($data);
        }
        else 
        {
            echo json_encode(array("status" => "Vui lòng đăng nhập để xem giỏ hàng của bạn để tiến hành thanh toán"));
        }
    }

    function QuantityCart()
    {
        include "../../frontend/includes/config.php";
        $idProduct = isset($_POST['id_product']) ? $_POST['id_product'] : "";
        $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : "";
        $idUser = isset($_POST['id_user']) ? $_POST['id_user'] : "";
        
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
    }
    function DeleteCart()
    {
        include "../../frontend/includes/config.php";
        if(isset($_POST['id_cart']) && isset($_POST['id_user']));
        {
            $idCart = $_POST['id_cart'];
            $idUser = $_POST['id_user'];
            $sql = "DELETE FROM cart_products WHERE product_id = '$idCart' AND user_id = '$idUser'";
            mysqli_query($connection, $sql);
            
            $upate_total = "SELECT products.price, cart_products.quantity as cart_quantity FROM cart_products INNER JOIN products ON cart_products.product_id = products.id WHERE user_id = '$idUser' ";
            $result_update_total = mysqli_query($connection, $upate_total);
            
            $totalPrice = 0;
            while($row_update_total = mysqli_fetch_array($result_update_total))
            {
                $totalPrice = $totalPrice + ($row_update_total['cart_quantity'] * $row_update_total['price']) ;
            }
            if($totalPrice == 0)
            {
                $update_cart = "DELETE FROM carts WHERE user_id = '$idUser'";
            }
            else 
            {
                $update_cart = "UPDATE carts SET total_price = '$totalPrice' WHERE user_id = '$idUser'";
            }
            mysqli_query($connection, $update_cart); 
        }
    }
    function AddCart()
    {
        include "../../frontend/includes/config.php";
        session_start();
        $idProduct = isset($_POST['id_product']) ? $_POST['id_product'] : "";
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
                    echo json_encode(array("status" => "Sản phẩm đã được thêm vào thành công"));
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
            else 
            {
                echo json_encode(array("status" => "Vui lòng đăng nhập"));
            }
        }
    }
?>