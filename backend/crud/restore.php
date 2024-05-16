<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    $idRestore = $_POST['id_restore'];
    $sqlRestore = "UPDATE products SET isActive = 1 WHERE id = '$idRestore'";
    DataSQL::querySQL($sqlRestore);
    ?>
    
?>