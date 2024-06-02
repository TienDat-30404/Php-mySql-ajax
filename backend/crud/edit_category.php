<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    if(isset($_GET['id_edit']))
    {
        $idEdit = $_GET['id_edit'];
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
?>