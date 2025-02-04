<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    $isActive = 1;
    $nameSearch = "%" . $_GET['inputSearchName'] . "%";
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : 5;
    $startPage = ($page - 1) * $pageSize;
    $sql = "SELECT products.isActive, products.id, products.name as nameProduct, products.price, products.image,
    products.quantity, products.publish_year
        FROM products WHERE products.isActive = ?
        AND name LIKE ? LIMIT ?, ?";
    $result = DataSQL::querySQLAll($sql, [$isActive, $nameSearch, $startPage, $pageSize]);
    $informations = array();
    $data = new stdClass();
    while($row = mysqli_fetch_array($result))
    {
        $informations[] = $row;
    }
    $data->informations = $informations;
    $sql_count = "SELECT * FROM products WHERE isActive = ? AND name like ?";
    $row_count = DataSQL::querySQLCount($sql_count, [$isActive, $nameSearch]);
    $data->number = $row_count;
    echo json_encode($data);
        
    
?>