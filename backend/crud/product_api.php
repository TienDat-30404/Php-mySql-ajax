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
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $nameProduct = $_POST['name_product'];
        $imageProduct = $_POST['image_product'];
        $priceProduct = $_POST['price_product'];
        $categoryProduct = $_POST['category_product'];
        $authorProduct = $_POST['author_product'];
        $publisherProduct = $_POST['publisher_product'];
        $quantityProduct = $_POST['quantity_product'];
        $publishYear = $_POST['publish_year'];
        $detail_product = $_POST['detail_product'];

        // Kiểm tra sự tồn tại của sản phẩm
        $sql_check = "SELECT * FROM products WHERE name = ? OR image = ?";
        $row_check = DataSQL::querySQLCount($sql_check, [$nameProduct, $imageProduct]);
        if($row_check == 0)
        {
            $sql_product = "INSERT INTO products(name, publisher_id, image, price, quantity, publish_year, detail) VALUES(?, ?, ?, ?, ?, ?, ?)";
            $stmt = DataSQL::executeSQLToSTMT($sql_product, [$nameProduct, $publisherProduct, $imageProduct, $priceProduct, $quantityProduct,
            $publishYear, $detail_product]);
            if($stmt)
            {
                $productId = $stmt->insert_id;
        
                $sql_category = "INSERT INTO product_categories(product_id, category_id) VALUES(?, ?)";
                DataSQL::executeSQL($sql_category, [$productId, $categoryProduct]);
        
                $sql_author = "INSERT INTO product_authors(product_id, author_id) VALUES(?, ?)";
                DataSQL::executeSQL($sql_author, [$productId, $authorProduct]);
                echo json_encode(array("status" => "Thêm sản phẩm $nameProduct vào cửa hàng thành công"));
            }
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
        $sqlRestore = "UPDATE products SET isActive = 1 WHERE id = ?";
        DataSQL::querySQLAll($sqlRestore, [$idRestore]);
    }
    function ProductNoExist()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $isActive = 0;
        $sql = "SELECT * FROM products WHERE isActive = ?";
        $result = DataSQL::querySQLAll($sql, [$isActive]);
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
            WHERE product_categories.product_id = ?
            ) AS category_name,
            (
            SELECT GROUP_CONCAT(name)
            FROM authors INNER JOIN product_authors ON authors.id = product_authors.author_id
            WHERE product_authors.product_id = ?
            ) AS author_name
            FROM 
                    products 
                LEFT JOIN 
                    product_categories ON products.id = product_categories.product_id
                LEFT JOIN
                    product_authors ON products.id = product_authors.product_id
                INNER JOIN
            publishers ON products.publisher_id = publishers.id
            WHERE products.id= ?
            GROUP BY 
                products.id, 
                products.name,
                publishers.name,
                products.image, 
                products.price, 
                products.quantity,
                products.publish_year,
                products.detail";
            $result = DataSQL::querySQLAll($sql, [$idEdit, $idEdit, $idEdit]);
            $data = array();
            $informations = array();
            while($row = mysqli_fetch_array($result))
            {
                $informations[] = $row;
            }
            $data['informations'] = $informations;
            $sqlCategories = "SELECT * FROM categories";
            $resultCategories = DataSQL::querySQLAll($sqlCategories);
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
            $resultPublishers = DataSQL::querySQLAll($sqlPublishers);
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
        if(isset($_POST['id_delete']))
        {
            include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
            $idDelete = $_POST['id_delete'];
            $sql = "UPDATE products SET isActive = 0 WHERE id = ?";
            DataSQL::querySQLAll($sql, [$idDelete]);
        }
    }
    function HandleEditProduct()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
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
        $sqlCheck = "SELECT * FROM products WHERE (name = ? || image = ?) AND id != ?";
        $row = DataSQL::querySQLCount($sqlCheck, [$nameProduct, $imageProduct, $idProduct]);
        if($row == 0)
        {
            $sql = "UPDATE products SET name = ?, image = ?, price = ?, publisher_id = ?,
            quantity = ?, publish_year = ?, detail = ? WHERE id = ?";
            DataSQL::executeSQL($sql, [$nameProduct, $imageProduct, $priceProduct, $publisherProduct, $quantityProduct, $publishYear, 
            $detailProduct, $idProduct]);

            $sqlCheckCategory = "SELECT * FROM product_categories WHERE product_id = ?";
            // $resultCheckCategory = mysqli_query($connection, $sqlCheckCategory);
            $rowCheckCategory = DataSQL::querySQLCount($sqlCheckCategory, [$idProduct]);
            if($rowCheckCategory != 0)
            {
                $sqlCategory = "UPDATE product_categories SET category_id = ? WHERE product_id = ?";
            }
            else 
            {
                $sqlCategory= "INSERT INTO product_categories(category_id, product_id) VALUES(? , ?)";
            }
            DataSQL::executeSQL($sqlCategory, [$categoryProduct, $idProduct]);

            $sqlCheckAuthor = "SELECT * FROM product_authors WHERE product_id = ?";
            // $resultCheckAuthor = mysqli_query($connection, $sqlCheckAuthor);
            $rowCheckAuthor = DataSQL::executeSQL($sqlCheckAuthor, [$idProduct]);
            if($rowCheckAuthor != 0)
            {
                
                $sqlAuthor = "UPDATE product_authors SET author_id = ? WHERE product_id = ?";
            }
            else 
            {
                $sqlAuthor = "INSERT INTO product_authors(author_id, product_id) VALUES(?, ?)";
            }
            // mysqli_query($connection, $sqlAuthor);
            DataSQL::executeSQL($sqlAuthor, [$authorProduct, $idProduct]);
            echo json_encode(array("success" => "Chỉnh sửa sản phẩm $nameProduct thành công"));
        }
        else 
        {
            echo json_encode(array("fail" => "Sản phẩm $nameProduct đã tồn tại. Không thể chỉnh sửa sản phẩm này"));
        }
    }
    function DefaultDisplayProduct()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $isActive = 1;
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $pageSize = isset($_POST['pageSize']) ? $_POST['pageSize'] : 5;
        $startPage = ($page - 1) * $pageSize;
        $sql = "SELECT products.isActive, products.id, products.name as nameProduct, products.price, products.image, 
        products.quantity, products.publish_year
        FROM products
        WHERE products.isActive = ? ORDER BY id LIMIT ?, ?";
        $result = DataSQL::querySQLAll($sql, [$isActive, $startPage, $pageSize]);
        $informations = array();
        $data = new stdClass();
        while($row = mysqli_fetch_array($result))
        {
            $informations[] = $row;
        }
        $data->informations = $informations;
        $sql_count = "SELECT * FROM products WHERE isActive = ?";
        $row_count = DataSQL::querySQLCount($sql_count, [$isActive]);
        $data->number = $row_count;
        echo json_encode($data);
    }
    function SearchProduct()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $isActive = 1;
        $nameSearchAdvanced = "%" . $_POST['nameSearchAdvanced'] . "%";
        $selectSearch = $_POST['search_select'];
        $priceFrom = $_POST['priceFrom'];
        $priceTo = $_POST['priceTo']; 
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $pageSize = isset($_POST['pageSize']) ? $_POST['pageSize'] : 5;
        $startPage = ($page - 1) * $pageSize;
        $check = "";
        $result = "";
        if($selectSearch != 0 && !empty($priceFrom) && !empty($priceTo) && !empty($nameSearchAdvanced))
        {
        $sql = "SELECT products.*, products.name as nameProduct 
        FROM products INNER JOIN product_categories ON products.id = product_categories.product_id 
        INNER JOIN categories ON product_categories.category_id = categories.id 
        WHERE categories.id = ? AND products.name like ?
        AND price >= ? AND price <= ? AND isActive = ? LIMIT ?, ?";
            $check = 1;
            $result = DataSQL::querySQLAll($sql, [$selectSearch, $nameSearchAdvanced, $priceFrom, $priceTo, $isActive, $startPage, $pageSize]);
        }
        else if($selectSearch != 0 && (empty($priceFrom) || empty($priceTo) && !empty($nameSearchAdvanced)))
        {
            $sql = "SELECT products.*, products.name as nameProduct FROM products INNER JOIN product_categories ON products.id = product_categories.product_id INNER JOIN categories ON 
        product_categories.category_id = categories.id WHERE categories.id = ? AND products.name like ? AND isActive = ? LIMIT ?, ?";
            $check = 2;
            $result = DataSQL::querySQLAll($sql, [$selectSearch, $nameSearchAdvanced, $isActive, $startPage, $pageSize]);
        }
        else if($selectSearch != 0 && !empty($priceFrom) && !empty($priceTo) && empty($nameSearchAdvanced))
        {
            $sql = "SELECT products.*, products.name as nameProduct FROM products INNER JOIN product_categories ON products.id = product_categories.product_id INNER JOIN categories ON 
            product_categories.category_id = categories.id WHERE categories.id = ? AND price >= ? AND price <= ? AND isActive = ? LIMIT ?, ?";
            $check = 3;
            $result = DataSQL::querySQLAll($sql, [$selectSearch, $priceFrom, $priceTo, $isActive, $startPage, $pageSize]);
        }
        else if($selectSearch != 0 && (empty($priceFrom) || empty($priceTo)) && empty($nameSearchAdvanced))
        {
            $sql = "SELECT products.*, products.name as nameProduct FROM products INNER JOIN product_categories ON products.id = product_categories.product_id INNER JOIN categories ON 
            product_categories.category_id = categories.id WHERE categories.id = ? AND isActive = ? LIMIT ?, ?";
            $check = 4;
            $result = DataSQL::querySQLAll($sql, [$selectSearch, $isActive, $startPage, $pageSize]);
        }
        else if($selectSearch == 0 && (empty($priceFrom) || empty($priceTo)) && !empty($nameSearchAdvanced))
        {
            $sql = "SELECT products.*, products.name as nameProduct FROM products WHERE products.name like ?
            AND isActive = ? LIMIT ?, ?";
            $check = 5;
            $result = DataSQL::querySQLAll($sql, [$nameSearchAdvanced, $isActive, $startPage, $pageSize]);
        }
        else if($selectSearch == 0 && !empty($priceFrom) && !empty($priceTo) && empty($nameSearchAdvanced))
        {
            $sql = "SELECT products.*, products.name as nameProduct FROM products WHERE price >= ?
            AND price <= ? AND isActive = ? LIMIT ?, ?";
            $check = 6;
            $result = DataSQL::querySQLAll($sql, [$priceFrom, $priceTo, $isActive, $startPage, $pageSize]);
        }
        else if($selectSearch == 0 && (empty($priceFrom) || empty($priceTo)) && empty($nameSearchAdvanced))
        {
            $sql = "SELECT products.*, products.name as nameProduct FROM products WHERE isActive = ? LIMIT ?, ?";
            $check = 7;
            $result = DataSQL::querySQLAll($sql, [$isActive, $startPage, $pageSize]);
        }
        else if($selectSearch == 0 && !empty($priceFrom) && !empty($priceTo) && !empty($nameSearchAdvanced))
        {
            $sql = "SELECT products.*, products.name as nameProduct FROM products WHERE price >= ? AND price <= ? AND
            products.name like ? AND isActive = ? LIMIT ?, ?";
            $check = 8;
            $result = DataSQL::querySQLAll($sql, [$priceFrom, $priceTo, $nameSearchAdvanced, $isActive, $startPage, $pageSize]);
        }
        // $result = mysqli_query($connection, $sql);
        $informations = array();
        $data = new stdClass();
        while($row = mysqli_fetch_array($result))
        {
            $informations[] = $row;
        }
        $data->informations = $informations;

        $row_count = "";
        if($check == 1)
        {
            $sql_count = "SELECT products.*, products.name as nameProduct FROM products INNER JOIN product_categories ON products.id = product_categories.product_id INNER JOIN categories ON 
            product_categories.category_id = categories.id WHERE categories.id = ? AND products.name like ?
            AND price >= ? AND price <= ? AND isActive = ?";
            $row_count = DataSQL::querySQLCount($sql_count, [$selectSearch, $nameSearchAdvanced, $priceFrom, $priceTo, $isActive]);
        }
        else if($check == 2)
        {
            $sql_count = "SELECT products.*, products.name as nameProduct FROM products INNER JOIN product_categories ON products.id = product_categories.product_id INNER JOIN categories ON 
            product_categories.category_id = categories.id WHERE categories.id = ? AND products.name like ? AND isActive = ?";
            $row_count = DataSQL::querySQLCount($sql_count, [$selectSearch, $nameSearchAdvanced, $isActive]);

        }
        else if($check == 3)
        {
            $sql_count = "SELECT products.*, products.name as nameProduct FROM products INNER JOIN product_categories ON products.id = product_categories.product_id INNER JOIN categories ON 
            product_categories.category_id = categories.id WHERE categories.id = ? AND price >= ? AND price <= ? AND isActive = ?";
            $row_count = DataSQL::querySQLCount($sql_count, [$selectSearch, $priceFrom, $priceTo, $isActive]);

        }
        else if($check == 4)
        {
            $sql_count = "SELECT products.*, products.name as nameProduct FROM products INNER JOIN product_categories ON products.id = product_categories.product_id INNER JOIN categories ON 
            product_categories.category_id = categories.id WHERE categories.id = ? AND isActive = ?";
            $row_count = DataSQL::querySQLCount($sql_count, [$selectSearch, $isActive]);
        }
        else if($check == 5)
        {
            $sql_count = "SELECT products.*, products.name as nameProduct FROM products WHERE products.name like ? AND isActive = ?";
            $row_count = DataSQL::querySQLCount($sql_count, [$nameSearchAdvanced, $isActive]);
        } 
        else if($check == 6)
        {
            $sql_count = "SELECT products.*, products.name as nameProduct FROM products WHERE price >= ? AND price <= ? AND isActive = ?";
            $row_count = DataSQL::querySQLCount($sql_count, [$priceFrom, $priceTo, $isActive]);

        }
        else if($check == 7)
        {
            $sql_count = "SELECT products.*, products.name as nameProduct FROM products WHERE isActive = ?";
            $row_count = DataSQL::querySQLCount($sql_count, [$isActive]);
        }
        else if($check == 8)
        {
            $sql_count = "SELECT products.*, products.name as nameProduct FROM products WHERE price >= ? AND price <= ? AND
            products.name like ? AND isActive = ?";
            $row_count = DataSQL::querySQLCount($sql_count, [$priceFrom, $priceTo, $nameSearchAdvanced, $isActive]);

        }
        // $result_count = mysqli_query($connection, $sql_count);
        // $row_count = mysqli_num_rows($result_count);
        $data->number = $row_count;
        echo json_encode($data);
    }
    function SearchIdProduct()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $isActive = 1;
        $idProduct = $_POST['inputSearchName'];
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $pageSize = isset($_POST['pageSize']) ? $_POST['pageSize'] : 5;
        $startPage = ($page - 1) * $pageSize;
        $sql = "SELECT products.isActive, products.id, products.name as nameProduct, products.price, products.image,
        products.quantity, products.publish_year
            FROM products WHERE products.isActive = ?
            AND id = ? LIMIT ?, ?";
        $result = DataSQL::querySQLAll($sql, [$isActive, $idProduct, $startPage, $pageSize]);
        $informations = array();
        $data = new stdClass();
        while($row = mysqli_fetch_array($result))
        {
            $informations[] = $row;
        }
        $data->informations = $informations;
        $sql_count = "SELECT * FROM products WHERE isActive = ? AND id = ?";
        $row_count = DataSQL::querySQLCount($sql_count, [$isActive, $idProduct]);
        $data->number = $row_count;
        echo json_encode($data);
    }
?>