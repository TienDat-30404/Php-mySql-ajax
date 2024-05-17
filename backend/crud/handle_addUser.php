<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $idRole = $_POST['user_add-roleId'];
        $fullname = $_POST['user_add-name'];
        $email = $_POST['user_add-email'];
        $password = $_POST['user_add-password'];
        $address = $_POST['user_add-address'];
        $phone = $_POST['user_add-phone'];
        $active = 1;
        $sqlCheck = "SELECT * FROM users WHERE email = '$email'";
        $result = DataSQL::querySQL($sqlCheck);
        $rowCheck = mysqli_num_rows($result);
        if($rowCheck == 0)
        {
            $sql = "INSERT INTO users(role_id, email, password, fullname, address, phone_number, active) VALUES('$idRole', '$email', '$password', 
            '$fullname', '$address', '$phone', '$active')";
            DataSQL::querySQl($sql);
            echo json_encode(array("status" => "Thêm tài khoản $fullname thành công"));
        }
        else 
        {
            echo json_encode(array("status" => "Tài khoản $fullname đã tồn tại"));
        }
        
?>

