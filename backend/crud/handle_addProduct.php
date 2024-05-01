<?php 
    include "../../frontend/includes/config.php";
        $nameProduct = $_GET['name_product'];
        echo $nameProduct;
        $imageProduct = $_GET['image_product'];
        $priceProduct = $_GET['price_product'];
        $categoryProduct = $_GET['category_product'];
        $authorProduct = $_GET['author_product'];
        $publisherProduct = $_GET['publisher_product'];
        $quantityProduct = $_GET['quantity_product'];
        $publishYear = $_GET['publish_year'];
        $detail_product = $_GET['detail_product'];
        
        $sql_product = "INSERT INTO products(name, publisher_id, image, price, quantity, publish_year, detail) VALUES('$nameProduct', 
        '$publisherProduct', '$imageProduct', '$priceProduct', '$quantityProduct', '$publishYear', '$detail_product')";
        mysqli_query($connection, $sql_product);
        $productId = mysqli_insert_id($connection);

        $sql_category = "INSERT INTO product_categories(product_id, category_id) VALUES('$productId', '$categoryProduct')";
        mysqli_query($connection, $sql_category);

        $sql_author = "INSERT INTO product_authors(product_id, author_id) VALUES('$productId', '$authorProduct')";
        mysqli_query($connection, $sql_author);
?>
