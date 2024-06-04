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
        case 'get_all_category':
            GetAllCategory();
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
            $sql = "SELECT * FROM categories WHERE id = '$idEdit'";
            $result = DataSQL::querySQL($sql);
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
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/frontend/includes/config.php";
        $idCategory = $_POST['id_category'];
        $nameCategory = $_POST['name_category'];
        $imageCategory = $_POST['image_category'];
        $sqlCheck = "SELECT * FROM categories WHERE (name = '$nameCategory' || image = '$imageCategory') AND id != '$idCategory'";
        $resultCheck = mysqli_query($connection, $sqlCheck);
        $row = mysqli_num_rows($resultCheck);
        if($row == 0)
        {
            $sql = "UPDATE categories SET name = '$nameCategory', image = '$imageCategory' WHERE id = '$idCategory'";
            mysqli_query($connection, $sql);
            echo json_encode(array("success" => "Chỉnh sửa thể loại $nameCategory thành công"));
        }
        else 
        {
            echo json_encode(array("fail" => "Thể loại $nameCategory đã tồn tại. Không thể chỉnh sửa thể loại này"));
        }
    }
    function AddCategory()
    {
        include "../../frontend/includes/config.php";
        $nameCategory = $_POST['name_category'];
        $imageCategory = $_POST['image_category'];
        $sql_check = "SELECT * FROM categories WHERE name = '$nameCategory' OR image = '$imageCategory'";
        $result = mysqli_query($connection, $sql_check);
        $row_check = mysqli_num_rows($result);
        if($row_check == 0)
        {
            $sql = "INSERT INTO categories(name, image) VALUES ('$nameCategory', '$imageCategory')";
            mysqli_query($connection, $sql);
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
            $sqlProductCategory = "DELETE FROM product_categories WHERE category_id = '$idDelete'";
            DataSQL::querySQL($sqlProductCategory);

            $sql = "DELETE FROM categories WHERE id = '$idDelete'";
            DataSQL::querySQL($sql);
        }
    }
    function GetAllCategory()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $sql = "SELECT * FROM categories";
        $result = DataSQL::querySQL($sql);
        $data = array();
        while($row = mysqli_fetch_array($result))
        {
            $data[] = $row;
        }
        echo json_encode($data);
    }
    function SearchCategory()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/frontend/includes/config.php";
        $idSearch = $_POST['id_search'];
        $nameSearch = $_POST['name_search'];
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $pageSize = isset($_POST['pageSize']) ? $_POST['pageSize'] : 5;
        $startPage = ($page - 1) * $pageSize;
        $check = "";
        $sql = "";
        if($idSearch == 0 && $nameSearch != "")
        {
            $sql = "SELECT * FROM categories WHERE name like '%" . $nameSearch . "%' LIMIT $startPage, $pageSize";
            $check = 1;
        }
        else if($idSearch != 0 && $nameSearch == "")
        {
            $sql = "SELECT * FROM categories WHERE id = '$idSearch' LIMIT $startPage, $pageSize";
            $check = 2;
        }
        else if($idSearch == 0 && $nameSearch == "")
        {
            $sql = "SELECT * FROM categories LIMIT $startPage, $pageSize";
            $check = 3;
        }
        $result = mysqli_query($connection, $sql);
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
            $sql_count = "SELECT * FROM categories WHERE name like '%" . $nameSearch . "%'";
        }
        else if($check == 2)
        {
            $sql_count = "SELECT * FROM categories WHERE id = '$idSearch'";
        }
        else if($check == 3)
        {
            $sql_count = "SELECT * FROM categories";
        }
        $result_count = mysqli_query($connection, $sql_count);
        $row_count = mysqli_num_rows($result_count);
        $data->number = $row_count;
        echo json_encode($data);
    }
?>