<?php 
    $choice = isset($_POST['choice']) ? $_POST['choice'] : "";
    switch($choice)
    {
        case 'display_edit_author':
            DisplayEditAuthor();
            break;
        case 'handle_edit_author':
            HandleEditAuthor();
            break;
        case 'add_author':
            AddAuthor();
            break;
        case 'delete_author':
            DeleteAuthor();
            break;
        case 'get_all_author':
            GetAllAuthor();
            break;
        case 'search_author':
            SearchAuthor();
            break;
    }
    function DisplayEditAuthor()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        if(isset($_POST['id_edit']))
        {
            $idEdit = $_POST['id_edit'];
            $sql = "SELECT * FROM authors WHERE id = '$idEdit'";
            $result = DataSQL::querySQL($sql);
            $data = array();
            while($row = mysqli_fetch_array($result))
            {
                $data[] = $row;
            }
            echo json_encode($data);
        }
    }
    function HandleEditAuthor()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/frontend/includes/config.php";
        $idAuthor = $_POST['id_author'];
        $nameAuthor = $_POST['name_author'];
        $sqlCheck = "SELECT * FROM authors WHERE (name = '$nameAuthor') AND id != '$idAuthor'";
        $resultCheck = mysqli_query($connection, $sqlCheck);
        $row = mysqli_num_rows($resultCheck);
        if($row == 0)
        {
            $sql = "UPDATE authors SET name = '$nameAuthor' WHERE id = '$idAuthor'";
            mysqli_query($connection, $sql);
            echo json_encode(array("success" => "Chỉnh sửa tác giả $nameAuthor thành công"));
        }
        else 
        {
            echo json_encode(array("fail" => "Tác giả $nameAuthor đã tồn tại. Không thể chỉnh sửa tác giả này"));
        }
    }
    function AddAuthor()
    {
        include "../../frontend/includes/config.php";
        $nameAuthor = $_POST['name_author'];
        $sql_check = "SELECT * FROM authors WHERE name = '$nameAuthor'";
        $result = mysqli_query($connection, $sql_check);
        $row_check = mysqli_num_rows($result);
        if($row_check == 0)
        {
            $sql = "INSERT INTO authors(name) VALUES ('$nameAuthor')";
            mysqli_query($connection, $sql);
            echo json_encode(array("status" => "Thêm tác giả $nameAuthor vào cửa hàng thành công"));
        }
        else 
        {
            echo json_encode(array("status" => "Tác giả $nameAuthor đã tồn tại trong cửa hàng"));
        }
    }
    function DeleteAuthor()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        if(isset($_POST['id_delete']))
        {
            $idDelete = $_POST['id_delete'];
            $sqlProductCategory = "DELETE FROM product_authors WHERE author_id = '$idDelete'";
            DataSQL::querySQL($sqlProductCategory);

            $sql = "DELETE FROM authors WHERE id = '$idDelete'";
            DataSQL::querySQL($sql);
        }
    }
    function GetAllAuthor()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $sql = "SELECT * FROM authors";
        $result = DataSQL::querySQL($sql);
        $data = array();
        while($row = mysqli_fetch_array($result))
        {
            $data[] = $row;
        }
        echo json_encode($data);
    }
    function SearchAuthor()
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
            $sql = "SELECT * FROM authors WHERE name like '%" . $nameSearch . "%' LIMIT $startPage, $pageSize";
            $check = 1;
        }
        else if($idSearch != 0 && $nameSearch == "")
        {
            $sql = "SELECT * FROM authors WHERE id = '$idSearch' LIMIT $startPage, $pageSize";
            $check = 2;
        }
        else if($idSearch == 0 && $nameSearch == "")
        {
            $sql = "SELECT * FROM authors LIMIT $startPage, $pageSize";
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
            $sql_count = "SELECT * FROM authors WHERE name like '%" . $nameSearch . "%'";
        }
        else if($check == 2)
        {
            $sql_count = "SELECT * FROM authors WHERE id = '$idSearch'";
        }
        else if($check == 3)
        {
            $sql_count = "SELECT * FROM authors";
        }
        $result_count = mysqli_query($connection, $sql_count);
        $row_count = mysqli_num_rows($result_count);
        $data->number = $row_count;
        echo json_encode($data);
    }
?>