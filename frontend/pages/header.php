<div class = "header">
    <div class = "header_content">
        <a href = "index.php" class = "header_content-logo">
            <img class = "header_content-logo-img" src="https://cdn0.fahasa.com/skin/frontend/ma_vanese/fahasa/images/fahasa-logo.png" alt="">
        </a>
        <i class="fa-solid fa-bars icon_advanced"></i>
        
        <div class = "header_content-search">
            <form action="index.php?" method = "GET">
                <input type="hidden" name = "nameSearch" value = "findName">
                <input name = "inputSearchName" class = "header_content-search-input" placeholder = "Tìm kiếm tại đây" type="text">
                <!-- <input name = "inputSearchSubmit" class = "header_content-search-submit" type="submit" value = "Tìm kiếm"> -->
                <button class = "header_content-search-submit">Tìm kiếm</button>
            </form>
        </div>
        <ul class = "header_content-user">
         
            <li class = "element_cart">
                <i class="fa-solid fa-cart-shopping"></i>
                <a href = "index.php?title=cart">Giỏ hàng</a>
            </li>
            <?php 
                session_start();
                if(isset($_SESSION['account']))
                {
                    $nameLogin = $_SESSION['account']['fullname'];
                    ?>
                    <li>
                        <h3 style = "margin-bottom : 5px; margin-top : 0"><?php echo $nameLogin; ?></h3>
                        <a href="frontend/pages/login/unset_login.php">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        </a>
                    </li>
                <?php }
                else 
                {
                    ?>
                    <li>
                        <i class="fa-regular fa-user"></i>
                        <a href = "index.php?title=login" class = "text_account">Tài khoản</a>
                    </li>
                <?php }
            ?>
        </ul>
    </div>
</div>


<div class = "header_search-block">
    <form action = "index.php?" method = "GET" class = "header_search-advanced">
        <div class = "header_search-advanced-name">
            <h4>Tên sản phẩm</h4>
            <input name = "nameSearchAdvanced" type="text" class = "header_search-advanced-name-input">
        </div>
        <div class = "header_search-advanced-divide">
            <h4 class = "header_search-advanced-divide-text">Phân loại</h4>
            <select name = "search_select" class = "header_search-advanced-divide-select" name="" id="">
            <option value="0">Tất cả</option>
            <?php 
                include "backend/includes/config.php";
                $sql = "SELECT * FROM categories";
                $result = mysqli_query($connection, $sql);
                while($row = mysqli_fetch_array($result))
                {
                    ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class = "header_search-advanced-price">
            <div class = "header_search-advanced-price-from">
                <h4 class = "header_search-advanced-price-from-text">Giá từ</h4>
                <input name = "priceFrom" class = "header_search-advanced-price-from-input" type="text">
            </div>
            <div class = "header_search-advanced-price-to">
                <h4 class = "header_search-advanced-price-to-text">đến</h4>
                <input name = "priceTo" class ="header_search-advanced-price-to-input" type="text">
            </div>
        </div>
        <div class = "button_search-advanced">
            
            <input name = "search_advanced" class = "header_search-advanced-submit" type="submit" value = "Tìm kiếm sản phẩm">
        </div>
    </form>
</div>
<div class = "hidden_content-3">
    <div class = "content_searchAdvanced"></div>
</div>
<div class = "searchAdvanced_block">
    <ul class = "pagination_searchAdvanced"></ul>
</div>
<div class = "toast"></div>
<div class = "toast_fail"></div>


