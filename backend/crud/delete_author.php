<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    if(isset($_GET['id_delete']))
    {
        $idDelete = $_GET['id_delete'];
        // $sqlProduct = "SELECT * FROM products JOIN product_categories ON products.id = product_categories.product_id WHERE category_id = '$idDelete'";
        // $resultProduct = DataSQL::querySQL($sqlProduct);
        // while($rowProduct = mysqli_fetch_array($resultProduct))
        // {
        //     $idProduct = $rowProduct['id'];
        //     $updateProduct = "UPDATE products SET isActive = 0 WHERE id = '$idProduct'";
        //     DataSQL::querySQL($updateProduct);
        // }
        $sqlProductCategory = "DELETE FROM product_authors WHERE author_id = '$idDelete'";
        DataSQL::querySQL($sqlProductCategory);

        $sql = "DELETE FROM authors WHERE id = '$idDelete'";
        DataSQL::querySQL($sql);
    }
?>