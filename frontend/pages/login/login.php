

<div class = "modal">
        <div class = "modal_base"></div>
            <div class="main">
                <form action="frontend/pages/login/handle_login.php" method="POST" class="form" id="form-2">
                    <a href="index.php" class="exitLogin">x</a>  
                    <div class="spacer"></div>
                    <h3 class = "login__text">Đăng nhập</h3>
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <?php 
                            if(isset($_SESSION['fail_login']))
                            {
                                ?>
                                <input  value = "<?php echo $_SESSION['fail_login']['email']; ?>" id="email" name="email" type="text" placeholder="VD: email@domain.com" class="form-control" autocomplete="u">
                            <?php 
                            }
                            else 
                            {
                                ?>
                                <input value = "" id="email" name="email" type="text" placeholder="VD: email@domain.com" class="form-control" autocomplete="u">
                            <?php }
                        ?>
                        <span class="form-message"></span>            
                    <div class="form-group">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <?php 
                            if(isset($_SESSION['fail_login']))
                            {
                                ?>
                                    <input value = "<?php echo $_SESSION['fail_login']['password']; ?>" id="password" name="password" type="password" placeholder="Nhập mật khẩu" class="form-control" autocomplete="new-password">
                            <?php }
                            else 
                            {
                                ?>
                                <input value = "" id="email" name="password" type="text" placeholder="VD: email@domain.com" class="form-control" autocomplete="u">
                            <?php }
                        ?>
                    </div>
                    <?php 
                        if(isset($_SESSION['fail_login']))
                        {
                            ?>
                            <h1 style = "color : red" class="form-message">Email hoặc mật khẩu không hợp lệ</h1>
                        <?php }
                    ?>
                    <div class = "support__login">
                        <h5 class = "support__login-text">Nếu chưa có tài khoản ?</h5>
                        <a href = "index.php?title=signup" class = "ifRegister">Đăng kí</a>
                    </div>
                    <input name = "button_login" type="submit" class = "button_login" value = "Đăng nhập">
                </form>
            </div>
        </div>
</div>

