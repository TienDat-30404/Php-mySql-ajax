<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    if(isset($_GET['id_delete']))
    {
        $idDelete = $_GET['id_delete'];
        $sql = "DELETE FROM publishers WHERE id = '$idDelete'";
        DataSQL::querySQL($sql);
    }
?>