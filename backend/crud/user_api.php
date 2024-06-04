<?php 
    $choice = isset($_POST['choice']) ? $_POST['choice'] : "";
    switch($choice) 
    {
        case 'delete_user':
            DeleteUser();
            break;
        case 'get_all_user_exist':
            GetAllUserExist();
            break;
        case 'restore_user':
            RestoreUser();
            break;
        case 'get_all_user_no_exist':
            GetAllUserNoExist();
            break;
        case 'add_user':
            AddUser();
            break;
    }
    function DeleteUser()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        if(isset($_POST['id_delete']))
        {
            $idDelete = $_POST['id_delete'];  
    
            $sql = "UPDATE users SET active = 0 WHERE id = '$idDelete'";
            DataSQL::querySQl($sql);
            header('location: ../index.php?title=accountExist');
        }
    }
    function GetAllUserExist()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $sql = "SELECT * FROM users WHERE active = 1";
        $result = DataSQL::querySQL($sql);
        $data = array();
        while($row = mysqli_fetch_array($result))
        {
            $data[] = $row;
        }
        echo json_encode($data);
    }
    function RestoreUser()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $idUser = $_POST['id_user'];
        $sqlUser = "UPDATE users SET active = 1 WHERE id = '$idUser'";
        DataSQL::querySQL($sqlUser);
    }
    function GetAllUserNoExist()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $sql = "SELECT * FROM users WHERE active = 0";
        $result = DataSQL::querySQL($sql);
        $data = array();
        while($row = mysqli_fetch_array($result))
        {
            $data[] = $row;
        }
        echo json_encode($data);
    }
    function AddUser()
    {
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
    }
?>
