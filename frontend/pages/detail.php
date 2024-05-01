<?php 
    include "../includes/config.php";
    $id_detail = $_GET['id_detail'];
    $sql = "SELECT products.id, products.detail, products.image, products.name as nameProduct, authors.name as nameAuthor, products.publish_year, products.price, publishers.name as
    namePublisher FROM publishers INNER JOIN products ON products.publisher_id = publishers.id INNER JOIN product_authors ON products.id = product_authors.product_id 
    INNER JOIN authors ON product_authors.author_id = authors.id WHERE products.id='$id_detail'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_array($result);
    
    $sql_1 = "SELECT categories.name as nameCategory FROM categories INNER JOIN product_categories ON categories.id = product_categories.category_id INNER JOIN products 
    ON products.id = product_categories.product_id WHERE products.id = '$id_detail'";
    $result_1 = mysqli_query($connection, $sql_1);
    $row_1 = mysqli_fetch_array($result_1);
    $informations = array(
        'id' => $row['id'],
        'image' => $row['image'],
        'nameProduct' => $row['nameProduct'],
        'nameAuthor' => $row['nameAuthor'],
        'publishYear' => $row['publish_year'],
        'price' => $row['price'],
        'namePublisher' => $row['namePublisher'],
        'detail' => $row['detail'],
        'nameCategory' => $row_1['nameCategory']
    );
    $data = new stdClass();
    $data->informations = $informations;
    echo json_encode($data);
?>
     
