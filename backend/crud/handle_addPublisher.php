<?php 
    include "../../frontend/includes/config.php";
        $namePublisher = $_POST['name_publisher'];
        $sql_check = "SELECT * FROM publishers WHERE name = '$namePublisher'";
        $result = mysqli_query($connection, $sql_check);
        $row_check = mysqli_num_rows($result);
        if($row_check == 0)
        {
            $sql = "INSERT INTO publishers(name) VALUES ('$namePublisher')";
            mysqli_query($connection, $sql);
            echo json_encode(array("status" => "Thêm nhà cung cấp $namePublisher vào cửa hàng thành công"));
        }
        else 
        {
            echo json_encode(array("status" => "Nhà cung cấp $namePublisher đã tồn tại trong cửa hàng"));
        }
?>
