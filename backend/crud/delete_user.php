<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/project_web2-Copy-2/backend/database/connect.php";
    if(isset($_GET['id_delete']))
    {
        $idDelete = $_GET['id_delete'];  

        $sql = "UPDATE users SET active = 0 WHERE id = '$idDelete'";
        DataSQL::querySQl($sql);
        header('location: ../index.php?title=accountExist');
    }
?>