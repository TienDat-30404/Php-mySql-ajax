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
    
            $sql = "UPDATE users SET active = 0 WHERE id = ?";
            DataSQL::executeSQL($sql, [$idDelete]);
        }
    }

    function RestoreUser()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $isActive = 1;
        $idUser = $_POST['id_user'];
        $sqlUser = "UPDATE users SET active = ? WHERE id = ?";
        DataSQL::executeSQL($sqlUser, [$isActive, $idUser]);
    }
    function GetAllUserNoExist()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $isActive = 0;
        // $page = isset($_POST['page']) ? $_POST['page'] : 1;
        // $pageSize = isset($_POST['pageSize']) ? $_POST['pageSize'] : 7;
        // $startPage = ($page - 1) * $pageSize;
        $sql = "SELECT * FROM users WHERE active = ?";
        $result = DataSQL::querySQLAll($sql, [$isActive]);
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
        $sqlCheck = "SELECT * FROM users WHERE email = ?";
        $rowCheck = DataSQL::querySQLCount($sqlCheck, [$email]);
        if($rowCheck == 0)
        {
            $sql = "INSERT INTO users(role_id, email, password, fullname, address, phone_number, active) VALUES(?, ?, ?, ?, ?, ?, ?)";
            DataSQL::executeSQL($sql, [$idRole, $email, $password, $fullname, $address, $phone, $active]);
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
            $isActive = 1;
            $idEdit = $_POST['id_edit'];
            $sql = "SELECT * FROM users WHERE active = ? AND id = ?";
            $result = DataSQL::querySQLAll($sql, [$isActive, $idEdit]);
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
        $sqlCheck = "SELECT * FROM users WHERE (email = ? OR fullname = ? OR phone_number = ?) AND id != ?";
        $rowCheck = DataSQL::querySQLCount($sqlCheck, [$emailUser, $nameUser, $phoneUser, $idHidden]);
        if($rowCheck == 0 && strlen($passwordUser) >= 6 && $nameUser != "" && $emailUser != "" && $passwordUser != "" 
        && $addressUser != "" && $phoneUser != "" && preg_match($checkEmail, $emailUser))
        {
            $sql = "UPDATE users SET role_id = ?, email = ?, password = ?, fullname = ?, 
            address = ?, phone_number = ? WHERE id = ?";
            DataSQL::executeSQL($sql, [$idRole, $emailUser, $passwordUser, $nameUser, $addressUser, $phoneUser, $idHidden]);
            echo json_encode(array("isSuccess" => "Chỉnh sửa thành công"));
        }
        else 
        {
            echo json_encode(array("isSuccess" => "Chỉnh sửa thất bại"));
        }
    }

    function SearchUser()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $isActive = 1;
        $nameSearch = "%" . $_POST['name_search'] . "%";
        $typeSearch = $_POST['type'];
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $pageSize = isset($_POST['pageSize']) ? $_POST['pageSize'] : 7;
        $startPage = ($page - 1) * $pageSize;
        $check = "";
        $sql = "";
        if($typeSearch == 0)
        {
            $sql = "SELECT * FROM users WHERE active = ?  LIMIT ?, ?";
            $check = 0;
            $result = DataSQL::querySQLAll($sql, [$isActive, $startPage, $pageSize]);
        }
        else if($typeSearch == 1)
        {
            $sql = "SELECT * FROM users WHERE id = ? AND active = ? ";
            $check = 1;
            $nameSearch = $_POST['name_search'];
            $result = DataSQL::querySQLAll($sql, [$nameSearch, $isActive]);
        }
        else if($typeSearch == 2)
        {
            $sql = "SELECT * FROM users WHERE fullname like ? AND active = ? LIMIT ?, ? ";
            $check = 2;
            $result = DataSQL::querySQLAll($sql, [$nameSearch, $isActive, $startPage, $pageSize]);
        }
        else if($typeSearch == 3)
        {
            $sql = "SELECT * FROM users WHERE email like ? AND active = ? LIMIT ?, ? ";
            $check = 3;
            $result = DataSQL::querySQLAll($sql, [$nameSearch, $isActive, $startPage, $pageSize]);
        }
        else if($typeSearch == 4)
        {
            $sql = "SELECT * FROM users WHERE phone_number like ? AND active = ? LIMIT ?, ? ";
            $check = 4;
            $result = DataSQL::querySQLAll($sql, [$nameSearch, $isActive, $startPage, $pageSize]);
        }
        $informations = array();
        $data = new stdClass();
        while($row = mysqli_fetch_array($result))
        {
            $informations[] = $row;
        }
        $data->informations = $informations;
        $sql_count = "";
        $row_count = "";
        if($check == 0)
        {
            $sql_count = "SELECT * FROM users WHERE active = ? ";
            $row_count = DataSQL::querySQLCount($sql_count, [$isActive]);
        }
        else if($check == 1)
        {
            $sql_count = "SELECT * FROM users WHERE id = ? AND active = ? ";
            $row_count = DataSQL::querySQLCount($sql_count, [$nameSearch, $isActive]);
        }
        else if($check == 2)
        {
            $sql_count = "SELECT * FROM users WHERE active = ? AND fullname like ? ";
            $row_count = DataSQL::querySQLCount($sql_count, [$isActive, $nameSearch]);
        }
        else if($check == 3)
        {
            $sql_count = "SELECT * FROM users WHERE email like ? AND active = ?";
            $row_count = DataSQL::querySQLCount($sql_count, [$nameSearch, $isActive]);
        }
        else if($check == 4)
        {
            $sql_count = "SELECT * FROM users WHERE phone_number like ? AND active = ?";
            $row_count = DataSQL::querySQLCount($sql_count, [$nameSearch, $isActive]);
        }
        $data->number = $row_count;
        echo json_encode($data);
    }

?>
