

    <div class = "container_right-content">
        <?php   
            if(isset($_GET['title']))
            {
                $title = $_GET['title'];
                if($title == 'accountExist')
                {
                    include "content/accountExist.php";  
                    if(isset($_GET['action']))
                    {
                        $action = $_GET['action'];
                        if($action == 'add')
                        {
                            include "crud/add_user.php";
                        }
                        else if($action == 'edit')
                        {
                            include "crud/edit_user.php";
                        }
                    }
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
                            <img src="image/user.png" alt="">
                        </div>
                    <?php 
                    include "content/product.php";
                    if(isset($_GET['action']))
                    {
                        $action = $_GET['action'];
                        if($action == 'add')
                        {
                            include "crud/add_product.php";
                        }
                    }
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
                            <img src="image/user.png" alt="">
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
                            <img src="image/user.png" alt="">
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
                            <img src="image/user.png" alt="">
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
                            <input type="date"> đến <input type="date">
                            <input class = "button_search" type="submit" value = "Tìm kiếm">
                            <img src="image/user.png" alt="">
                        </div>
                    <?php 
                    include "content/order.php";
                }
                else if($title == 'receipt')
                {
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