<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    if(isset($_GET['id_edit']))
    {
        $idEdit = $_GET['id_edit'];
        $sql = "SELECT * FROM publishers WHERE id = '$idEdit'";
        $result = DataSQL::querySQL($sql);
        $data = array();
        while($row = mysqli_fetch_array($result))
        {
            $data[] = $row;
        }
        echo json_encode($data);
    }
?>