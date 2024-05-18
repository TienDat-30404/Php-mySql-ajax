<?php
    include "../../frontend/includes/config.php";

    $idStaff = $_POST['id_staff'];
    $idSupplier = $_POST['id_supplier'];
    $idProduct = $_POST['id_product'];
    $nameProduct = $_POST['name_product'];
    $priceProduct = $_POST['price_product'];
    $quantityProduct = $_POST['quantity_product'];
    $totalPrice = 0;
    for($i = 0; $i < count($priceProduct); $i++)
    {
        $totalPrice = $totalPrice + $priceProduct[$i];
    }
    $sql_entry_slips = "INSERT INTO entry_slips(staff_id, date_entry, total_price, supplier_id) VALUES('$idStaff', NOW(), '$totalPrice', '$idSupplier')";
    mysqli_query($connection, $sql_entry_slips);
?>