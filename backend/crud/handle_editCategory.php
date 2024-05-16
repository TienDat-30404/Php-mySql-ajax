<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/frontend/includes/config.php";
    $idCategory = $_POST['id_category'];
    $nameCategory = $_POST['name_category'];
    $imageCategory = $_POST['image_category'];
    $sqlCheck = "SELECT * FROM categories WHERE (name = '$nameCategory' || image = '$imageCategory') AND id != '$idCategory'";
    $resultCheck = mysqli_query($connection, $sqlCheck);
    $row = mysqli_num_rows($resultCheck);
    if($row == 0)
    {
        $sql = "UPDATE categories SET name = '$nameCategory', image = '$imageCategory' WHERE id = '$idCategory'";
        mysqli_query($connection, $sql);
        echo json_encode(array("success" => "Chỉnh sửa thể loại $nameCategory thành công"));
    }
    else 
    {
        echo json_encode(array("fail" => "Thể loại $nameCategory đã tồn tại. Không thể chỉnh sửa thể loại này"));
    }
?>
