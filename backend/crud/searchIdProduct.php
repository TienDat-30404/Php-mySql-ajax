<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    $idProduct = $_GET['inputSearchName'];
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : 5;
    $startPage = ($page - 1) * $pageSize;
    $sql = "SELECT products.isActive, products.id, products.name as nameProduct, products.price, products.image as imageProduct,
    products.quantity, products.publish_year
        FROM products WHERE products.isActive = 1 
        AND id = '$idProduct' LIMIT $startPage, $pageSize";
    $result = DataSQL::querySQL($sql);
    $informations = array();
    $data = new stdClass();
    while($row = mysqli_fetch_array($result))
    {
        $informations[] = $row;
    }
    $data->informations = $informations;
    $sql_count = "SELECT * FROM products WHERE isActive = 1 AND id = '$idProduct'";
    $result_count = DataSQL::querySQL($sql_count);
    $row_count = mysqli_num_rows($result_count);
    $data->number = $row_count;
    echo json_encode($data);

?>