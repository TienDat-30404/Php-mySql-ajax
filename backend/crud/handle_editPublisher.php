<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/frontend/includes/config.php";
    $idPublisher = $_POST['id_publisher'];
    $namePublisher = $_POST['name_publisher'];
    $sqlCheck = "SELECT * FROM publishers WHERE (name = '$namePublisher') AND id != '$idPublisher'";
    $resultCheck = mysqli_query($connection, $sqlCheck);
    $row = mysqli_num_rows($resultCheck);
    if($row == 0)
    {
        $sql = "UPDATE publishers SET name = '$namePublisher' WHERE id = '$idPublisher'";
        mysqli_query($connection, $sql);
        echo json_encode(array("success" => "Chỉnh sửa nhà cung cấp $namePublisher thành công"));
    }
    else 
    {
        echo json_encode(array("fail" => "Nhà cung cấp $namePublisher đã tồn tại. Không thể chỉnh sửa nhà cung cấp này"));
    }
?>
