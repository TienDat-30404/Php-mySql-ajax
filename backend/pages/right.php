
<div class = "container_right">
    <div class = "container_right-header">
        <i class="fa-solid fa-bars"></i>
        <select name="" id="select_search-product">
            <option value="0">Tất cả</option>
            <option value="1">Tìm kiếm theo mã</option>
            <option value="2">Tìm kiếm theo tên</option>
        </select>
        <div class = "container_right-header-search">
            <input style = "text-align : center" type="text" placeholder = "Search here">
        </div>
        <input class = "button_search" type="submit" value = "Tìm kiếm">
        <img src="image/user.png" alt="">
    </div>
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
                    include "content/category.php";
                }
                else if($title == 'author')
                {
                    include "content/author.php";
                }
                else if($title == 'publisher')
                {
                    include "content/publisher.php";
                }
                else if($title == 'order')
                {
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