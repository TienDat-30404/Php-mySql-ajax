<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/frontend/includes/config.php";
    $idSearch = $_GET['id_search'];
    $nameSearch = $_GET['name_search'];
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : 5;
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
?>