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
                JOIN products ON entry_slip_details.product_id = products.id WHERE entry_slips.id = ?";
        $data = array();
        $result = DataSQL::querySQLAll($sql, [$idReceipt]);
        while($row = mysqli_fetch_array($result)) {
            $data[] = $row;
        }

        echo json_encode($data);
    }
    function CreateReceipt()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        // $timeCurrent = date('Y-m-d H:i:s');
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
        
        $sql_entry_slips = "INSERT INTO entry_slips(staff_id, date_entry, total_price, supplier_id) VALUES(?, NOW(), ?, ?)";
        $stmt = DataSQL::executeSQLToSTMT($sql_entry_slips, [$idStaff, $totalPrice, $idSupplier]);
        $idEntrySlips = $stmt->insert_id;
    
                for($i = 0; $i < count($idProduct); $i++)
                {
                    // Insert entry_slip_details
                    $idDetailProduct = $idProduct[$i];
                    $detailQuantity = $quantityProduct[$i];
                    $detailPrice = $priceProduct[$i];
                    $sqlInsertDetail = "INSERT INTO entry_slip_details(entry_slip_id, product_id, quantity, entry_price) VALUES(?, ?, ?, ?)";
                    DataSQL::executeSQL($sqlInsertDetail, [$idEntrySlips, $idDetailProduct, $detailQuantity, $detailPrice]);
        
                    // update quantity product 
                    $sqlSelectProduct = "SELECT * FROM products WHERE id = ?";
                    $resultSelectProduct = DataSQL::querySQLAll($sqlSelectProduct, [$idDetailProduct]);
                    while($rowSelectProduct = mysqli_fetch_array($resultSelectProduct))
                    {

                        $updateQuantityProduct = $rowSelectProduct['quantity'] + $detailQuantity;
                    }
        
                    $sqlUpdateQuantity = "UPDATE products SET quantity = ? WHERE id = ?";
                    DataSQL::executeSQL($sqlUpdateQuantity, [$updateQuantityProduct, $idDetailProduct]);
                }
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
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";

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
        $result = "";
        if ($idReceipt == 0 && $dateFrom == "" && $dateTo == "" && $priceFrom == "" && $priceTo == "") {
            $sql = "SELECT * FROM entry_slips LIMIT ?, ?";
            $check = 1;
            $result = DataSQL::querySQLAll($sql, [$startPage, $pageSize]);
        }   
        else if ($idReceipt != 0 && $dateFrom == "" && $dateTo == ""  && $priceFrom == "" && $priceTo == "")
        {
            $sql = "SELECT * FROM entry_slips WHERE id = ? LIMIT ?, ?";
            $check = 2;
            $result = DataSQL::querySQLAll($sql, [$idReceipt, $startPage, $pageSize]);
        }
        else if ($idReceipt == 0 && $dateFrom != "" && $dateTo != "" && $priceFrom == "" && $priceTo == "") 
        {
            $sql = "SELECT * FROM entry_slips WHERE DATE(date_entry) BETWEEN ? AND ? LIMIT ?, ?";
            $check = 3;
            $result = DataSQL::querySQLAll($sql, [$dateFrom, $dateTo, $startPage, $pageSize]);
        } 
        else if ($idReceipt == 0 && $dateFrom == "" && $dateTo == "" && $priceFrom != "" && $priceTo != "") 
        {
            $sql = "SELECT * FROM entry_slips WHERE total_price >= ? AND total_price <= ? LIMIT ?, ?";
            $check = 4;
            $result = DataSQL::querySQLAll($sql, [$priceFrom, $priceTo, $startPage, $pageSize]);
        } 
        else if($idReceipt == 0 && $dateFrom != "" && $dateTo != "" && $priceFrom != "" && $priceTo != "")
        {
            $sql = "SELECT * FROM entry_slips WHERE total_price >= ? AND total_price <= ? AND DATE(date_entry) BETWEEN 
            ? AND ? LIMIT ?, ?";
            $check = 5;
            $result = DataSQL::querySQLAll($sql, [$priceFrom, $priceTo, $dateFrom, $dateTo, $startPage, $pageSize]);
        }
        if (!empty($sql)) {
            $data = new stdClass();
            $informations = array();
            while ($row = mysqli_fetch_array($result)) {
                $informations[] = $row;
            }
            $data->informations = $informations;

            $sql_count = "";
            $row_count = "";
            switch ($check) {
                case 1:
                    $sql_count = "SELECT * FROM entry_slips";
                    $row_count = DataSQL::querySQLCount($sql_count);
                    break;
                case 2:
                    $sql_count = "SELECT * FROM entry_slips WHERE id = ?";
                    $row_count = DataSQL::querySQLCount($sql_count, [$idReceipt]);
                    break;
                case 3:
                    $sql_count = "SELECT * FROM entry_slips WHERE DATE(date_entry) BETWEEN ? AND ?";
                    $row_count = DataSQL::querySQLCount($sql_count, [$dateFrom, $dateTo]);
                    break;
                case 4:
                    $sql_count = "SELECT * FROM entry_slips WHERE total_price >= ? AND total_price <= ?";
                    $row_count = DataSQL::querySQLCount($sql_count, [$priceFrom, $priceTo]);
                    break; 
                case 5:
                    $sql_count = "SELECT * FROM entry_slips WHERE total_price >= ? AND total_price <= ? AND DATE(date_entry) BETWEEN ? AND ?";
                    $row_count = DataSQL::querySQLCount($sql_count, [$priceFrom, $priceTo, $dateFrom, $dateTo]);
                    break;
            }

            $data->number = $row_count;

            echo json_encode($data);
        } else {
            echo json_encode(["error" => "Invalid query parameters"]);
        }
    }
?>