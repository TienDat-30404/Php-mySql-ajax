<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    session_start();
    $fullname = $_GET['fullname'];
    $email = $_GET['email'];
    $password = $_GET['password'];
    $password_confirmation = $_GET['password_confirmation'];
    $roleId = 3;
    $address = "";
    $phone = "";
    $active = 1;
    $sql_all = "SELECT * FROM users WHERE email = ? OR fullname = ?";

    $row_sql_all = DataSQL::querySQLCount($sql_all, [$email, $fullname]);
    if($row_sql_all > 0)
    {
        echo json_encode(array("isSuccess" => "Tài khoản hoặc tên người dùng đã tồn tại"));
    }
    else 
    {
        echo json_encode(array("isSuccess" => "Đăng kí thành công"));
        $sql = "INSERT INTO users(role_id, email, password, fullname, address, phone_number, active) VALUES (?, ?, ?, ?, ?, ?, ?)";
        DataSQL::executeSQL($sql, [$roleId, $email, $password, $fullname, $address, $phone, $active]);
    }
?>