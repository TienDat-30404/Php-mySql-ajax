<div class = modal>
    <div class = "modal_base">
        <form action="crud/add_user.php" method = "POST">
            <div class = "modal_base-account">
                <h3 class = "user_title">Add User</h3>
                <a href="index.php?title=accountExist" style = "position : absolute; top : 20px; right : 50px; font-size : 20px">X</a>
                <ul class = "user_add">
                    <li style = "margin-right : 60px">
                        <h4 style = "margin-top : 0; margin-bottom : 10px" class = "">Name Role</h4>
                        <select name="user_add-roleId" id="select_add">
                        <?php 
                            include_once $_SERVER['DOCUMENT_ROOT'] . "/project_web2-Copy-2/backend/database/connect.php";
                            $sql = "SELECT * FROM roles";
                            $result = DataSQL::querySQL($sql);
                            while($row = mysqli_fetch_array($result))
                            {
                                ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                            <?php }
                        ?>
                            
                        </select>
                    </li>
                    
                    <li>
                        <h4 style = "margin-top : 5px; margin-bottom : 5px">Full Name</h4>
                        <input name = "user_add-name" type="text">
                        <span class = "form-message"></span>
                    </li>
                    <li>
                        <h4>Email</h4>
                        <input name = "user_add-email" type="text">
                        <span class = "form-message"></span>
                    </li>
                    <li>
                        <h4>Password</h4>
                        <input name = "user_add-password" type="text">
                        <span class = "form-message"></span>
                    </li>
                    <li>
                        <h4>Address</h4>
                        <input name = "user_add-address" type="text">
                        <span class = "form-message"></span>
                    </li>
                    <li>
                        <h4>Phone</h4>
                        <input name = "user_add-phone" type="text">
                        <span class = "form-message"></span>
                    </li>
                    <li>
                        <input name = "button_add" type = "submit" class = "button_add">
                    </li>
                </ul>
            </div>
        </form>
    </div>
</div>
<script>
    async function HandleAddUser(idRole, name, email, password, address, phone)
    {
        if(confirm("Xác nhận thêm ?"))
        {
            var formData = new FormData();
            formData.append('choice', 'add_user')
            formData.append('user_add-roleId', idRole);
            formData.append('user_add-name', name);
            formData.append('user_add-email', email);
            formData.append('user_add-password', password);
            formData.append('user_add-address', address);
            formData.append('user_add-phone', phone);
            var link = await fetch('crud/user_api.php', {
                method: 'POST',
                body: formData
            });
            var json = await link.json();
            var success = `Thêm tài khoản ${name} thành công`;
            var fail = `Tài khoản ${name} đã tồn tại`;
            if(json.status === success)
            {
                alert(success)
                var ElementP = document.querySelector('input[name="user_add-name"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
    
                var ElementP1 = document.querySelector('input[name="user_add-email"]')
                var notification1 = ElementP1.nextElementSibling;
                notification1.innerText = "";
                ElementP1.classList.remove('border-message')
    
                var ElementP2 = document.querySelector('input[name="user_add-password"]')
                var notification2 = ElementP2.nextElementSibling;
                notification2.innerText = "";
                ElementP2.classList.remove('border-message')
    
                var ElementP3 = document.querySelector('input[name="user_add-address"]')
                var notification3 = ElementP3.nextElementSibling;
                notification3.innerText = "";
                ElementP3.classList.remove('border-message')
    
                var ElementP4 = document.querySelector('input[name="user_add-phone"]')
                var notification4 = ElementP4.nextElementSibling;
                notification4.innerText = "";
                ElementP4.classList.remove('border-message')
            }
            if(json.status === fail)
            {
                alert(fail)
            }
        }
    }
    var addButton = document.querySelector('input[name="button_add"]')
    addButton.addEventListener('click', function(event)
    {
        event.preventDefault();
        var idRole = document.querySelector('select[name="user_add-roleId"]').value
        var fullname = document.querySelector('input[name="user_add-name"]').value
        var email = document.querySelector('input[name="user_add-email"]').value
        var checkEmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        var password = document.querySelector('input[name="user_add-password"]').value
        var address = document.querySelector('input[name="user_add-address"]').value
        var phone = document.querySelector('input[name="user_add-phone"]').value
        var checkPhone = /^09\d{8,9}$/;
        var firstFocus = null;
        if(password.length < 6 || fullname == "" || email == "" || password == "" || address == "" || phone == "" ||
        !email.match(checkEmail) || !phone.match(checkPhone))
        {
            if(fullname == "")
            {
                var ElementP = document.querySelector('input[name="user_add-name"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Tên không được rông";
                ElementP.classList.add('border-message')
                if(firstFocus == null)
                {
                    firstFocus = ElementP;
                }

            }
            else 
            {
                var ElementP = document.querySelector('input[name="user_add-name"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(email == "")
            {
                var ElementP = document.querySelector('input[name="user_add-email"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Email không được rỗng";
                ElementP.classList.add('border-message')
                if(firstFocus == null)
                {
                    firstFocus = ElementP;
                }
            
            }
            else 
            {
                var ElementP = document.querySelector('input[name="user_add-email"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(!email.match(checkEmail))
            {
                var ElementP = document.querySelector('input[name="user_add-email"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Email không hợp lệ";
                ElementP.classList.add('border-message')
                if(firstFocus == null)
                {
                    firstFocus = ElementP;
                }
            }
            else 
            {
                var ElementP = document.querySelector('input[name="user_add-email"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(password == "")
            {
                var ElementP = document.querySelector('input[name="user_add-password"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Mật khẩu không được rỗng";
                ElementP.classList.add('border-message')
                if(firstFocus == null)
                {
                    firstFocus = ElementP;
                }
                
            }
            else 
            {
                var ElementP = document.querySelector('input[name="user_add-password"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(password.length < 6)
            {
                var ElementP = document.querySelector('input[name="user_add-password"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Mật khẩu phải chứa ít nhất 6 kí tự";
                ElementP.classList.add('border-message')
                if(firstFocus == null)
                {
                    firstFocus = ElementP;
                }
                
            }
            else 
            {
                var ElementP = document.querySelector('input[name="user_add-password"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(address == "")
            {
                var ElementP = document.querySelector('input[name="user_add-address"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Địa chỉ không được rông";
                ElementP.classList.add('border-message')
                if(firstFocus == null)
                {
                    firstFocus = ElementP;
                }
                
            }
            else 
            {
                var ElementP = document.querySelector('input[name="user_add-address"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(phone == "")
            {
                var ElementP = document.querySelector('input[name="user_add-phone"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Số điện thoại không được rỗng";
                ElementP.classList.add('border-message')
                if(firstFocus == null)
                {
                    firstFocus = ElementP;
                }
                
            }
            else 
            {
                var ElementP = document.querySelector('input[name="user_add-phone"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(!phone.match(checkPhone))
            {
                var ElementP = document.querySelector('input[name="user_add-phone"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Số điện thoại không hợp lệ";
                ElementP.classList.add('border-message')
                if(firstFocus == null)
                {
                    firstFocus = ElementP;
                }
            }
            else 
            {
                var ElementP = document.querySelector('input[name="user_add-phone"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            firstFocus.focus();
        }
        else 
        {
            HandleAddUser(idRole, fullname, email, password, address, phone)
        }
    })
</script>
