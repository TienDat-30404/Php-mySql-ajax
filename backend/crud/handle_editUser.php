<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/project_web2-Copy-2/backend/database/connect.php";
    if(isset($_POST['button_edit']))
    {
        $idHidden = $_POST['id_hidden'];
        $idRole = $_POST['user_edit-roleId'];
        $nameUser = $_POST['user_edit-name'];
        $emailUser = $_POST['user_edit-email'];      
        $checkEmail = '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/';
        $passwordUser = $_POST['user_edit-password'];
        $addressUser = $_POST['user_edit-address'];
        $phoneUser = $_POST['user_edit-phone'];
        $sqlCheck = "SELECT * FROM users WHERE (email = '$emailUser' OR fullname = '$nameUser' OR phone_number = '$phoneUser') AND id != '$idHidden'";
        $result = DataSQL::querySQL($sqlCheck);
        $rowCheck = mysqli_num_rows($result);
        if($rowCheck == 0 && strlen($passwordUser) >= 6 && $nameUser != "" && $emailUser != "" && $passwordUser != "" 
        && $addressUser != "" && $phoneUser != "" && preg_match($checkEmail, $emailUser))
        {
            $sql = "UPDATE users SET role_id = '$idRole', email = '$emailUser', password = '$passwordUser', fullname = '$nameUser', 
            address = '$addressUser', phone_number = '$phoneUser' WHERE id = '$idHidden'";
            DataSQL::querySQL($sql);
            header('location: ../index.php?title=accountExist');
        }
        else 
        {
            header("location: ../index.php?title=accountExist");
        }
    }
?>