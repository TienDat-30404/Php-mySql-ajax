<?php 
    $choice = isset($_POST['choice']) ? $_POST['choice'] : "";
    switch($choice)
    {
        case 'display_edit_publisher':
            DisplayEditPublisher();
            break;
        case 'handle_edit_publisher':
            HandleEditPublisher();
            break;
        case 'add_publisher':
            AddPublisher();
            break;
        case 'delete_publisher':
            DeletePublisher();
            break;
        case 'search_publisher':
            SearchPublisher();
            break;
    }
    function DisplayEditPublisher()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        if(isset($_POST['id_edit']))
        {
            $idEdit = $_POST['id_edit'];
            $sql = "SELECT * FROM publishers WHERE id = ?";
            $result = DataSQL::querySQLAll($sql, [$idEdit]);
            $data = array();
            while($row = mysqli_fetch_array($result))
            {
                $data[] = $row;
            }
            echo json_encode($data);
        }
    }
    function HandleEditPublisher()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $idPublisher = $_POST['id_publisher'];
        $namePublisher = $_POST['name_publisher'];
        $sqlCheck = "SELECT * FROM publishers WHERE (name = ?) AND id != ?";
        $row = DataSQL::querySQLCount($sqlCheck, [$namePublisher, $idPublisher]);
        if($row == 0)   
        {
            $sql = "UPDATE publishers SET name = ? WHERE id = ?";
            DataSQL::executeSQL($sql, [$namePublisher, $idPublisher]);
            echo json_encode(array("success" => "Chỉnh sửa nhà xuất bản $namePublisher thành công"));
        }
        else 
        {
            echo json_encode(array("fail" => "Nhà xuất bản $namePublisher đã tồn tại. Không thể chỉnh sửa nhà xuất bản này"));
        }
    }
    function AddPublisher()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $namePublisher = $_POST['name_publisher'];
        $sql_check = "SELECT * FROM publishers WHERE name = ?";
        $row_check = DataSQL::querySQLCount($sql_check, [$namePublisher]);
        if($row_check == 0) 
        {
            $sql = "INSERT INTO publishers(name) VALUES (?)";
            DataSQL::executeSQL($sql, [$namePublisher]);
            echo json_encode(array("status" => "Thêm nhà xuất bản $namePublisher vào cửa hàng thành công"));
        }
        else 
        {
            echo json_encode(array("status" => "Nhà xuất bản $namePublisher đã tồn tại trong cửa hàng"));
        }
    }
    function DeletePublisher()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        if(isset($_POST['id_delete']))
        {
            $idPublishProduct = 1;
            $idDelete = $_POST['id_delete'];
            $sqlPublisherProduct = "UPDATE products SET publisher_id = ? WHERE publisher_id = ?";
            DataSQL::executeSQL($sqlPublisherProduct, [$idPublishProduct, $idDelete]);
            $sql = "DELETE FROM publishers WHERE id = ?";
            DataSQL::executeSQL($sql, [$idDelete]);
        }
    }
    function SearchPublisher()
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
            $sql = "SELECT * FROM publishers WHERE name like ? LIMIT ?, ?";
            $check = 1;
            $result = DataSQL::querySQLAll($sql, [$nameSearch, $startPage, $pageSize]);
        }
        else if($idSearch != 0)
        {
            $sql = "SELECT * FROM publishers WHERE id = ? LIMIT ?, ? ";
            $check = 2;
            $result = DataSQL::querySQLAll($sql, [$idSearch, $startPage, $pageSize]);
        }
        else if($idSearch == 0 && $nameSearch == "")
        {
            $sql = "SELECT * FROM publishers LIMIT ?, ? ";
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
        $row_count = "";
        if($check == 1)
        {
            $sql_count = "SELECT * FROM publishers WHERE name like ? ";
            $row_count = DataSQL::querySQLCount($sql_count, [$nameSearch]);
        }
        else if($check == 2)
        {
            $sql_count = "SELECT * FROM publishers WHERE id = ?";
            $row_count = DataSQL::querySQLCount($sql_count, [$idSearch]);
        }
        else if($check == 3)
        {
            $sql_count = "SELECT * FROM publishers ";
            $row_count = DataSQL::querySQL($sql_count);
        }
        $data->number = $row_count;
        echo json_encode($data);
    }
?>