<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    if(isset($_GET['id_delete']))
    {
        $idDelete = $_GET['id_delete'];
        $sql = "UPDATE products SET isActive = 0 WHERE id = '$idDelete'";
        DataSQL::querySQL($sql);
    }
?>