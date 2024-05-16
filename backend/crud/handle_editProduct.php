<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/frontend/includes/config.php";
    $idProduct = $_POST['id_product'];
    $nameProduct = $_POST['name_product'];
    $imageProduct = $_POST['image_product'];
    $priceProduct = $_POST['price_product'];
    $categoryProduct = $_POST['category_product'];
    $authorProduct = $_POST['author_product'];
    $publisherProduct = $_POST['publisher_product'];
    $quantityProduct = $_POST['quantity_product'];
    $publishYear = $_POST['publish_year'];
    $detailProduct = $_POST['detail_product'];
    $sqlCheck = "SELECT * FROM products WHERE (name = '$nameProduct' || image = '$imageProduct') AND id != '$idProduct'";
    $resultCheck = mysqli_query($connection, $sqlCheck);
    $row = mysqli_num_rows($resultCheck);
    if($row == 0)
    {
        $sql = "UPDATE products SET name = '$nameProduct', image = '$imageProduct', price = '$priceProduct', publisher_id = '$publisherProduct',
        quantity = '$quantityProduct', publish_year = '$publishYear', detail = '$detailProduct' WHERE id = '$idProduct'";
        mysqli_query($connection, $sql);

        $sqlCheckCategory = "SELECT * FROM product_categories WHERE product_id = '$idProduct'";
        $resultCheckCategory = mysqli_query($connection, $sqlCheckCategory);
        $rowCheckCategory = mysqli_num_rows($resultCheckCategory);
        if($rowCheckCategory != 0)
        {
            $sqlCategory = "UPDATE product_categories SET category_id = '$categoryProduct' WHERE product_id = '$idProduct'";
        }
        else 
        {
            $sqlCategory= "INSERT INTO product_categories(product_id, category_id) VALUES('$idProduct', '$categoryProduct')";
        }
        mysqli_query($connection, $sqlCategory);

        $sqlCheckAuthor = "SELECT * FROM product_authors WHERE product_id = '$idProduct'";
        $resultCheckAuthor = mysqli_query($connection, $sqlCheckAuthor);
        $rowCheckAuthor = mysqli_num_rows($resultCheckAuthor);
        if($rowCheckAuthor != 0)
        {
            
            $sqlAuthor = "UPDATE product_authors SET author_id = '$authorProduct' WHERE product_id = '$idProduct'";
        }
        else 
        {
            $sqlAuthor = "INSERT INTO product_authors(product_id, author_id) VALUES('$idProduct', '$authorProduct')";
        }
        mysqli_query($connection, $sqlAuthor);
        echo json_encode(array("success" => "Chỉnh sửa sản phẩm $nameProduct thành công"));
    }
    else 
    {
        echo json_encode(array("fail" => "Sản phẩm $nameProduct đã tồn tại. Không thể chỉnh sửa sản phẩm này"));
    }
?>
