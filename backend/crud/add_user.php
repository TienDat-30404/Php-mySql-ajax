<div class = modal>
    <div class = "modal_base">
        <form action="crud/add_user.php" method = "POST">
            <div class = "modal_base-account">
                <h3 class = "user_title">Add User</h3>
                <a href="index.php?title=accountExist" style = "position : absolute; top : 20px; right : 50px; font-size : 20px">X</a>
                <ul class = "user_add">
                    <li style = "margin-right : 60px">
                        <h4 style = "margin-top : 0; margin-bottom : 10px" class = "">Name Role</h4>
                        <select name="user_add-roleId" id="select_add" onchange="displayNameRole()">
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
                    </li>
                    <li>
                        <h4>Email</h4>
                        <input name = "user_add-email" type="text">
                    </li>
                    <li>
                        <h4>Password</h4>
                        <input name = "user_add-password" type="text">
                    </li>
                    <li>
                        <h4>Address</h4>
                        <input name = "user_add-address" type="text">
                    </li>
                    <li>
                        <h4>Phone</h4>
                        <input name = "user_add-phone" type="text">
                    </li>
                    <li>
                        <input name = "button_add" type = "submit" class = "button_add">
                    </li>
                </ul>
            </div>
        </form>
    </div>
</div>
<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/project_web2-Copy-2/backend/database/connect.php";
    if(isset($_POST['button_add']))
    {
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
    }
?>
