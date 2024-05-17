<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    session_start();
    $idBill = $_POST['id_bill'];
    $idStaff = "";
    if(isset($_SESSION['account']))
    {
        $idStaff = $_SESSION['account']['id_user'];
    }
    $sql = "UPDATE bills SET staff_id = '$idStaff', bill_status_id = 2 WHERE id = '$idBill'";
    DataSQL::querySQL($sql);
?>