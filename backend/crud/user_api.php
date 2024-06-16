<?php 
    $choice = isset($_POST['choice']) ? $_POST['choice'] : "";
    switch($choice) 
    {
        case 'delete_user':
            DeleteUser();
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
        case 'display_edit_user':
            DisplayEditUser();
            break;
        case 'handle_edit_user':
            HandleEditUser();
            break;
        case 'search_user':
            SearchUser();
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
    function DisplayEditUser()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        if(isset($_POST['id_edit']))
        {
            $idEdit = $_POST['id_edit'];
            $sql = "SELECT * FROM users WHERE active = 1 AND id = '$idEdit'";
            $result = DataSQL::querySQL($sql);
            $data = array();
            while($row = mysqli_fetch_array($result))
            {
                $data[] = $row;
            }
            echo json_encode($data);
        }
    }
    function HandleEditUser()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
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

    function SearchUser()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/frontend/includes/config.php";
        $nameSearch = $_POST['name_search'];
        $typeSearch = $_POST['type'];
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $pageSize = isset($_POST['pageSize']) ? $_POST['pageSize'] : 7;
        $startPage = ($page - 1) * $pageSize;
        $check = "";
        $sql = "";
        if($typeSearch == 0)
        {
            $sql = "SELECT * FROM users WHERE active = 1  LIMIT $startPage, $pageSize";
            $check = 0;
        }
        else if($typeSearch == 1)
        {
            $sql = "SELECT * FROM users WHERE id = '$nameSearch' AND active = 1 ";
            $check = 1;
        }
        else if($typeSearch == 2)
        {
            $sql = "SELECT * FROM users WHERE fullname like '%" . $nameSearch . "%' AND active = 1 LIMIT $startPage, $pageSize ";
            $check = 2;
        }
        else if($typeSearch == 3)
        {
            $sql = "SELECT * FROM users WHERE email like '%" . $nameSearch . "%' AND active = 1 LIMIT $startPage, $pageSize ";
            $check = 3;
        }
        else if($typeSearch == 4)
        {
            $sql = "SELECT * FROM users WHERE phone_number like '%" . $nameSearch . "%' AND active = 1 LIMIT $startPage, $pageSize ";
            $check = 4;
        }
        $result = mysqli_query($connection, $sql);
        $informations = array();
        $data = new stdClass();
        while($row = mysqli_fetch_array($result))
        {
            $informations[] = $row;
        }
        $data->informations = $informations;
        $sql_count = "";
        if($check == 0)
        {
            $sql_count = "SELECT * FROM users WHERE active = 1 ";
        }
        else if($check == 1)
        {
            $sql_count = "SELECT * FROM users WHERE id = '$nameSearch' AND active = 1 ";
        }
        else if($check == 2)
        {
            $sql_count = "SELECT * FROM users WHERE active = 1 AND fullname like '%" . $nameSearch . "%' ";
        }
        else if($check == 3)
        {
            $sql_count = "SELECT * FROM users WHERE email like '%" . $nameSearch . "%' AND active = 1";
        }
        else if($check == 4)
        {
            $sql_count = "SELECT * FROM users WHERE phone_number like '%" . $nameSearch . "%' AND active = 1";
        }
        $result_count = mysqli_query($connection, $sql_count);
        $row_count = mysqli_num_rows($result_count);
        $data->number = $row_count;
        echo json_encode($data);
    }

?>
