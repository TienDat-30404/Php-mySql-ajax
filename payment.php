<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="frontend/css/payment.css">
</head>
<body>
    <div class = "payment">
    <?php 
    include "frontend/includes/config.php";
    session_start();
    if(isset($_SESSION['account']))
    {
        $idUser = $_SESSION['account']['id_user'];
        $sql = "SELECT products.*, cart_products.quantity as quantityCart, carts.* FROM carts INNER JOIN cart_products
            ON carts.user_id = cart_products.user_id INNER JOIN products ON cart_products.product_id = products.id WHERE 
        cart_products.user_id = '$idUser'";
        $result = mysqli_query($connection, $sql);
        $informations = array();
        while($row = mysqli_fetch_array($result))
        {
            $informations[] = $row;
        }
        $select_total = $informations[0];
        $total_price = $select_total['total_price'];
    ?>
        <form method = "POST" action = "payment.php" class = "payment_information">
            <div class = "payment_information-user">
                <h3 class = "payment_title-text">Thông tín khách hàng</h3>
                <ul class = "payment_information-user-order">
                    <li>
                        <h4 class = "payment_information-user-order-method">Phương thức thanh toán</h4>
                        <select name="select_payment_method" id="">
                            <option value="Momo">Momo</option>
                            <option value="Ngân hàng">Ngân hàng</option>
                            <option value="Tiền ảo">Tiền ảo</option>
                        </select>
                    </li>
                    <li>
                        <h4>Số tài khoản</h4>
                        <input name = "input_bank_account" type="text">
                    </li>
                    <li>
                       <h4>Địa chỉ nhận hàng</h4>
                       <textarea name="input_address" id="" cols="50" rows="10"></textarea>
                    </li>
                </ul>
            </div>
            <div class = "payment_information-product">
                <div class = "payment_information-prodcut-title">
                    <h2>Đơn hàng</h2>
                </div>
                <ul class = "payment_information-product-order">
                    <li>
                        <h3>Sản phẩm</h3>
                        <?php 
                            foreach($informations as $value)
                            {
                                ?>
                                <h4><?php echo $value['name']; ?></h4>
                            <?php }
                        ?>
                    </li>
                    <li>
                        <h3>Số lượng</h3>
                        <?php 
                            foreach($informations as $value)
                            {
                                ?>
                                <h4><?php echo $value['quantityCart']; ?></h4>
                            <?php }
                        ?>
                    </li>
                    <li>
                        <h3>Thành tiền</h3>
                        <?php
                            foreach($informations as $value)
                            {
                                ?>
                                    <h4><?php echo $value['price']; ?></h4>
                            <?php } 
                        ?>
                    </li>
                </ul>
                <div class = "payment_information-product-total">
                    <h3 class = "payment_information-product-total-text">Tổng tiền</h3>
                    <h3 class = "payment_information-product-total-number"><?php echo $total_price; ?></h3>
                </div>
                <input name = "button_payment" type = "submit" class = "payment_button" value = "Đặt hàng"></input>
            </div>
        </form>
        <?php } ?>
    </div>
</body>
</html>

<?php 
    include "frontend/includes/config.php";
    if(isset($_POST['button_payment']))
    {
        $idUser = $_SESSION['account']['id_user'];
        $statusOrder = 1;
        $inputPaymentMethod = $_POST['select_payment_method'];
        $inputBankAccount = $_POST['input_bank_account'];
        $inputAdress = $_POST['input_address'];
        $sql_total = "SELECT * FROM carts WHERE user_id = '$idUser'";
        $result_total = mysqli_query($connection, $sql_total);
        $row_total = mysqli_fetch_assoc($result_total);
        $totalPrice = $row_total['total_price'];

        $sql = "INSERT INTO bills(user_id, total_price, address, payment_method, bill_status_id, number_account_bank) 
        VALUES( '$idUser', '$totalPrice', '$inputAdress', '$inputPaymentMethod', '$statusOrder', '$inputBankAccount')";
        mysqli_query($connection, $sql);

        $lastIdBill = mysqli_insert_id($connection);
        $sqlBillDetail = "SELECT bills.id as idBill, products.id as idProduct, products.*, cart_products.quantity as quantityCart FROM 
        products INNER JOIN cart_products ON products.id = cart_products.product_id INNER JOIN bills ON cart_products.user_id = bills.user_id
        WHERE bills.user_id = '$idUser' AND bills.id = '$lastIdBill'";
        $result = mysqli_query($connection, $sqlBillDetail);
        while($row = mysqli_fetch_array($result))
        {
            $idBill = $row['idBill'];
            $idProduct = $row['idProduct'];
            $quantity = $row['quantityCart'];
            $price = $row['price'];
            $insertBillDetail = "INSERT INTO bill_details(bill_id, product_id, quantity, price) VALUE('$idBill', '$idProduct', '$quantity',
            '$price')";
            mysqli_query($connection, $insertBillDetail);
        }
        $sqlDeleteCartProduct = "DELETE FROM cart_products WHERE user_id = '$idUser'";
        mysqli_query($connection, $sqlDeleteCartProduct);
        $sqlDeleteCart = "DELETE FROM carts WHERE user_id = '$idUser'";
        mysqli_query($connection, $sqlDeleteCart);
        header('location: index.php');
    }
?>