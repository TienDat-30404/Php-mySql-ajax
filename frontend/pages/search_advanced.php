<?php 
    include "../includes/config.php";
    $nameSearchAdvanced = isset($_GET['nameSearchAdvanced']) ? $_GET['nameSearchAdvanced'] : "";
    $selectSearch = isset($_GET['search_select']) ? $_GET['search_select'] : 0;
    $priceFrom = isset($_GET['priceFrom']) ? $_GET['priceFrom'] : "";
    $priceTo = isset($_GET['priceTo']) ? $_GET['priceTo'] : "";
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : 5;
    $startPage = ($page - 1) * $pageSize;
    $check = "";
    if($selectSearch != 0 && !empty($priceFrom) && !empty($priceTo) && !empty($nameSearchAdvanced))
    {
    $sql = "SELECT products.*, products.name as nameProduct FROM products INNER JOIN product_categories ON products.id = product_categories.product_id INNER JOIN categories ON 
    product_categories.category_id = categories.id WHERE categories.id = '$selectSearch' AND products.name like '%" . $nameSearchAdvanced . "%'
     AND price >= '$priceFrom' AND price <= '$priceTo' AND isActive = 1 LIMIT $startPage, $pageSize";
        $check = 1;
    }
    else if($selectSearch != 0 && (empty($priceFrom) || empty($priceTo) && !empty($nameSearchAdvanced)))
    {
        $sql = "SELECT products.*, products.name as nameProduct FROM products INNER JOIN product_categories ON products.id = product_categories.product_id INNER JOIN categories ON 
    product_categories.category_id = categories.id WHERE categories.id = '$selectSearch' AND products.name like '%" . $nameSearchAdvanced . "%' AND isActive = 1 LIMIT $startPage, $pageSize";
        $check = 2;
    }
    else if($selectSearch != 0 && !empty($priceFrom) && !empty($priceTo) && empty($nameSearchAdvanced))
    {
        $sql = "SELECT products.*, products.name as nameProduct FROM products INNER JOIN product_categories ON products.id = product_categories.product_id INNER JOIN categories ON 
        product_categories.category_id = categories.id WHERE categories.id = '$selectSearch' AND price >= '$priceFrom' AND price <= '$priceTo' AND isActive = 1 LIMIT $startPage, $pageSize";
        $check = 3;
    }
    else if($selectSearch != 0 && (empty($priceFrom) || empty($priceTo)) && empty($nameSearchAdvanced))
    {
        $sql = "SELECT products.*, products.name as nameProduct FROM products INNER JOIN product_categories ON products.id = product_categories.product_id INNER JOIN categories ON 
        product_categories.category_id = categories.id WHERE categories.id = '$selectSearch' AND isActive = 1 LIMIT $startPage, $pageSize";
        $check = 4;
    }
    else if($selectSearch == 0 && (empty($priceFrom) || empty($priceTo)) && !empty($nameSearchAdvanced))
    {
        $sql = "SELECT products.*, products.name as nameProduct FROM products WHERE products.name like '%" . $nameSearchAdvanced . "%'
        AND isActive = 1 LIMIT $startPage, $pageSize";
        $check = 5;
    }
    else if($selectSearch == 0 && !empty($priceFrom) && !empty($priceTo) && empty($nameSearchAdvanced))
    {
        $sql = "SELECT products.*, products.name as nameProduct FROM products WHERE price >= '$priceFrom' 
        AND price <= '$priceTo' AND isActive = 1 LIMIT $startPage, $pageSize";
        $check = 6;
    }
    else if($selectSearch == 0 && (empty($priceFrom) || empty($priceTo)) && empty($nameSearchAdvanced))
    {
        $sql = "SELECT products.*, products.name as nameProduct FROM products AND isActive = 1 LIMIT $startPage, $pageSize";
        $check = 7;
    }
    else if($selectSearch == 0 && !empty($priceFrom) && !empty($priceTo) && !empty($nameSearchAdvanced))
    {
        $sql = "SELECT products.*, products.name as nameProduct FROM products WHERE price >= '$priceFrom' AND price <= '$priceTo' AND
         products.name like '%" . $nameSearchAdvanced . "%' AND isActive = 1 LIMIT $startPage, $pageSize";
        $check = 8;
    }
    $result = mysqli_query($connection, $sql);
    $informations = array();
    $data = new stdClass();
    while($row = mysqli_fetch_array($result))
    {
        $informations[] = $row;
    }
    $data->informations = $informations;
    if($check == 1)
    {
        $sql_count = "SELECT products.*, products.name as nameProduct FROM products INNER JOIN product_categories ON products.id = product_categories.product_id INNER JOIN categories ON 
        product_categories.category_id = categories.id WHERE categories.id = '$selectSearch' AND products.name like '%" . $nameSearchAdvanced . "%'
         AND price >= '$priceFrom' AND price <= '$priceTo' AND isActive = 1";
    }
    else if($check == 2)
    {
        $sql_count = "SELECT products.*, products.name as nameProduct FROM products INNER JOIN product_categories ON products.id = product_categories.product_id INNER JOIN categories ON 
        product_categories.category_id = categories.id WHERE categories.id = '$selectSearch' AND products.name like '%" . $nameSearchAdvanced . "%' AND isActive = 1";
    }
    else if($check == 3)
    {
        $sql_count = "SELECT products.*, products.name as nameProduct FROM products INNER JOIN product_categories ON products.id = product_categories.product_id INNER JOIN categories ON 
        product_categories.category_id = categories.id WHERE categories.id = '$selectSearch' AND price >= '$priceFrom' AND price <= '$priceTo' AND isActive = 1";
    }
    else if($check == 4)
    {
        $sql_count = "SELECT products.*, products.name as nameProduct FROM products INNER JOIN product_categories ON products.id = product_categories.product_id INNER JOIN categories ON 
        product_categories.category_id = categories.id WHERE categories.id = '$selectSearch' AND isActive = 1";
    }
    else if($check == 5)
    {
        $sql_count = "SELECT products.*, products.name as nameProduct FROM products WHERE products.name like '%" . $nameSearchAdvanced . "%' AND isActive = 1";
    } 
    else if($check == 6)
    {
        $sql_count = "SELECT products.*, products.name as nameProduct FROM products WHERE price >= '$priceFrom' AND price <= '$priceTo' AND isActive = 1";
    }
    else if($check == 7)
    {
        $sql_count = "SELECT products.*, products.name as nameProduct FROM products WHERE isActive = 1";
    }
    else if($check == 8)
    {
        $sql_count = "SELECT products.*, products.name as nameProduct FROM products WHERE price >= '$priceFrom' AND price <= '$priceTo' AND
        products.name like '%" . $nameSearchAdvanced . "%' AND isActive = 1";
    }
    $result_count = mysqli_query($connection, $sql_count);
    $row_count = mysqli_num_rows($result_count);
    $data->number = $row_count;
    echo json_encode($data);
