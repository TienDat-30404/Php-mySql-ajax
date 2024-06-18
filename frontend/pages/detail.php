<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    $id_detail = $_GET['id_detail'];
    $sql = "SELECT products.id, products.detail, products.image, products.name as nameProduct, authors.name as nameAuthor, 
    products.publish_year, products.price, publishers.name as namePublisher 
    FROM publishers LEFt JOIN products ON products.publisher_id = publishers.id 
    LEFT JOIN product_authors ON products.id = product_authors.product_id 
    LEFT JOIN authors ON product_authors.author_id = authors.id 
    WHERE products.id=?";
    $result = DataSQL::querySQLAll($sql, [$id_detail]);
    $row = mysqli_fetch_array($result);
    
    $sql_1 = "SELECT categories.name as nameCategory 
    FROM categories 
    INNER JOIN product_categories ON categories.id = product_categories.category_id 
    INNER JOIN products ON products.id = product_categories.product_id 
    WHERE products.id = ?";
    $result_1 = DataSQL::querySQLAll($sql_1, [$id_detail]);
    $categories = [];
    while($row_1 = mysqli_fetch_array($result_1)) {
        $categories[] = $row_1['nameCategory'];
    }

    $nameCategory = !empty($categories) ? implode(", ", $categories) : "Chưa có thể loại";
    $nameAuthor = "";
    if($row['nameAuthor'] == null)
    {
        $nameAuthor = "Chưa có tác giả";
    }
    else 
    {
        $nameAuthor = $row['nameAuthor'];
    }
    $informations = array(
        'id' => $row['id'],
        'image' => $row['image'],
        'nameProduct' => $row['nameProduct'],
        'nameAuthor' => $nameAuthor,
        'publishYear' => $row['publish_year'],
        'price' => $row['price'],
        'namePublisher' => $row['namePublisher'],
        'detail' => $row['detail'],
        'nameCategory' => $nameCategory
    );
    $data = new stdClass();
    $data->informations = $informations;
    echo json_encode($data);
?>
     
