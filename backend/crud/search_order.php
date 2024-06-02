<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/frontend/includes/config.php";

$idOrder = isset($_GET['id_order']) ? $_GET['id_order'] : 0;
$nameCustomer = isset($_GET['name_customer']) ? trim($_GET['name_customer'], '"') : '';
$dateFrom = isset($_GET['date_from']) ? trim($_GET['date_from'], '"') : '';
$dateTo = isset($_GET['date_to']) ? trim($_GET['date_to'], '"') : '';
$status = isset($_GET['status']) ? $_GET['status'] : 0;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : 7;
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
?>