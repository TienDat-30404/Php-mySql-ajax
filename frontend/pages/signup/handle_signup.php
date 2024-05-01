<?php 
    include "../../includes/config.php";
    session_start();
    $fullname = $_GET['fullname'];
    $email = $_GET['email'];
    $password = $_GET['password'];
    $password_confirmation = $_GET['password_confirmation'];
    $roleId = 1;
    $address = "";
    $phone = "";
    $active = 1;
    $sql_all = "SELECT * FROM users WHERE email = '$email' OR fullname = '$fullname'";
    $result_sql_all = mysqli_query($connection, $sql_all);

    $row_sql_all = mysqli_num_rows($result_sql_all);
    if($row_sql_all > 0)
    {
        echo "Tên đăng nhập hoặc tài khoản đã tồn tại";
        header('location: index.php');
    }
    else 
    {
        $sql = "INSERT INTO users(role_id, email, password, fullname, address, phone_number, active) VALUES ('$roleId', '$email', '$password', '$fullname', '$address', '$phone', '$active')";
        mysqli_query($connection, $sql);  
    }
?>