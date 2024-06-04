<?php 
    $choice = isset($_POST['choice']) ? $_POST['choice'] : "";
    switch($choice) 
    {
        case 'detail_order':
            DetailOrder();
            break;
        case 'delete_order':
            DeleteOrder();
            break;
        case 'confirm_order':
            ConfirmOrder();
            break;
        case 'display_default_order':
            DisplayDefaultOrder();
            break;
        case 'search_order':
            SearchOrder();
            break;
    }
    function DetailOrder()
    {
        $idOrder = $_POST['id_order'];
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";

        $sql = "SELECT products.name as nameProduct, products.image as imageProduct, products.price as priceProduct, users.fullname as nameUser, bills.date_create as dateCreate,
        bills.payment_method as paymentMethod, bills.number_account_bank as numberAccountBank, bills.total_price as totalPrice, 
        bill_details.quantity as quantityProduct, bills.address as address FROM products JOIN bill_details ON products.id = bill_details.product_id JOIN bills 
        ON bill_details.bill_id = bills.id JOIN users ON bills.user_id = users.id WHERE bills.id = '$idOrder'";

        $data = array();
        $result = DataSQL::querySQL($sql);
        while($row = mysqli_fetch_array($result)) {
            $data[] = $row;
        }

        $sqlNameStaff = "SELECT users.fullname as nameStaff FROM users JOIN bills ON users.id = bills.staff_id WHERE bills.id = '$idOrder'";
        $resultNameStaff = DataSQL::querySQL($sqlNameStaff);
        $rowNameStaff = mysqli_fetch_array($resultNameStaff);

        // Kiểm tra xem có nhân viên không, nếu có thì thêm vào từng phần tử trong mảng chi tiết đơn hàng
        if ($rowNameStaff) {
            foreach ($data as &$detail) {
                $detail['nameStaff'] = $rowNameStaff['nameStaff'];
            }
        }
        echo json_encode($data);
    }
    function DeleteOrder()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        if(isset($_POST['id_delete']))
        {
            $idDelete = $_POST['id_delete'];
            $sqlDeleteDetailBill = "DELETE FROM bill_details WHERE bill_id = '$idDelete'";
            DataSQL::querySQL($sqlDeleteDetailBill);
            $sqlDeleteBill = "DELETE FROM bills WHERE id = '$idDelete'";
            DataSQL::querySQL($sqlDeleteBill);
        }
    }
    function ConfirmOrder()
    {
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
    }
    function DisplayDefaultOrder()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $pageSize = isset($_POST['pageSize']) ? $_POST['pageSize'] : 5;
        $startPage = ($page - 1) * $pageSize;
        $sql = "SELECT users.*, bills.*, bills.id as idBill, bills.bill_status_id FROM bills JOIN users ON bills.user_id = users.id LIMIT $startPage,
        $pageSize";
        $result = DataSQL::querySQL($sql);
        $informations = array();
        $data = new stdClass();
        while($row = mysqli_fetch_array($result))
        {
            $informations[] = $row;
        }
        $data->informations = $informations;
        $sql_count = "SELECT users.*, bills.*, bills.id as idBill, bills.bill_status_id FROM bills JOIN users ON bills.user_id = users.id";
        $result_count = DataSQL::querySQL($sql_count);
        $row_count = mysqli_num_rows($result_count);
        $data->number = $row_count;
        echo json_encode($data);
    }
    function SearchOrder()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/frontend/includes/config.php";

        $idOrder = isset($_POST['id_order']) ? $_POST['id_order'] : 0;
        $nameCustomer = isset($_POST['name_customer']) ? trim($_POST['name_customer'], '"') : '';
        $dateFrom = isset($_POST['date_from']) ? trim($_POST['date_from'], '"') : '';
        $dateTo = isset($_POST['date_to']) ? trim($_POST['date_to'], '"') : '';
        $status = isset($_POST['status']) ? $_POST['status'] : 0;
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $pageSize = isset($_POST['pageSize']) ? $_POST['pageSize'] : 7;
        $startPage = ($page - 1) * $pageSize;

        $sql = "";
        $check = "";

        if ($idOrder == 0 && $nameCustomer == "" && $dateFrom == "" && $dateTo == "") {
            if ($status == 1) {
                $sql = "SELECT bills.*, bills.id as idBill, users.fullname FROM bills LEFT JOIN users ON bills.user_id = users.id WHERE bill_status_id = 1 LIMIT $startPage, $pageSize";
                $check = 1;
            } elseif ($status == 2) {
                $sql = "SELECT bills.*, bills.id as idBill, users.fullname FROM bills LEFT JOIN users ON bills.user_id = users.id WHERE bill_status_id = 2 LIMIT $startPage, $pageSize";
                $check = 2;
            } else if($status == 0) {
                $sql = "SELECT bills.*, bills.id as idBill, users.fullname FROM bills LEFT JOIN users ON bills.user_id = users.id LIMIT $startPage, $pageSize";
                $check = 3;
            }
        }   
        else if ($idOrder != 0 && $nameCustomer == "" && $dateFrom == "" && $dateTo == "" && $status == 0)
        {
            $sql = "SELECT bills.*, bills.id as idBill, users.fullname FROM bills LEFT JOIN users ON bills.user_id = users.id WHERE bills.id = '$idOrder' LIMIT $startPage, $pageSize";
            $check = 4;
        }
        else if ($idOrder == 0 && $nameCustomer != "" && $dateFrom == "" && $dateTo == "" && $status == 0) 
        {
            $sql = "SELECT bills.*, bills.id as idBill, users.fullname FROM bills JOIN users ON bills.user_id = users.id WHERE users.fullname LIKE '%" . $nameCustomer. "%' LIMIT $startPage, $pageSize";
            $check = 5;
        } 
        else if ($idOrder == 0 && $nameCustomer == "" && $dateFrom != "" && $dateTo != "" && $status == 0) 
        {
            $sql = "SELECT bills.*, bills.id as idBill, users.fullname FROM bills LEFT JOIN users ON bills.user_id = users.id WHERE DATE(date_create) BETWEEN '$dateFrom' AND '$dateTo' LIMIT $startPage, $pageSize";
            $check = 6;
        } 
        else if ($idOrder == 0 && $nameCustomer != "" && $dateFrom != "" && $dateTo != "" && $status == 0) 
        {
            $sql = "SELECT bills.*, bills.id as idBill, users.fullname FROM bills JOIN users ON bills.user_id = users.id WHERE users.fullname LIKE '%$nameCustomer%' AND DATE(date_create) BETWEEN '$dateFrom' AND '$dateTo' LIMIT $startPage, $pageSize";
            $check = 7;
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
                    $sql_count = "SELECT * FROM bills WHERE bill_status_id = 1";
                    break;
                case 2:
                    $sql_count = "SELECT * FROM bills WHERE bill_status_id = 2";
                    break;
                case 3:
                    $sql_count = "SELECT * FROM bills";
                    break;
                case 4:
                    $sql_count = "SELECT * FROM bills WHERE id = '$idOrder'";
                    break;
                case 5:
                    $sql_count = "SELECT * FROM bills JOIN users ON bills.user_id = users.id WHERE users.fullname LIKE '%" . $nameCustomer. "%'";
                    break;
                case 6:
                    $sql_count = "SELECT * FROM bills WHERE DATE(date_create) BETWEEN '$dateFrom' AND '$dateTo'";
                    break;
                case 7:
                    $sql_count = "SELECT * FROM bills JOIN users ON bills.user_id = users.id WHERE users.fullname LIKE '%$nameCustomer%' AND DATE(date_create) BETWEEN '$dateFrom' AND '$dateTo'";
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
