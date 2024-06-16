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
            $sql = "SELECT * FROM publishers WHERE id = '$idEdit'";
            $result = DataSQL::querySQL($sql);
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
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/frontend/includes/config.php";
        $idPublisher = $_POST['id_publisher'];
        $namePublisher = $_POST['name_publisher'];
        $sqlCheck = "SELECT * FROM publishers WHERE (name = '$namePublisher') AND id != '$idPublisher'";
        $resultCheck = mysqli_query($connection, $sqlCheck);
        $row = mysqli_num_rows($resultCheck);
        if($row == 0)   
        {
            $sql = "UPDATE publishers SET name = '$namePublisher' WHERE id = '$idPublisher'";
            mysqli_query($connection, $sql);
            echo json_encode(array("success" => "Chỉnh sửa nhà xuất bản $namePublisher thành công"));
        }
        else 
        {
            echo json_encode(array("fail" => "Nhà xuất bản $namePublisher đã tồn tại. Không thể chỉnh sửa nhà xuất bản này"));
        }
    }
    function AddPublisher()
    {
        include "../../frontend/includes/config.php";
        $namePublisher = $_POST['name_publisher'];
        $sql_check = "SELECT * FROM publishers WHERE name = '$namePublisher'";
        $result = mysqli_query($connection, $sql_check);
        $row_check = mysqli_num_rows($result);
        if($row_check == 0)
        {
            $sql = "INSERT INTO publishers(name) VALUES ('$namePublisher')";
            mysqli_query($connection, $sql);
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
            $idDelete = $_POST['id_delete'];
            $sqlPublisherProduct = "UPDATE products SET publisher_id = 1 WHERE publisher_id = '$idDelete'";
            DataSQL::querySQL($sqlPublisherProduct);
            $sql = "DELETE FROM publishers WHERE id = '$idDelete'";
            DataSQL::querySQL($sql);
        }
    }
    function SearchPublisher()
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
            $sql = "SELECT * FROM publishers WHERE name like '%" . $nameSearch . "%' LIMIT $startPage, $pageSize ";
            $check = 1;
        }
        else if($idSearch != 0 && $nameSearch == "")
        {
            $sql = "SELECT * FROM publishers WHERE id = '$idSearch' LIMIT $startPage, $pageSize ";
            $check = 2;
        }
        else if($idSearch == 0 && $nameSearch == "")
        {
            $sql = "SELECT * FROM publishers LIMIT $startPage, $pageSize ";
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
            $sql_count = "SELECT * FROM publishers WHERE name like '%" . $nameSearch . "%' ";
        }
        else if($check == 2)
        {
            $sql_count = "SELECT * FROM publishers WHERE id = '$idSearch' ";
        }
        else if($check == 3)
        {
            $sql_count = "SELECT * FROM publishers ";
        }
        $result_count = mysqli_query($connection, $sql_count);
        $row_count = mysqli_num_rows($result_count);
        $data->number = $row_count;
        echo json_encode($data);
    }
?>