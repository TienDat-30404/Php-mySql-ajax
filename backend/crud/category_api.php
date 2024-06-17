<?php 
    $choice = isset($_POST['choice']) ? $_POST['choice'] : "";
    switch($choice) 
    {
        case 'display_edit_category':
            DisplayEditCategory();
            break;
        case 'handle_edit_category':
            HandleEditCategory();
            break;
        case 'add_category':
            AddCategory();
            break;
        case 'delete_category':
            DeleteCategory();
            break;
        case 'search_category':
            SearchCategory();
            break;
    }
    function DisplayEditCategory()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        if(isset($_POST['id_edit']))
        {
            $idEdit = $_POST['id_edit'];
            $sql = "SELECT * FROM categories WHERE id = ?";
            $result = DataSQL::querySQLAll($sql, [$idEdit]);
            $data = new stdClass();
            $informations = array();
            while($row = mysqli_fetch_array($result))
            {
                $informations[] = $row;
            }
            $data->informations = $informations;
            echo json_encode($data);
        }
    }
    function HandleEditCategory()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $idCategory = $_POST['id_category'];
        $nameCategory = $_POST['name_category'];
        $imageCategory = $_POST['image_category'];
        $sqlCheck = "SELECT * FROM categories WHERE (name = ? || image = ?) AND id != ?";
        $row = DataSQL::querySQLCount($sqlCheck, [$nameCategory, $imageCategory, $idCategory]);
        if($row == 0)
        {
            $sql = "UPDATE categories SET name = ?, image = ? WHERE id = ?";
            DataSQL::executeSQL($sql, [$nameCategory, $imageCategory, $idCategory]);
            echo json_encode(array("success" => "Chỉnh sửa thể loại $nameCategory thành công"));
        }
        else 
        {
            echo json_encode(array("fail" => "Thể loại $nameCategory đã tồn tại. Không thể chỉnh sửa thể loại này"));
        }
    }
    function AddCategory()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $nameCategory = $_POST['name_category'];
        $imageCategory = $_POST['image_category'];
        $sql_check = "SELECT * FROM categories WHERE name = ? OR image = ?";
        $row_check = DataSQL::querySQLCount($sql_check, [$nameCategory, $imageCategory]);
        if($row_check == 0)
        {
            $sql = "INSERT INTO categories(name, image) VALUES (?, ?)";
            DataSQL::executeSQL($sql, [$nameCategory, $imageCategory]);
            echo json_encode(array("status" => "Thêm thể loại $nameCategory vào cửa hàng thành công"));
        }
        else 
        {
            echo json_encode(array("status" => "Thể loại $nameCategory đã tồn tại trong cửa hàng"));
        }
    }
    function DeleteCategory()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        if(isset($_POST['id_delete']))
        {
            $idDelete = $_POST['id_delete'];
            $sqlProductCategory = "DELETE FROM product_categories WHERE category_id = ?";
            DataSQL::executeSQL($sqlProductCategory, [$idDelete]);

            $sql = "DELETE FROM categories WHERE id = ?";
            DataSQL::executeSQL($sql, [$idDelete]);
        }
    }
    function SearchCategory()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $idSearch = $_POST['id_search'];
        $nameSearch = "%" . $_POST['name_search'] . "%";
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $pageSize = isset($_POST['pageSize']) ? $_POST['pageSize'] : 5;
        $startPage = ($page - 1) * $pageSize;
        $check = "";
        $sql = "";
        $result = "";
        if($idSearch == 0 && $nameSearch != "")
        {
            $sql = "SELECT * FROM categories WHERE name like ? LIMIT ?, ?";
            $check = 1;
            $result = DataSQL::querySQLAll($sql, [$nameSearch, $startPage, $pageSize]);
        }
        else if($idSearch == 0 || $nameSearch != "")
        {
            $sql = "SELECT * FROM categories WHERE id = ?";
            $check = 2;
            $result = DataSQL::querySQLAll($sql, [$idSearch]);
        }
        else if($idSearch == 0 && $nameSearch == "")
        {
            $sql = "SELECT * FROM categories LIMIT ?, ?";
            $check = 3;
            $result = DataSQL::querySQLAll($sql, [$startPage, $pageSize]);
        }
        $informations = array();
        $data = new stdClass();
        while($row = mysqli_fetch_array($result))
        {
            $informations[] = $row;
        }
        $data->informations = $informations;
        $sql_count = "";
        if($check == 1)
        {
            $sql_count = "SELECT * FROM categories WHERE name like ?";
            $row_count = DataSQL::querySQLCount($sql_count, [$nameSearch]);
        }
        else if($check == 2)
        {
            $sql_count = "SELECT * FROM categories WHERE id = ?";
            $row_count = DataSQL::querySQLCount($sql_count, [$idSearch]);
        }
        else if($check == 3)
        {
            $sql_count = "SELECT * FROM categories";
            $row_count = DataSQL::querySQL($sql_count);
        }
        $data->number = $row_count;
        echo json_encode($data);
    }
?>