

    <div class = "container_right-content">
        <?php   
            if(isset($_GET['title']))
            {
                $title = $_GET['title'];
                if($title == 'accountExist')
                {
                    ?>
                    <div class = "container_right">
                        <div class = "container_right-header">
                            <!-- <i class="fa-solid fa-bars"></i> -->
                            <select name="" id="select_search-user">
                                <option value="0">Tất cả</option>
                                <option value="1">Tìm kiếm theo mã</option>
                                <option value="2">Tìm kiếm tên</option>
                                <option value="3">Tìm kiếm email</option>
                                <option value="4">Tìm kiếm theo số điện thoại</option>
                            </select>
                            <div style = "width : 300px" class = "container_right-header-search">
                                <input name = "inputName" style = "text-align : center;" type="text" placeholder = "Search here">
                            </div>
                            <input class = "button_search" type="submit" value = "Tìm kiếm">
                            <img class = "avatar_admin" src="image/user.png" alt="">
                            <div class = "logout_admin">
                                <a href = "../frontend/pages/login/unset_login.php">Đăng xuất</a>
                            </div>
                        </div>
                    <?php 
                    include "content/accountExist.php"; 
                }
                else if($title == 'accountDelete')
                {
                    include "content/accountDelete.php";
                }
                else if($title == 'product')
                {
                    ?>
                    <div class = "container_right">
                        <div class = "container_right-header">
                            <!-- <i class="fa-solid fa-bars"></i> -->
                            <select name="" id="select_search-product">
                                <option value="0">Tất cả</option>
                                <option value="1">Tìm kiếm theo mã</option>
                            </select>
                            Giá từ <input name = "inputFrom" type="text"> đến <input name = "inputTo" type="text">
                            <div style = "width : 300px" class = "container_right-header-search">
                                <input name = "inputName" style = "text-align : center;" type="text" placeholder = "Search here">
                            </div>
                            <input class = "button_search" type="submit" value = "Tìm kiếm">
                            <img class = "avatar_admin" src="image/user.png" alt="">
                            <div class = "logout_admin">
                                <a href = "../frontend/pages/login/unset_login.php">Đăng xuất</a>
                            </div>
                        </div>
                    <?php 
                    include "content/product.php";
                }
                else if($title == 'category')
                {
                    ?>
                    <div class = "container_right">
                        <div class = "container_right-header">
                            <!-- <i class="fa-solid fa-bars"></i> -->
                            <select name="" id="select_search-category">
                                <option value="0">Tất cả</option>
                                <option value="1">Tìm kiếm theo mã</option>
                                <option value="2">Tìm kiếm theo tên</option>
                            </select>
                            <div style = "width : 300px" class = "container_right-header-search">
                                <input name = "name_search-category" style = "text-align : center;" type="text" placeholder = "Search here">
                            </div>
                            <input class = "button_search" type="submit" value = "Tìm kiếm">
                            <img class = "avatar_admin" src="image/user.png" alt="">
                            <div class = "logout_admin">
                                <a href = "../frontend/pages/login/unset_login.php">Đăng xuất</a>
                            </div>
                        </div>
                    <?php 
                    include "content/category.php";
                }
                else if($title == 'author')
                {
                    ?>
                    <div class = "container_right">
                        <div class = "container_right-header">
                            <!-- <i class="fa-solid fa-bars"></i> -->
                            <select name="" id="select_search-author">
                                <option value="0">Tất cả</option>
                                <option value="1">Tìm kiếm theo mã</option>
                                <option value="2">Tìm kiếm theo tên</option>
                            </select>
                            <div style = "width : 300px" class = "container_right-header-search">
                                <input name = "name_search-author" style = "text-align : center;" type="text" placeholder = "Search here">
                            </div>
                            <input class = "button_search" type="submit" value = "Tìm kiếm">
                            <img class = "avatar_admin" src="image/user.png" alt="">
                            <div class = "logout_admin">
                                <a href = "../frontend/pages/login/unset_login.php">Đăng xuất</a>
                            </div>
                        </div>
                    <?php 
                    include "content/author.php";
                }
                
                else if($title == 'publisher')
                {
                    ?>
                    <div class = "container_right">
                        <div class = "container_right-header">
                            <!-- <i class="fa-solid fa-bars"></i> -->
                            <select name="" id="select_search-publisher">
                                <option value="0">Tất cả</option>
                                <option value="1">Tìm kiếm theo mã</option>
                                <option value="2">Tìm kiếm theo tên</option>
                            </select>
                            <div style = "width : 300px" class = "container_right-header-search">
                                <input name = "name_search-publisher" style = "text-align : center;" type="text" placeholder = "Search here">
                            </div>
                            <input class = "button_search" type="submit" value = "Tìm kiếm">
                            <img class = "avatar_admin" src="image/user.png" alt="">
                            <div class = "logout_admin">
                                <a href = "../frontend/pages/login/unset_login.php">Đăng xuất</a>
                            </div>
                        </div>
                    <?php 
                    include "content/publisher.php";
                }
                else if($title == 'order')
                {
                    ?>
                    <div class = "container_right">
                        <div class = "container_right-header">
                            <!-- <i class="fa-solid fa-bars"></i> -->
                            <select name="" id="select_search-order">
                                <option value="0">Tất cả</option>
                                <option value="1">Tìm kiếm theo mã đơn hàng</option>
                                <option value="2">Tìm kiếm theo tên khách hàng</option>
                                <option value="3">Chờ xử lí</option>
                                <option value="4">Đã xử lí</option>
                            </select>
                            <div style = "width : 200px" class = "container_right-header-search">
                                <input name = "name_search-order" style = "text-align : center;" type="text" placeholder = "Search here">
                            </div>
                            <input name = "date_from" type="date"> đến <input name = "date_to" type="date">
                            <input class = "button_search" type="submit" value = "Tìm kiếm">
                            <img class = "avatar_admin" src="image/user.png" alt="">
                            <div class = "logout_admin">
                                <a href = "../frontend/pages/login/unset_login.php">Đăng xuất</a>
                            </div>
                        </div>
                    <?php 
                    include "content/order.php";
                }
                else if($title == 'receipt')
                {
                    ?>
                    <div class = "container_right">
                        <div class = "container_right-header">
                            <!-- <i class="fa-solid fa-bars"></i> -->
                            <select name="" id="select_search-receipt">
                                <option value="0">Tất cả</option>
                                <option value="1">Tìm kiếm theo mã phiếu nhập</option>
                            </select>
                            <div style = "width : 200px" class = "container_right-header-search">
                                <input name = "name_search-receipt" style = "text-align : center;" type="text" placeholder = "Search here">
                            </div>
                            <input name = "date_from" type="date"> đến <input name = "date_to" type="date">
                            <input type="text" name = "price_from"> đến <input type="text" name = "price_to">
                            <input class = "button_search" type="submit" value = "Tìm kiếm">
                            <img class = "avatar_admin" src="image/user.png" alt="">
                            <div class = "logout_admin">
                                <a href = "../frontend/pages/login/unset_login.php">Đăng xuất</a>
                            </div>
                        </div>
                    <?php 
                    include "content/receipt.php";
                }
                else if($title == 'statistical')
                {
                    include "content/statistical.php";
                }
                else if($title == 'dashboard')
                {
                    include "content/dashboard.php";
                }
            }
        ?>
    </div>
</div>


<script>
    var blockLogoutAdmin = document.querySelector('.logout_admin')
    blockLogoutAdmin.style.display = 'none'
    document.querySelector('.avatar_admin').addEventListener('click', function(e)
    {
        if(blockLogoutAdmin.style.display == 'none')
        {
            blockLogoutAdmin.style.display = 'block'
        }
        else 
        {
            blockLogoutAdmin.style.display = 'none'
        }
    })
</script>