
<div class = modal>
    <div class = "modal_base">
        <form action="crud/handle_editUser.php" method = "POST">
            <div class = "modal_base-account">
                <h3 class = "user_title">Edit User</h3>
                <a href="index.php?title=accountExist" style = "position : absolute; top : 20px; right : 50px; font-size : 20px">X</a>
                <ul class = "user_add">

                    <li>
                        <h4 style = "margin-top : 0; margin-bottom : 10px" class = "">Name Role</h4>
                        <select name="user_edit-roleName" id="select_edit" onChange="OnChangeIdRole()">
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
                        <h4 style = "margin-top : 0; margin-bottom : 10px">Id Role</h4>
                        <input value = "1" class = "IdRole" name = "user_edit-roleId" type="text">
                    </li>
                    <?php 
                        include_once $_SERVER['DOCUMENT_ROOT'] . "/project_web2-Copy-2/backend/database/connect.php";
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
                        <input value = "<?php echo $valueRow['fullname']; ?>" style = "margin-left : 20px" name = "user_edit-name" type="text">
                    </li>
                    <li>
                        <h4>Email</h4>
                        <input value = "<?php echo $valueRow['email']; ?>" name = "user_edit-email" type="text">
                    </li>
                    <li>
                        <h4>Password</h4>
                        <input value = "<?php echo $valueRow['password']; ?>" style = "margin-left : 30px" name = "user_edit-password" type="text">
                    </li>
                    <li>
                        <h4>Address</h4>
                        <input value = "<?php echo $valueRow['address']; ?>" style = "margin-left : 40px" name = "user_edit-address" type="text">
                        <input value = "<?php echo $valueRow['id']; ?>" type="hidden" name = "id_hidden" >
                    </li>
                    <li>
                        <h4>Phone</h4>
                        <input value = "<?php echo $valueRow['phone_number']; ?>" name = "user_edit-phone" type="text">
                    </li>
                    <li>
                        <input style = "padding : 10px; font-size : 17px; color : white" name = "button_edit" type = "submit" class = "button_add" value = "Chỉnh sửa">
                    </li>
                </ul>
            </div>
        </form>
    </div>
</div>
<script>
    function OnChangeIdRole()
    {
        var selectIdRole = document.querySelector('#select_edit').value;
        console.log(selectIdRole)
        document.querySelector('.IdRole').value = selectIdRole
    }
</script>

