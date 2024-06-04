<?php 
    $choice = isset($_POST['choice']) ? $_POST['choice'] : "";
    switch($choice) 
    {
        case 'detail_receipt':
            DetailReceipt();
            break;
        case 'create_receipt':
            CreateReceipt();
            break;
        case 'get_all_receipt':
            GetAllReceipt();
            break;
        case 'delete_receipt':
            DeleteReceipt();
            break;
    }
    function DetailReceipt()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $idReceipt = $_POST['id_receipt'];
        $sql = "SELECT entry_slips.total_price as totalPrice, products.image as imageProduct, entry_slips.date_entry as dateCreate, 
                suppliers.name as nameSupplier, users.fullname as nameStaff, 
                products.name as nameProduct, products.price as priceProduct, entry_slip_details.quantity as quantityProduct 
                FROM entry_slips JOIN suppliers ON entry_slips.supplier_id = suppliers.id
                JOIN users ON entry_slips.staff_id = users.id
                JOIN entry_slip_details ON entry_slips.id = entry_slip_details.entry_slip_id
                JOIN products ON entry_slip_details.product_id = products.id WHERE entry_slips.id = '$idReceipt'";
        $data = array();
        $result = DataSQL::querySQL($sql);
        while($row = mysqli_fetch_array($result)) {
            $data[] = $row;
        }

        echo json_encode($data);
    }
    function CreateReceipt()
    {
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
    }
    function GetAllReceipt()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $sql = "SELECT * FROM entry_slips";
        $result = DataSQL::querySQL($sql);
        $data = array();
        while($row = mysqli_fetch_array($result))
        {
            $data[] = $row;
        }
        echo json_encode($data);
    }
    function DeleteReceipt()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        if(isset($_POST['id_delete']))
        {
            $idDelete = $_POST['id_delete'];
            $sqlEntrySLipDetails = "DELETE FROM entry_slip_details WHERE entry_slip_id = '$idDelete'";
            DataSQL::querySQL($sqlEntrySLipDetails);
            $sqlEntrySlip = "DELETE FROM entry_slips WHERE id = '$idDelete'";
            DataSQL::querySQL($sqlEntrySlip);
        }
    }
?>