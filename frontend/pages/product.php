<?php 
    include "../includes/config.php";
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : 5;
    $startPage = ($page - 1) * $pageSize;
    $sql = "SELECT products.isActive, products.id, products.name as nameProduct, products.price, products.image, 
    products.quantity, products.publish_year
    FROM products
    WHERE products.isActive = 1 ORDER BY id LIMIT $startPage, $pageSize";
    $result = mysqli_query($connection, $sql);
    $informations = array();
    $data = new stdClass();
    while($row = mysqli_fetch_array($result))
    {
        $informations[] = $row;
    }
    $data->informations = $informations;
    $sql_count = "SELECT * FROM products WHERE isActive = 1";
    $result_count = mysqli_query($connection, $sql_count);
    $row_count = mysqli_num_rows($result_count);
    $data->number = $row_count;
    echo json_encode($data);
?>
