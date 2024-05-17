<?php

// Thông tin kết nối cơ sở dữ liệu
if (!defined('DB_HOST')) {
    // Nếu chưa được định nghĩa, định nghĩa nó
    define('DB_HOST', 'localhost');
}

// Tương tự cho các hằng số khác: DB_USERNAME, DB_PASSWORD, DB_NAME

if (!defined('DB_USERNAME')) {
    define('DB_USERNAME', 'root');
}

if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', '');
}

if (!defined('DB_NAME')) {
    define('DB_NAME', 'php_thuan');
}

// Tạo kết nối đến cơ sở dữ liệu
$connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Kiểm tra kết nối
if ($connection->connect_error) {
    die("Kết nối cơ sở dữ liệu thất bại: " . $connection->connect_error);
}

// Thiết lập bộ ký tự kết nối
$connection->set_charset("utf8");
 
?>