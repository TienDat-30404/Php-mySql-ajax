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
            $sql = "SELECT * FROM authors WHERE id = ?";
            $result = DataSQL::querySQLAll($sql, [$idEdit]);
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
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $idAuthor = $_POST['id_author'];
        $nameAuthor = $_POST['name_author'];
        $sqlCheck = "SELECT * FROM authors WHERE (name = ?) AND id != ?";
        $row = DataSQL::querySQLCount($sqlCheck, [$nameAuthor, $idAuthor]);
        if($row == 0)
        {
            $sql = "UPDATE authors SET name = ? WHERE id = ?";
            DataSQL::executeSQL($sql, [$nameAuthor, $idAuthor]);
            echo json_encode(array("success" => "Chỉnh sửa tác giả $nameAuthor thành công"));
        }
        else 
        {
            echo json_encode(array("fail" => "Tác giả $nameAuthor đã tồn tại. Không thể chỉnh sửa tác giả này"));
        }
    }
    function AddAuthor()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
        $nameAuthor = $_POST['name_author'];
        $sql_check = "SELECT * FROM authors WHERE name = ?";
        $row_check = DataSQL::querySQLCount($sql_check, [$nameAuthor]);
        if($row_check == 0)
        {
            $sql = "INSERT INTO authors(name) VALUES (?)";
            DataSQL::executeSQL($sql, [$nameAuthor]);
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
            $sqlProductCategory = "DELETE FROM product_authors WHERE author_id = ?";
            DataSQL::executeSQL($sqlProductCategory, [$idDelete]);

            $sql = "DELETE FROM authors WHERE id = ?";
            DataSQL::executeSQL($sql, [$idDelete]);
        }
    }
    function SearchAuthor()
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
            $sql = "SELECT * FROM authors WHERE name like ? LIMIT ?, ?";
            $check = 1;
            $result = DataSQL::querySQLAll($sql, [$nameSearch, $startPage, $pageSize]);
        }
        else if($idSearch != 0)
        {
            $sql = "SELECT * FROM authors WHERE id = ? LIMIT ?, ?";
            $check = 2;
            $result = DataSQL::querySQLAll($sql, [$idSearch, $startPage, $pageSize]);
        }
        else if($idSearch == 0 && $nameSearch == "")
        {
            $sql = "SELECT * FROM authors LIMIT ?, ?";
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
            $sql_count = "SELECT * FROM authors WHERE name like ?";
            $row_count = DataSQL::querySQLCount($sql_count, [$nameSearch]);
        }
        else if($check == 2)
        {
            $sql_count = "SELECT * FROM authors WHERE id = ?";
            $row_count = DataSQL::querySQLCount($sql_count, [$idSearch]);
        }
        else if($check == 3)
        {
            $sql_count = "SELECT * FROM authors";
            $row_count = Data::querySQL($sql_count);
        }
        $data->number = $row_count;
        echo json_encode($data);
    }
?>