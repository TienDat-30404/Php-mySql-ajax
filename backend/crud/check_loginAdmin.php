<?php 
    session_start();

    // Kiểm tra xem người dùng đã đăng nhập chưa
    if (!isset($_SESSION['account']) || $_SESSION['account']['id_role'] != 1) {
        // Nếu chưa đăng nhập hoặc không phải admin, chuyển hướng đến trang đăng nhập
        header('Location: ../index.php?title=login');
        exit();
    }
?>