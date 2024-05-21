<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    if(isset($_GET['id_delete']))
    {
        $idDelete = $_GET['id_delete'];
        $sqlEntrySLipDetails = "DELETE FROM entry_slip_details WHERE entry_slip_id = '$idDelete'";
        DataSQL::querySQL($sqlEntrySLipDetails);
        $sqlEntrySlip = "DELETE FROM entry_slips WHERE id = '$idDelete'";
        DataSQL::querySQL($sqlEntrySlip);
    }
?>