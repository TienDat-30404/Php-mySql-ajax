<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    if(isset($_GET['id_delete']))
    {
        $idDelete = $_GET['id_delete'];
        $sqlPublisherProduct = "UPDATE products SET publisher_id = 1 WHERE publisher_id = '$idDelete'";
        DataSQL::querySQL($sqlPublisherProduct);
        $sql = "DELETE FROM publishers WHERE id = '$idDelete'";
        DataSQL::querySQL($sql);
    }
?>