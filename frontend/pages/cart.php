
<div class = "modal"> 
    <div class = "modal_base">  
        <div class = "cart">
            <a class = "detail_exit" href="index.php">X</a>
            <table  cellpadding = "10" cellspacing = "0">
                <?php 
                    if(isset($_SESSION['account']))
                    {
                        include "frontend/includes/config.php"; 
                        $idUser = $_SESSION['account']['id_user'];
                        ?>
                        <thead>
                            <tr >
                                <th style = "color : blue; font-size : 18px" colspan = "7" >Giỏ hảng : 
                                    <?php
                                        include "frontend/includes/config.php"; 
                                        if(isset($_SESSION['account']))
                                        {
                                            $idUser = $_SESSION['account']['id_user'];
                                            $sql = "SELECT * FROM users WHERE id = '$idUser'";
                                            $result = mysqli_query($connection, $sql);
                                            $row = mysqli_fetch_assoc($result);
                                            ?>
                                            <span style = "color : red; padding-left : 10px; font-size : 20px"><?php echo $row['fullname']; ?></span>
                                        <?php }
                                    ?>
                                </th> 
                            </tr>
                            <tr>
                                <th>STT</th>
                                <th>Ảnh sản phẩm</th>
                                <th>Tên sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Giá sản phẩm</th>
                                <th>Thành tiền</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $sql_total = "SELECT * FROM carts WHERE user_id = '$idUser'";
                                $result_total = mysqli_query($connection, $sql_total);
                                $row_total = mysqli_fetch_assoc($result_total);

                                $sql = "SELECT  products.*, cart_products.quantity as cart_quantity FROM  cart_products  INNER JOIN
                                products ON cart_products.product_id = products.id WHERE user_id = '$idUser'";
                                $result = mysqli_query($connection, $sql);
                                $number = 0;
                                while($row = mysqli_fetch_array($result))
                                {
                                    $number = $number + 1;
                                    ?>
                                    <input class = "hidden_idUser" type="hidden" value = <?php echo $idUser; ?> >
                                    <tr>
                                        <td><?php echo $number; ?></td>
                                        <td><img src="<?php echo $row['image']; ?>" alt=""></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td style = "width : 150px">
                                            <div class = "parent_quantity">
                                                <span class = "increase_quantity" data-id-product = <?php echo $row['id']; ?> >+</span>
                                                <input class = "input_quantity" type="text" value = "<?php echo $row['cart_quantity']; ?>">
                                                <span class = "decrease_quantity" data-id-product = <?php echo $row['id']; ?>>-</span>
                                            </div>
                                        </td>

                                        <td data-price = "<?php echo $row['price']; ?>" class = "price_product"><?php echo $row['price']; ?></td>
                                        <td class = "total_price-cart"><?php echo $row['cart_quantity'] * $row['price']; ?></td>
                                        <td class = "delete_cart">
                                            <a href="frontend/pages/delete_cart.php?id_cart=<?php echo $row['id']; ?>&id_user=<?php echo $idUser; ?>">Xóa</a>
                                        </td>
                                    </tr>
                              <?php } 
                                    if($row_total > 0)
                                    {
                                        ?>
                                        <tr>
                                            <td style = "font-size : 20px; font-family: Roboto, sans-serif;"  colspan = "7">
                                                <div class = "payment_cart-total">
                                                    <h5>Tổng tiền</h5>
                                                    <span class = "total_price-cart-user"><?php echo $row_total['total_price']; ?></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan = "7">
                                                <div class = "payment_cart-text">
                                                    <a href ="payment.php">Thanh toán</a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                              ?>
                                </tbody>
                                 <?php } 
                            else 
                            {
                                ?>
                                <h2 class = "notification_cart">Vui lòng đăng nhập để xem giỏ hàng của bạn để tiến hành thanh toán</h2>
                                <img class = "cart_empty" src="frontend/image/cart_empty.jpg" alt="">
                            <?php } ?>
                    </table>
                    
        </div>
    </div>
</div>

