$idRole = $_POST['user_add-roleId'];
        $fullname = $_POST['user_add-name'];
        $email = $_POST['user_add-email'];
        $checkEmail = '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/';
        $password = $_POST['user_add-password'];
        $address = $_POST['user_add-address'];
        $phone = $_POST['user_add-phone'];
        $active = 1;
        $sqlCheck = "SELECT * FROM users WHERE email = '$email' OR fullname = '$fullname' OR phone_number = '$phone'";
        $result = DataSQL::querySQL($sqlCheck);
        $rowCheck = mysqli_num_rows($result);
        if($rowCheck == 0 && strlen($password) >= 6 && $fullname != "" && $email != "" && $password != "" && $address != "" && $phone != "" &&
        preg_match($checkEmail, $email))
        {
            $sql = "INSERT INTO users(role_id, email, password, fullname, address, phone_number, active) VALUES('$idRole', '$email', '$password', 
            '$fullname', '$address', '$phone', '$active')";
            DataSQL::querySQl($sql);
            header('location: ../index.php?title=accountExist');
        }
        else 
        {
            header('location: ../index.php?title=accountExist&action=add');
        }




        <script>
    async function HandleAddUser(idRole, name, email, password, address, phone)
    {
        var formData = new FormData();
        formData.append('user_add-roleId', idRole);
        formData.append('user_add-name', name);
        formData.append('user_add-email', email);
        formData.append('user_add-password', password);
        formData.append('user_add-address', address);
        formData.append('user_add-phone', phone);
        var link = await fetch('crud/handle_addUser.php', {
            method: 'POST',
            body: formData
        });
        // var json = await link.json();
        // var success = `Thêm tác giả ${nameAuthor} vào cửa hàng thành công`;
        // var fail = `Tác giả ${nameAuthor} đã tồn tại trong cửa hàng`;
        // if(json.status === success)
        // {
        //     alert(success)
        //     var ElementP = document.querySelector('input[name="name_author"]')
        //     var notification = ElementP.nextElementSibling;
        //     notification.innerText = "";
        //     ElementP.classList.remove('border-message')
        // }
        // if(json.status === fail)
        // {
        //     alert(fail)
        //     var ElementP = document.querySelector('input[name="name_author"]')
        //     ElementP.focus()
        //     var notification = ElementP.nextElementSibling;
        //     notification.innerText = "Tên tác giả đã tồn tại";
        //     ElementP.classList.remove('border-message')
        // }
    }
</script>