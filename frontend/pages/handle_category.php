<?php 
    include "../includes/config.php";
    $idCategory = '';
    if(isset($_GET['id_category']))
    {
        $idCategory = $_GET['id_category'];
    }
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : 5;
    $startPage = ($page - 1) * $pageSize;
    $sql = "SELECT products.id, products.name as nameProduct, products.price, products.image as imageProduct FROM products  INNER JOIN product_categories ON products.id = product_categories.product_id INNER JOIN
            categories ON product_categories.category_id = categories.id WHERE categories.id = '$idCategory' LIMIT $startPage, $pageSize";
    $result = mysqli_query($connection, $sql);
    $informations = array();
    $data = new stdClass();
    while($row = mysqli_fetch_array($result))
    {
        $informations[] = $row;
    }
    $data->informations = $informations;
    $sql_count = "SELECT products.id, products.name as nameProduct, products.price, products.image as imageProduct FROM products  INNER JOIN product_categories ON products.id = product_categories.product_id INNER JOIN
    categories ON product_categories.category_id = categories.id WHERE categories.id = '$idCategory'";
    $result_count = mysqli_query($connection, $sql_count);
    $row_count = mysqli_num_rows($result_count);
    $data->number = $row_count;
    echo json_encode($data);
    
    
?>