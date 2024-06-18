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

        $sql = "SELECT products.name as nameProduct, products.image as imageProduct, products.price as priceProduct,
         users.fullname as nameUser, bills.date_create as dateCreate,
        bills.payment_method as paymentMethod, bills.number_account_bank as numberAccountBank, bills.total_price as totalPrice, 
        bill_details.quantity as quantityProduct, bills.address as address 
        FROM products JOIN bill_details ON products.id = bill_details.product_id 
        JOIN bills ON bill_details.bill_id = bills.id JOIN users ON bills.user_id = users.id WHERE bills.id = ?";

        $data = array();
        $result = DataSQL::querySQLAll($sql, [$idOrder]);
        while($row = mysqli_fetch_array($result)) {
            $data[] = $row;
        }

        $sqlNameStaff = "SELECT users.fullname as nameStaff FROM users JOIN bills ON users.id = bills.staff_id WHERE bills.id = ?";
        $resultNameStaff = DataSQL::querySQLAll($sqlNameStaff, [$idOrder]);
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
            $sqlDeleteDetailBill = "DELETE FROM bill_details WHERE bill_id = ?";
            DataSQL::executeSQL($sqlDeleteDetailBill, [$idDelete]);
            $sqlDeleteBill = "DELETE FROM bills WHERE id = ?";
            DataSQL::executeSQL($sqlDeleteBill, [$idDelete]);
        }
    }
    function ConfirmOrder()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        session_start();
        $isStatus = 2;
        $idBill = $_POST['id_bill'];
        $idStaff = "";
        if(isset($_SESSION['account']))
        {
            $idStaff = $_SESSION['account']['id_user'];
        }
        $sql = "UPDATE bills SET staff_id = ?, bill_status_id = ? WHERE id = ?";
        DataSQL::executeSQL($sql, [$idStaff, $isStatus, $idBill]);
    }
    function DisplayDefaultOrder()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $pageSize = isset($_POST['pageSize']) ? $_POST['pageSize'] : 5;
        $startPage = ($page - 1) * $pageSize;
        $sql = "SELECT users.*, bills.*, bills.id as idBill, bills.bill_status_id FROM bills JOIN users ON bills.user_id = users.id LIMIT ?, ?";
        $result = DataSQL::querySQLAll($sql, [$startPage, $pageSize]);
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
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $idOrder = isset($_POST['id_order']) ? $_POST['id_order'] : 0;
        $nameCustomer = isset($_POST['name_customer']) ? "%" . trim($_POST['name_customer'] . "%", '"') : "";
        $dateFrom = isset($_POST['date_from']) ? trim($_POST['date_from'], '"') : "";
        $dateTo = isset($_POST['date_to']) ? trim($_POST['date_to'], '"') : "";
        $status = isset($_POST['status']) ? $_POST['status'] : 0;
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $pageSize = isset($_POST['pageSize']) ? $_POST['pageSize'] : 7;
        $startPage = ($page - 1) * $pageSize;

        $sql = "";
        $check = "";
        $result = "";
        $noStatus = 1;
        $yesStatus = 2;
        if ($status == 1) {
            $sql = "SELECT bills.*, bills.id as idBill, users.fullname FROM bills LEFT JOIN users ON bills.user_id = users.id WHERE bill_status_id = ? LIMIT ?, ?";
            $check = 1;
            $result = DataSQL::querySQLAll($sql, [$noStatus, $startPage, $pageSize]);
        } else if ($status == 2) {
            $sql = "SELECT bills.*, bills.id as idBill, users.fullname FROM bills LEFT JOIN users ON bills.user_id = users.id WHERE bill_status_id = ? LIMIT ?, ?";
            $check = 2;
            $result = DataSQL::querySQLAll($sql, [$yesStatus, $startPage, $pageSize]);
        } else if($status == 0) {
            $sql = "SELECT bills.*, bills.id as idBill, users.fullname FROM bills LEFT JOIN users ON bills.user_id = users.id LIMIT ?, ?";
            $check = 3;
            $result = DataSQL::querySQLAll($sql, [$startPage, $pageSize]);
        }
        if ($idOrder != 0)
        {
            $sql = "SELECT bills.*, bills.id as idBill, users.fullname FROM bills LEFT JOIN users ON bills.user_id = users.id WHERE bills.id = ? LIMIT ?, ?";
            $check = 4;
            $result = DataSQL::querySQLAll($sql, [$idOrder, $startPage, $pageSize]);
        }
        if ($idOrder == 0 && $nameCustomer != "" && $dateFrom == "" && $dateTo == "" && $status == 0) 
        {
            $sql = "SELECT bills.*, bills.id as idBill, users.fullname FROM bills JOIN users ON bills.user_id = users.id WHERE users.fullname LIKE ? LIMIT ?, ?";
            $check = 5;
            $result = DataSQL::querySQLAll($sql, [$nameCustomer, $startPage, $pageSize]);
        } 
        else if ($idOrder == 0 && $nameCustomer == "" && $dateFrom != "" && $dateTo != "" && $status == 0) 
        {
            $sql = "SELECT bills.*, bills.id as idBill, users.fullname FROM bills LEFT JOIN users ON bills.user_id = users.id WHERE DATE(date_create) BETWEEN ? AND ? LIMIT ?, ?";
            $check = 6;
            $result = DataSQL::querySQLAll($sql, [$dateFrom, $dateTo, $startPage, $pageSize]);
        } 
        else if ($idOrder == 0 && $nameCustomer != "" && $dateFrom != "" && $dateTo != "" && $status == 0) 
        {
            $sql = "SELECT bills.*, bills.id as idBill, users.fullname FROM bills JOIN users ON bills.user_id = users.id WHERE users.fullname LIKE ? AND DATE(date_create) BETWEEN ? AND ? LIMIT ?, ?";
            $check = 7;
            $result = DataSQL::querySQLAll($sql, [$nameCustomer, $dateFrom, $dateTo, $startPage, $pageSize]);
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
                    $sql_count = "SELECT * FROM bills WHERE bill_status_id = ?";
                    $row_count = DataSQL::querySQLCount($sql_count, [$noStatus]);
                    break;
                case 2:
                    $sql_count = "SELECT * FROM bills WHERE bill_status_id = ?";
                    $row_count = DataSQL::querySQLCount($sql_count, [$yesStatus]);
                    break;
                case 3:
                    $sql_count = "SELECT * FROM bills";
                    $row_count = DataSQL::querySQL($sql_count);
                    break;
                case 4:
                    $sql_count = "SELECT * FROM bills WHERE id = ?";
                    $row_count = DataSQL::querySQLCount($sql_count, [$idOrder]);
                    break;
                case 5:
                    $sql_count = "SELECT * FROM bills JOIN users ON bills.user_id = users.id WHERE users.fullname LIKE ?";
                    $row_count = DataSQL::querySQLCount($sql_count, [$nameCustomer]);
                    break;
                case 6:
                    $sql_count = "SELECT * FROM bills WHERE DATE(date_create) BETWEEN ? AND ?";
                    $row_count = DataSQL::querySQLCount($sql_count, [$dateFrom, $dateTo]);
                    break;
                case 7:
                    $sql_count = "SELECT * FROM bills JOIN users ON bills.user_id = users.id 
                    WHERE users.fullname LIKE ? AND DATE(date_create) BETWEEN ? AND ?";
                    $row_count = DataSQL::querySQLCount($sql_count, [$nameCustomer, $dateFrom, $dateTo]);
                    break;
                
            }

            $data->number = $row_count;

            echo json_encode($data);
        } else {
            echo json_encode(["error" => "Invalid query parameters"]);
        }
    }
?>
