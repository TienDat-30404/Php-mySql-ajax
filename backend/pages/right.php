<div class = "container_right">
    <div class = "container_right-header">
        <i class="fa-solid fa-bars"></i>
        <div class = "container_right-header-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder = "Search here">
        </div>
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
                else if($title = 'order')
                {
                    include "content/order.php";
                }
            }
        ?>
    </div>
</div>