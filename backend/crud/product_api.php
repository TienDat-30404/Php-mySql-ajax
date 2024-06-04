<?php
    $choice = isset($_POST['choice']) ? $_POST['choice'] : "";
    switch($choice)
    {
        case 'add_product':
            AddProduct();
            break;
        case 'restore_product':
            RestoreProduct();
            break;
        case 'product_noExist':
            ProductNoExist();
            break;
        case 'display_edit_product':
            DisplayEditProduct();
            break;
        case 'delete_product':
            DeleteProduct();
            break;
        case 'get_all_product':
            GetAllProduct();
            break;
        case 'handle_edit_product':
            HandleEditProduct();
            break;
        case 'default_display_product':
            DefaultDisplayProduct();
            break;
        case 'search_product':
            SearchProduct();
            break;
        case 'search_id_product':
            SearchIdProduct();
            break;
    }
    function AddProduct()
    {
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
            mysqli_query($connection, $sql_category);
    
            $sql_author = "INSERT INTO product_authors(product_id, author_id) VALUES('$productId', '$authorProduct')";
            mysqli_query($connection, $sql_author);
            echo json_encode(array("status" => "Thêm sản phẩm $nameProduct vào cửa hàng thành công"));
        }
        else 
        {
            echo json_encode(array("status" => "Sản phẩm $nameProduct đã tồn tại trong cửa hàng"));
        }
    }
    function RestoreProduct()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $idRestore = $_POST['id_restore'];
        $sqlRestore = "UPDATE products SET isActive = 1 WHERE id = '$idRestore'";
        DataSQL::querySQL($sqlRestore);
    }
    function ProductNoExist()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $sql = "SELECT * FROM products WHERE isActive = 0";
        $result = DataSQL::querySQL($sql);
        $data = array();
        while($row = mysqli_fetch_array($result))
        {
            $data[] = $row;
        }
        echo json_encode($data);  
    }
    function DisplayEditProduct()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        if(isset($_POST['id_edit']))
        {
            $idEdit = $_POST['id_edit'];
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
    }
    function DeleteProduct()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        if(isset($_POST['id_delete']))
        {
            $idDelete = $_POST['id_delete'];
            $sql = "UPDATE products SET isActive = 0 WHERE id = '$idDelete'";
            DataSQL::querySQL($sql);
        }
    }
    function GetAllProduct()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $sql = "SELECT * FROM products WHERE isActive = 1";
        $result = DataSQL::querySQL($sql);
        $data = array();
        while($row = mysqli_fetch_array($result))
        {
            $data[] = $row;
        }
        echo json_encode($data);
    }
    function HandleEditProduct()
    {
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
    }
    function DefaultDisplayProduct()
    {
        include "../../frontend/includes/config.php";
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $pageSize = isset($_POST['pageSize']) ? $_POST['pageSize'] : 5;
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
    }
    function SearchProduct()
    {
        include "../../frontend/includes/config.php";
        $nameSearchAdvanced = $_POST['nameSearchAdvanced'];
        $selectSearch = $_POST['search_select'];
        $priceFrom = $_POST['priceFrom'];
        $priceTo = $_POST['priceTo']; 
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $pageSize = isset($_POST['pageSize']) ? $_POST['pageSize'] : 5;
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
            $sql = "SELECT products.*, products.name as nameProduct FROM products WHERE isActive = 1 LIMIT $startPage, $pageSize";
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
    }
    function SearchIdProduct()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $idProduct = $_POST['inputSearchName'];
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $pageSize = isset($_POST['pageSize']) ? $_POST['pageSize'] : 5;
        $startPage = ($page - 1) * $pageSize;
        $sql = "SELECT products.isActive, products.id, products.name as nameProduct, products.price, products.image,
        products.quantity, products.publish_year
            FROM products WHERE products.isActive = 1 
            AND id = '$idProduct' LIMIT $startPage, $pageSize";
        $result = DataSQL::querySQL($sql);
        $informations = array();
        $data = new stdClass();
        while($row = mysqli_fetch_array($result))
        {
            $informations[] = $row;
        }
        $data->informations = $informations;
        $sql_count = "SELECT * FROM products WHERE isActive = 1 AND id = '$idProduct'";
        $result_count = DataSQL::querySQL($sql_count);
        $row_count = mysqli_num_rows($result_count);
        $data->number = $row_count;
        echo json_encode($data);
    }
?>