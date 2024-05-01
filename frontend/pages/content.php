<?php 
    if(isset($_GET['nameSearch']))
    {
        $nameSearch = $_GET['nameSearch'];
        if($nameSearch=="findName")
        {
            ?>
            <div class = "content_search"></div>
            <ul class = "pagination_search"></ul>
        <?php }
    }
    else 
    {
        include "frontend/pages/banner.php";
        include "frontend/pages/category.php";
        if(isset($_GET['title']))
        {
            $title = $_GET['title'];
            if($title=='login')
            {
                include "login/login.php";
            }
            else if($title=='signup')
            {
                include "signup/signup.php";
            }
            else if($title=='cart')
            {
                include "cart.php";
            }
        }
        else 
        {
            ?>
                <div class = "content"></div>
                <ul class = "pagination_homepage"></ul>
                <div class = "content_category"></div>
                <ul class = "pagination_category"></ul>
        <?php }
        include "footer.php";
    }
?>