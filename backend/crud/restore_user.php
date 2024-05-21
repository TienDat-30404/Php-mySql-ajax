<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    $idUser = $_POST['id_user'];
    $sqlUser = "UPDATE users SET active = 1 WHERE id = '$idUser'";
    DataSQL::querySQL($sqlUser);
    ?>
    
?>