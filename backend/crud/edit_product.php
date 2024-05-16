



<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    if(isset($_GET['id_edit']))
    {
        $idEdit = $_GET['id_edit'];
        $sql = "SELECT 
        products.id,
        products.publish_year as publish_year,
        products.name as product_name, 
        publishers.name as publisher_name, 
        products.image,
        products.price,
        products.quantity,
        products.publish_year,
        products.detail,
        (
            SELECT GROUP_CONCAT(categories.name)
        FROM categories INNER JOIN product_categories ON categories.id = product_categories.category_id
        WHERE product_categories.product_id = '$idEdit'
        ) AS category_name,
        (
        SELECT GROUP_CONCAT(name)
        FROM authors INNER JOIN product_authors ON authors.id = product_authors.author_id
        WHERE product_authors.product_id = '$idEdit'
        ) AS author_name
        FROM 
                products 
            LEFT JOIN 
                product_categories ON products.id = product_categories.product_id
            LEFT JOIN
                product_authors ON products.id = product_authors.product_id
            INNER JOIN
        publishers ON products.publisher_id = publishers.id
        WHERE products.id= '$idEdit'
        GROUP BY 
            products.id, 
            products.name,
            publishers.name,
            products.image, 
            products.price, 
            products.quantity,
            products.publish_year,
            products.detail";
        $result = DataSQL::querySQL($sql);
        $data = array();
        $informations = array();
        while($row = mysqli_fetch_array($result))
        {
            $informations[] = $row;
        }
        $data['informations'] = $informations;
         $sqlCategories = "SELECT * FROM categories";
        $resultCategories = DataSQL::querySQL($sqlCategories);
        $categories = array();

        while($category = mysqli_fetch_array($resultCategories)) {
            $categories[] = $category;
        }
        $data['categories'] = $categories;

        $authors = array();
        $sqlAuthor = "SELECT * FROM authors";
        $resultAuthor = DataSQL::querySQL($sqlAuthor);
        while($row = mysqli_fetch_array($resultAuthor))
        {
            $authors[] = $row;
        }
        $data['authors'] = $authors;

        $publishers = array();
        $sqlPublishers = "SELECT * FROM publishers";
        $resultPublishers = DataSQL::querySQL($sqlPublishers);
        while($row = mysqli_fetch_array($resultPublishers))
        {
            $publishers[] = $row;
        }
        $data['publishers'] = $publishers;
        echo json_encode($data);
    }
?>