<?php 
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
?>
