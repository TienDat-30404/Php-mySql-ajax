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
        case 'display_default_receipt':
            DisplayDefaultReceipt();
            break;
        case 'search_receipt':
            SearchReceipt();
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

    function DisplayDefaultReceipt()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $pageSize = isset($_POST['pageSize']) ? $_POST['pageSize'] : 5;
        $startPage = ($page - 1) * $pageSize;
        $sql = "SELECT * FROM entry_slips LIMIT $startPage, $pageSize";
        $result = DataSQL::querySQL($sql);
        $informations = array();
        $data = new stdClass();
        while($row = mysqli_fetch_array($result))
        {
            $informations[] = $row;
        }
        $data->informations = $informations;
        $sql_count = "SELECT * FROM entry_slips";
        $result_count = DataSQL::querySQL($sql_count);
        $row_count = mysqli_num_rows($result_count);
        $data->number = $row_count;
        echo json_encode($data);
    }


    function SearchReceipt()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/frontend/includes/config.php";

        $idReceipt = isset($_POST['id_receipt']) ? $_POST['id_receipt'] : 0;
        $dateFrom = isset($_POST['date_from']) ? trim($_POST['date_from'], '"') : '';
        $dateTo = isset($_POST['date_to']) ? trim($_POST['date_to'], '"') : '';
        $priceFrom = isset($_POST['price_from']) ? $_POST['price_from'] : 0;
        $priceTo = isset($_POST['price_to']) ? $_POST['price_to'] : 0;
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $pageSize = isset($_POST['pageSize']) ? $_POST['pageSize'] : 7;
        $startPage = ($page - 1) * $pageSize;

        $sql = "";
        $check = "";

        if ($idReceipt == 0 && $dateFrom == "" && $dateTo == "" && $priceFrom == "" && $priceTo == "") {
            $sql = "SELECT * FROM entry_slips LIMIT $startPage, $pageSize";
            $check = 1;
        }   
        else if ($idReceipt != 0 && $dateFrom == "" && $dateTo == ""  && $priceFrom == "" && $priceTo == "")
        {
            $sql = "SELECT * FROM entry_slips WHERE id = '$idReceipt' LIMIT $startPage, $pageSize";
            $check = 2;
        }
        else if ($idReceipt == 0 && $dateFrom != "" && $dateTo != "" && $priceFrom == "" && $priceTo == "") 
        {
            $sql = "SELECT * FROM entry_slips WHERE DATE(date_entry) BETWEEN '$dateFrom' AND '$dateTo' LIMIT $startPage, $pageSize";
            $check = 3;
        } 
        else if ($idReceipt == 0 && $dateFrom == "" && $dateTo == "" && $priceFrom != "" && $priceTo != "") 
        {
            $sql = "SELECT * FROM entry_slips WHERE total_price >= $priceFrom AND total_price <= $priceTo LIMIT $startPage, $pageSize";
            $check = 4;
        } 
        else if($idReceipt == 0 && $dateFrom != "" && $dateTo != "" && $priceFrom != "" && $priceTo != "")
        {
            $sql = "SELECT * FROM entry_slips WHERE total_price >= $priceFrom AND total_price <= $priceTo AND DATE(date_entry) BETWEEN 
            '$dateFrom' AND '$dateTo' LIMIT $startPage, $pageSize";
            $check = 5;
        }
        if (!empty($sql)) {
            $result = mysqli_query($connection, $sql);
            $data = new stdClass();
            $informations = array();
            while ($row = mysqli_fetch_array($result)) {
                $informations[] = $row;
            }
            $data->informations = $informations;

            $sql_count = "";
            switch ($check) {
                case 1:
                    $sql_count = "SELECT * FROM entry_slips";
                    break;
                case 2:
                    $sql_count = "SELECT * FROM entry_slips WHERE id = '$idReceipt'";
                    break;
                case 3:
                    $sql_count = "SELECT * FROM entry_slips WHERE DATE(date_entry) BETWEEN '$dateFrom' AND '$dateTo'";
                    break;
                case 4:
                    $sql_count = "SELECT * FROM entry_slips WHERE total_price >= $priceFrom AND total_price <= $priceTo";
                    break; 
                case 5:
                    $sql_count = "SELECT * FROM entry_slips WHERE total_price >= $priceFrom AND total_price <= $priceTo AND DATE(date_entry) BETWEEN 
                    '$dateFrom' AND '$dateTo'";
                    break;
            }

            $result_count = mysqli_query($connection, $sql_count);
            $row_count = mysqli_num_rows($result_count);
            $data->number = $row_count;

            echo json_encode($data);
        } else {
            echo json_encode(["error" => "Invalid query parameters"]);
        }
    }
?>