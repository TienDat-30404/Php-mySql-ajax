<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    $sql = "SELECT * FROM authors";
    $result = DataSQL::querySQL($sql);
    $data = array();
    while($row = mysqli_fetch_array($result))
    {
        $data[] = $row;
    }
    echo json_encode($data);
?>