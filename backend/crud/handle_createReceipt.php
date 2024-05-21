<?php
    include "../../frontend/includes/config.php";

    $idStaff = $_POST['id_staff'];
    $idSupplier = $_POST['id_supplier'];
    $idProduct = $_POST['id_product'];
    $priceProduct = $_POST['price_product'];
    $quantityProduct = $_POST['quantity_product'];
    $totalPrice = 0;
    for($i = 0; $i < count($priceProduct); $i++)
    {
        $totalPrice = $totalPrice + $priceProduct[$i];
    }
    $sql_entry_slips = "INSERT INTO entry_slips(staff_id, date_entry, total_price, supplier_id) VALUES('$idStaff', NOW(), '$totalPrice', '$idSupplier')";
    mysqli_query($connection, $sql_entry_slips);


    $idEntrySlips = mysqli_insert_id($connection);
    for($i = 0; $i < count($idProduct); $i++)
    {
        // Insert entry_slip_details
        $idDetailProduct = $idProduct[$i];
        $detailQuantity = $quantityProduct[$i];
        $detailPrice = $priceProduct[$i];
        $sqlInsertDetail = "INSERT INTO entry_slip_details(entry_slip_id, product_id, quantity, entry_price) VALUES('$idEntrySlips', 
        '$idDetailProduct', '$detailQuantity', '$detailPrice')";
        mysqli_query($connection, $sqlInsertDetail);

        // update quantity product 
        $sqlSelectProduct = "SELECT * FROM products WHERE id = '$idDetailProduct'";
        $resultSelectProduct = mysqli_query($connection, $sqlSelectProduct);
        $rowSelectProduct = mysqli_fetch_array($resultSelectProduct);
        $updateQuantityProduct = $rowSelectProduct['quantity'] + $detailQuantity;

        $sqlUpdateQuantity = "UPDATE products SET quantity = '$updateQuantityProduct' WHERE id = '$idDetailProduct'";
        mysqli_query($connection, $sqlUpdateQuantity);
    }
?>