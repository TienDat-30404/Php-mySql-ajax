<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    if(isset($_GET['id_delete']))
    {
        $idDelete = $_GET['id_delete'];
        $sqlDeleteDetailBill = "DELETE FROM bill_details WHERE bill_id = '$idDelete'";
        DataSQL::querySQL($sqlDeleteDetailBill);
        $sqlDeleteBill = "DELETE FROM bills WHERE id = '$idDelete'";
        DataSQL::querySQL($sqlDeleteBill);
    }
?>