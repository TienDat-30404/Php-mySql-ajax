<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    $sql = "SELECT * FROM users WHERE active = 1";
    $result = DataSQL::querySQL($sql);
    $data = array();
    while($row = mysqli_fetch_array($result))
    {
        $data[] = $row;
    }
    echo json_encode($data);
?>