<div class = "register__modal">
        <div class = "modal">
            <div class = "modal_base"></div>
                <div class="main">
                    <form action="" method="POST" class="form" id="form-1">
                        <a href="index.php" class="exitRegister">x</a>  
                        <h3 style = "margin-bottom : -20px"class="heading">Đăng ký</h3>                
                        <div class="spacer"></div>
                
                        <div class="form-group">
                            <label for="fullname" class="form-label">Tên đăng nhập</label>
                            <input id="fullname" name="fullname" type="text" placeholder="VD: Lập trình Web" class="form-control">
                            <span style = "font-size : 17px;" class="form-message"></span>
                        </div>
                
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" type="text" placeholder="VD: email@domain.com" class="form-control" autocomplete="u">
                            <span class="form-message"></span>
                        </div>
                
                        <div class="form-group">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input id="password" name="password" type="password" placeholder="Nhập mật khẩu" class="form-control" autocomplete="new-password" >
                            <span class="form-message"></span>
                        </div>
                
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Nhập lại mật khẩu</label>
                            <input id="password_confirmation" name="password_confirmation" placeholder="Nhập lại mật khẩu" type="password" class="form-control" autocomplete="new-password">
                            <span class="form-message"></span>
                        </div>
                        <span class = "fail_signup"></span>
                        <a href = "index.php?title=login" class = "switchLogin">Chuyển sang đăng nhập</a>

                        <input name = "button_signup" type="submit" class = "button_signup" value = "Đăng kí">

                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- <script>
    document.querySelector('.exitRegister').addEventListener('click', function(e)
    {
        e.preventDefault();
        document.querySelector('.modal').remove()
    })
</script> -->