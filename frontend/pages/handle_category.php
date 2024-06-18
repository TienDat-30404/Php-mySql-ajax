<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    $idCategory = '';
    $isActive = 1;
    if(isset($_GET['id_category']))
    {
        $idCategory = $_GET['id_category'];
    }
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : 5;
    $startPage = ($page - 1) * $pageSize;
    $sql = "SELECT products.id, products.name as nameProduct, products.price, products.image 
    FROM products  
    INNER JOIN product_categories ON products.id = product_categories.product_id 
    INNER JOIN categories ON product_categories.category_id = categories.id 
    WHERE categories.id = ? AND isActive = ? LIMIT ?, ?";
    $result = DataSQL::querySQLAll($sql, [$idCategory, $isActive, $startPage, $pageSize]);
    $informations = array();
    $data = new stdClass();
    while($row = mysqli_fetch_array($result))
    {
        $informations[] = $row;
    }
    $data->informations = $informations;
    $sql_count = "SELECT products.isActive, products.id, products.name as nameProduct, products.price, products.image 
    FROM products INNER JOIN product_categories ON products.id = product_categories.product_id 
    INNER JOIN categories ON product_categories.category_id = categories.id 
    WHERE categories.id = ? AND products.isActive = ?";
    $row_count = DataSQL::querySQLCount($sql_count, [$idCategory, $isActive]);
    $data->number = $row_count;
    echo json_encode($data);
    
    
?>