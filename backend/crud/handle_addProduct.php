<?php 
    include "../../frontend/includes/config.php";
        $nameProduct = $_POST['name_product'];
        $imageProduct = $_POST['image_product'];
        $priceProduct = $_POST['price_product'];
        $categoryProduct = $_POST['category_product'];
        $authorProduct = $_POST['author_product'];
        $publisherProduct = $_POST['publisher_product'];
        $quantityProduct = $_POST['quantity_product'];
        $publishYear = $_POST['publish_year'];
        $detail_product = $_POST['detail_product'];
        $sql_check = "SELECT * FROM products WHERE name = '$nameProduct' OR image = '$imageProduct'";
        $result = mysqli_query($connection, $sql_check);
        $row_check = mysqli_num_rows($result);
        if($row_check == 0)
        {
            $sql_product = "INSERT INTO products(name, publisher_id, image, price, quantity, publish_year, detail) VALUES('$nameProduct', 
            '$publisherProduct', '$imageProduct', '$priceProduct', '$quantityProduct', '$publishYear', '$detail_product')";
            mysqli_query($connection, $sql_product);
            $productId = mysqli_insert_id($connection);
    
            $sql_category = "INSERT INTO product_categories(product_id, category_id) VALUES('$productId', '$categoryProduct')";
            // mysqli_query($connection, $sql_category);
    
            $sql_author = "INSERT INTO product_authors(product_id, author_id) VALUES('$productId', '$authorProduct')";
            // mysqli_query($connection, $sql_author);
            echo json_encode(array("status" => "Thêm sản phẩm $nameProduct vào cửa hàng thành công"));
        }
        else 
        {
            echo json_encode(array("status" => "Sản phẩm $nameProduct đã tồn tại trong cửa hàng"));
        }
?>
