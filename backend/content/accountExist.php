
<table style = "width : 100%; background-color : white" cellspacing = "0" cellpading = "10">
    <thead>
        <tr>
            <th colspan = "9">
                <a class = "add_user" href="">Add User</a>
            </th>
        </tr>
        <tr>
            <th style = "width : 40px">Id</th>
            <th>Role Id</th>
            <th>Email</th>
            <th>Password</th>
            <th>FullName</th>
            <th>Address</th>
            <th>Number Phone</th>
            <th colspan = "2">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
              include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
            $sql = "SELECT * FROM users WHERE active = 1";
            $result = DataSQL::querySQl($sql);
            while($row = mysqli_fetch_array($result))
            {
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['role_id']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['password']; ?></td>
                    <td><?php echo $row['fullname']; ?></td>
                    <td><?php echo $row['address']; ?></td>
                    <td><?php echo $row['phone_number']; ?></td>
                    
                    <td>
                        <a data-id-user = "<?php echo $row['id']; ?>" class = "edit_user" href="">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </td>
                    <td>
                        <a data-id-user = <?php echo $row['id']; ?> class = "delete_user" href="">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php }
        ?>
    </tbody>
</table>
<div class = "pagination"></div>
<script>
    // Delete User
    async function DeleteUser(id)
    {
        if(confirm("Xác nhận xóa?"))
        {
            var formData = new FormData()
            formData.append('choice', 'delete_user')
            formData.append('id_delete', id)
            var link = await fetch(`crud/user_api.php`, {
                method : 'POST',
                body : formData
            })
            SearchUser(0,"")
        }
    }
    var elementDel = document.querySelectorAll(".delete_user")
    elementDel.forEach(function(item)
    {
        item.addEventListener('click', function(event)
        {
            console.log(item)
            event.preventDefault();
            var idUser = this.getAttribute('data-id-user')
            DeleteUser(idUser)
        })
    })
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
                SearchUser(0, "")
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

    function AddUser()
    {
        var addUser = `
            <div class = modal>
                <div class = "modal_base">
                    <form action="crud/add_user.php" method = "POST">
                        <div class = "modal_base-account">
                            <h3 class = "user_title">Add User</h3>
                            <a class = "exit_crud-all" href="" style = "position : absolute; top : 20px; right : 50px; font-size : 20px">X</a>
                            <ul class = "user_add">
                                <li style = "margin-right : 60px">
                                    <h4 style = "margin-top : 0; margin-bottom : 10px" class = "">Name Role</h4>
                                    <select name="user_add-roleId" id="select_add">
                                    <?php 
                                        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
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
        `
        document.body.insertAdjacentHTML('beforeend', addUser);
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

        var detailExit = document.querySelector('.exit_crud-all');
        var modal = document.querySelector('.modal');
        detailExit.addEventListener('click', function(e)
        {
            e.preventDefault();
            modal.remove(); 
        })
    }
    var addUser = document.querySelector('.add_user')
    addUser.addEventListener('click', function(e)
    {
        e.preventDefault()
        AddUser()
    })


    // Edit User 

    async function EditUser(id)
    {
        var formData = new FormData()
        formData.append('choice', 'display_edit_user')
        formData.append('id_edit', id)
        var link = await fetch(`crud/user_api.php`, {
            method : 'POST',
            body : formData
        })
        var json = await link.json();
        DisplayEditUser(json)
    }
    var elementEdit = document.querySelectorAll('.edit_user')
    elementEdit.forEach(function(item)
    {
        item.addEventListener('click', function(event)
        {
            checkExit = true
           event.preventDefault();
           var idUser = this.getAttribute('data-id-user')
           EditUser(idUser)
        })
    })


    async function HandleEditUser(idUser, idRole, name, email, password, address, phone)
    {
        if(confirm("Xác nhân chỉnh sửa?"))
        {
            var formData = new FormData();
            formData.append('choice', 'handle_edit_user')
            formData.append('id_hidden', idUser);
            formData.append('user_edit-roleId', idRole);
            formData.append('user_edit-name', name)
            formData.append('user_edit-email', email)
            formData.append('user_edit-password', password)
            formData.append('user_edit-address', address)
            formData.append('user_edit-phone', phone)
            var link = await fetch('crud/user_api.php', {
                method: 'POST',
                body: formData
            });
            var json = await link.json();
            console.log(json)
            var success = "Chỉnh sửa thành công";
            var fail = "Chỉnh sửa thất bại";
            if(json.isSuccess === success)
            {
                alert(success)
            }
            else 
            {
                alert(fail)
            }
            SearchUser(0, "")
        }
    }

    function DisplayEditUser(data)
    {
        data.forEach(function(value)
        {
            var editUser = `
                <div class = "modal">
                    <div class = "modal_base">
                        <form action="" method = "">
                            <div class = "modal_base-account">
                                <h3 class = "user_title">Edit User</h3>
                                <a class = "exit_crud-all" href="" style = "position : absolute; top : 20px; right : 50px; font-size : 20px">X</a>
                                <ul class = "user_add">

                                    <li>
                                        <h4 style = "margin-top : 0; margin-bottom : 10px" class = "">Name Role</h4>
                                        <select name="user_edit-roleName" id="select_edit" onChange="OnChangeIdRole()">
                                        <?php 
                                            include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
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
                                        <h4 style = "margin-top : 0; margin-bottom : 10px">Id Role</h4>
                                        <input value = "1" class = "IdRole" name = "user_edit-roleId" type="text">
                                    </li>
                                    <?php 
                                        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
                                        if(isset($_GET['id_edit']))
                                        {
                                            $idEdit = $_GET['id_edit'];
                                            $sql = "SELECT * FROM users WHERE id = '$idEdit'";
                                            $result = DataSQL::querySQL($sql);
                                            $valueRow = mysqli_fetch_assoc($result);
                                        }
                                    ?>
                                    <li>
                                        <h4>Full Name</h4>
                                        <input value = "${value.fullname}" style = "margin-left : 20px" name = "user_edit-name" type="text">
                                        <span class = "form-message"></span>
                                    </li>
                                    <li>
                                        <h4>Email</h4>
                                        <input value = "${value.email}" name = "user_edit-email" type="text">
                                        <span class = "form-message"></span>
                                    </li>
                                    <li>
                                        <h4>Password</h4>
                                        <input value = "${value.password}" style = "margin-left : 30px" name = "user_edit-password" type="text">
                                        <span class = "form-message"></span>
                                    </li>
                                    <li>
                                        <h4>Address</h4>
                                        <input value = "${value.address}" style = "margin-left : 40px" name = "user_edit-address" type="text">
                                        <input value = "${value.id}" type="hidden" name = "id_hidden" >
                                        <span class = "form-message"></span>
                                    </li>
                                    <li>
                                        <h4>Phone</h4>
                                        <input value = "${value.phone_number}" name = "user_edit-phone" type="text">
                                        <span class = "form-message"></span>
                                    </li>
                                    <li>
                                        <input style = "padding : 10px; font-size : 17px; color : white" name = "button_edit" type = "submit" class = "button_add" value = "Chỉnh sửa">
                                    </li>
                                </ul>
                            </div>
                        </form>
                    </div> 
                </div>
            `
            document.body.insertAdjacentHTML('beforeend', editUser);
            var addButton = document.querySelector('input[name="button_edit"]')
            addButton.addEventListener('click', function(event)
            {
                event.preventDefault();
                var idUser = document.querySelector('input[name="id_hidden"]').value
                var idRole = document.querySelector('input[name="user_edit-roleId"]').value
                var fullname = document.querySelector('input[name="user_edit-name"]').value
                var email = document.querySelector('input[name="user_edit-email"]').value
                var checkEmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                var password = document.querySelector('input[name="user_edit-password"]').value
                var address = document.querySelector('input[name="user_edit-address"]').value
                var phone = document.querySelector('input[name="user_edit-phone"]').value
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
                        var ElementP = document.querySelector('input[name="user_edit-name"]')
                        var notification = ElementP.nextElementSibling;
                        notification.innerText = "";
                        ElementP.classList.remove('border-message')
                    }
                    if(email == "")
                    {
                        var ElementP = document.querySelector('input[name="user_edit-email"]')
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
                        var ElementP = document.querySelector('input[name="user_edit-email"]')
                        var notification = ElementP.nextElementSibling;
                        notification.innerText = "";
                        ElementP.classList.remove('border-message')
                    }
                    if(!email.match(checkEmail))
                    {
                        var ElementP = document.querySelector('input[name="user_edit-email"]')
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
                        var ElementP = document.querySelector('input[name="user_edit-email"]')
                        var notification = ElementP.nextElementSibling;
                        notification.innerText = "";
                        ElementP.classList.remove('border-message')
                    }
                    if(password == "")
                    {
                        var ElementP = document.querySelector('input[name="user_edit-password"]')
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
                        var ElementP = document.querySelector('input[name="user_edit-password"]')
                        var notification = ElementP.nextElementSibling;
                        notification.innerText = "";
                        ElementP.classList.remove('border-message')
                    }
                    if(password.length < 6)
                    {
                        var ElementP = document.querySelector('input[name="user_edit-password"]')
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
                        var ElementP = document.querySelector('input[name="user_edit-password"]')
                        var notification = ElementP.nextElementSibling;
                        notification.innerText = "";
                        ElementP.classList.remove('border-message')
                    }
                    if(address == "")
                    {
                        var ElementP = document.querySelector('input[name="user_edit-address"]')
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
                        var ElementP = document.querySelector('input[name="user_edit-address"]')
                        var notification = ElementP.nextElementSibling;
                        notification.innerText = "";
                        ElementP.classList.remove('border-message')
                    }
                    if(phone == "")
                    {
                        var ElementP = document.querySelector('input[name="user_edit-phone"]')
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
                        var ElementP = document.querySelector('input[name="user_edit-phone"]')
                        var notification = ElementP.nextElementSibling;
                        notification.innerText = "";
                        ElementP.classList.remove('border-message')
                    }
                    if(!phone.match(checkPhone))
                    {
                        var ElementP = document.querySelector('input[name="user_edit-phone"]')
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
                        var ElementP = document.querySelector('input[name="user_edit-phone"]')
                        var notification = ElementP.nextElementSibling;
                        notification.innerText = "";
                        ElementP.classList.remove('border-message')
                    }
                    firstFocus.focus();
                }
                else 
                {
                    HandleEditUser(idUser, idRole, fullname, email, password, address, phone)
                }
            })
        })
        var detailExit = document.querySelector('.exit_crud-all');
        var modal = document.querySelector('.modal');
        detailExit.addEventListener('click', function(e)
        {
            e.preventDefault();
            modal.remove(); 
        })
    }
    function OnChangeIdRole()
    {
        var selectIdRole = document.querySelector('#select_edit').value;
        console.log(selectIdRole)
        document.querySelector('.IdRole').value = selectIdRole
    }



    var currentPage = 1
    var pageSize = 7
    var pagination = document.querySelector('.pagination')
    function DisplaySearchUser(data, element)
    {
        var informations
        if(data.number != 0)
        {
            document.querySelector('table').innerHTML = ""
            informations =
                `  <thead>
                        <tr>
                            <th colspan = "9">
                                <a class = "addUser" href="">Add User</a>
                            </th>
                        </tr>
                        <tr>
                            <th>Id</th>
                            <th>Role ID</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>FullName</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                <tbody>`
                    data.informations.forEach(function(value)
                    {
                        informations += `
                        <tr>
                            <td>${value.id}</td>
                            <td>${value.role_id}</td>
                            <td>${value.email}</td>
                            <td>${value.password}</td>
                            <td>${value.fullname}</td>
                            <td>${value.address}</td>
                            <td>${value.phone_number}</td>
                            <td>
                                <a data-id-user = "${value.id}" class = "edit_user" href="">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                            <td>
                                <a data-id-user = ${value.id} class = "delete_user" href="">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody> `
                    })
                
        }
        else 
        {
            informations = ""
        }
        document.querySelector(element).innerHTML = informations

        var addUser = document.querySelector('.addUser')
        addUser.addEventListener('click', function(e)
        {
            e.preventDefault()
            AddUser()
        })

        var editUser = document.querySelectorAll('.edit_user')
        editUser.forEach(function(item)
        {
            item.addEventListener('click', function(event)
            {
                event.preventDefault();
                var idUser = this.getAttribute('data-id-user')
                EditUser(idUser)
            })
        })

        var elementDel = document.querySelectorAll(".delete_user")
        elementDel.forEach(function(item)
        {
            item.addEventListener('click', function(event)
        {
            event.preventDefault();
            var idUser = this.getAttribute('data-id-user')
            DeleteUser(idUser)

        })
        })
    }
     // search User
     var checkSelect = document.querySelector("#select_search-user")
    async function SearchUser(type, nameSearch)
    {
        var formData = new FormData();
        formData.append('choice', 'search_user')
        formData.append('type', type)
        formData.append('name_search', nameSearch)
        formData.append('page', currentPage)
        formData.append('pageSize', pageSize)
        var response = await fetch(`crud/user_api.php`, {
            method : 'POST',
            body : formData
        });
        var json = await response.json()
        console.log(json)
        DisplaySearchUser(json, "table")

        var indexSelect = checkSelect.value
        if(indexSelect == 0)
        {
            DisplayPagination(json, 0)
        }
        else if(indexSelect == 1)
        {
            DisplayPagination(json, 1 )
        }
        else if(indexSelect == 2)
        {
            DisplayPagination(json, 2 )
        }
        else if(indexSelect == 3)
        {
            DisplayPagination(json, 3 )
        }
        else if(indexSelect == 4)
        {
            DisplayPagination(json, 4 )
        }

    }

    SearchUser(0, "")
    var copySearch

    checkSelect.addEventListener("change", function(e)
    {
        document.querySelector('input[name="inputName"').value = ""
    })
    document.querySelector('.button_search').addEventListener('click', function(event)
    {
        currentPage = 1
        pagination.innerHTML = ""
        var inputSearch = document.querySelector('input[name="inputName"').value

        copySearch = inputSearch
        event.preventDefault();
        var indexSelect = checkSelect.value
        if(indexSelect == 0)
        {
            SearchUser(0, "")
        }
        else if(indexSelect == 1)
        {
            SearchUser(1, inputSearch)
        }
        else if(indexSelect == 2)
        {
            SearchUser(2, inputSearch)
        }
        else if(indexSelect == 3)
        {
            SearchUser(3, inputSearch)
        }
        else if(indexSelect == 4)
        {
            SearchUser(4, inputSearch)
        }

    })


    function DisplayPagination(data, check) {
    pagination.innerHTML = "";
    var maxPage = Math.ceil(data.number/ pageSize);
    console.log(maxPage)
    var start = 1;
    var end = maxPage;
    if(currentPage > 2 && maxPage > 3 && currentPage < maxPage)
    {
        start = currentPage - 1;
        end = currentPage + 1;
    }
    else if(currentPage == maxPage && maxPage > 3)
    {
        start = currentPage - 2;
        end = maxPage;
    }
    else if(maxPage > 3 && currentPage <= 2)
    {
        end = 3;
    }
    else if(maxPage == 1)
    {
        pagination.classList.add('hide');
    }
    if(maxPage > 1)
    {
        pagination.classList.remove('hide')
    }
    if(currentPage > 1)
    {
        var prevPage = document.createElement('li');
        prevPage.innerText = "Prev";
        prevPage.setAttribute('onclick', "ChangePage(" + (currentPage - 1) +", " + check + ")");
        pagination.appendChild(prevPage);
    }
    for (var i = start; i <= end; i++) {
        var pageButton = document.createElement('li');
        pageButton.innerText = i;
        if(i == currentPage)
        {
            pageButton.classList.add('headPage')
        }
        pageButton.setAttribute('onclick', "ChangePage(" + (i) + ", " + check + ")")
        pagination.appendChild(pageButton);
    }
    if(currentPage < maxPage)
    {
        var nextPage = document.createElement('li')
        nextPage.innerText = "Next";
        nextPage.setAttribute('onclick', "ChangePage(" + (currentPage + 1) +", " + check + ")");
        pagination.appendChild(nextPage)
    }
    }
    function ChangePage(index, check)
    {
        currentPage = index
        if(check == 0)
        {
            SearchUser(0, "")
        }
        else if(check == 1)
        {
            SearchUser(1, copySearch)
        }
        else if(check == 2)
        {
            SearchUser(2, copySearch)
        }
        else if(check == 3)
        {
            SearchUser(3, copySearch)
        }
        else if(check == 4)
        {
            SearchUser(4, copySearch)
        }
    }
</script>
