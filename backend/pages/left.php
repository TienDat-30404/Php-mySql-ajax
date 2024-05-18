<div class = "container_left">
    <ul class = "container_left-content">
        <a href="">
            <li>
                <i class="fa-solid fa-house"></i>
                <h3>Dashboard</h3>
            </li>
        </a>
        <li class = "account">
            <div class = "account_title">
                <i class="fa-solid fa-user"></i>
                <h3 style = "font-size : 20px">Account</h3>
                <i style = "font-size : 15px; margin-left : 100px" class="fa-solid fa-chevron-down icondown_account"></i>
                <i style = "font-size : 15px; margin-left : 100px" class="fa-solid fa-chevron-up iconup_account"></i>
            </div>
        </li>
        <div style = "width : 100%" class = "account_content">
            <div style = "display : flex; flex-wrap : wrap; width : 100%" class = "account_exist">
                <a href = "index.php?title=accountExist" class = "text_account-exist">Tài khoản đang tồn tại</a>
                <a href = "index.php?title=accountDelete" class = "text_account-delete">Tài khoản đã xóa</a>
            </div>
        </div>
        <a href="index.php?title=product">
            <li>
                <i class="fa-brands fa-product-hunt"></i>
                <h3>Product</h3>
            </li>
        </a>
        <a href="index.php?title=category">
            <li>
                <i class="fa-solid fa-copyright"></i>
                <h3>Category</h3>
            </li>
        </a>
        <a href="index.php?title=author">
            <li>
                <i class="fa-solid fa-copyright"></i>
                <h3>Author</h3>
            </li>
        </a>
        <a href="index.php?title=publisher">
            <li>
                <i class="fa-solid fa-copyright"></i>
                <h3>Publisher</h3>
            </li>
        </a>
        <a href="index.php?title=order">
            <li>
                <i class="fa-solid fa-copyright"></i>
                <h3>Order</h3>
            </li>
        </a>
        <a href="index.php?title=receipt">
            <li>
                <i class="fa-solid fa-copyright"></i>
                <h3>Receipt</h3>
            </li>
        </a>
    </ul>
</div>

<script>
    var iconDownAccount = document.querySelector('.icondown_account')
    var iconUpAccount = document.querySelector('.iconup_account');
    iconUpAccount.style.display = 'none'
    iconDownAccount.addEventListener('click', function(e)
    {
       document.querySelector('.account_content').style.display = 'block' 
       iconDownAccount.style.display = 'none'
       iconUpAccount.style.display = 'block'
    })
    iconUpAccount.addEventListener('click', function(e)
    {
        document.querySelector('.account_content').style.display = 'none'
        iconDownAccount.style.display = 'block';
        iconUpAccount.style.display = 'none'
    })
</script>