<?php 
    include "../../frontend/includes/config.php";
        $nameCategory = $_POST['name_category'];
        $imageCategory = $_POST['image_category'];
        $sql_check = "SELECT * FROM categories WHERE name = '$nameCategory' OR image = '$imageCategory'";
        $result = mysqli_query($connection, $sql_check);
        $row_check = mysqli_num_rows($result);
        if($row_check == 0)
        {
            $sql = "INSERT INTO categories(name, image) VALUES ('$nameCategory', '$imageCategory')";
            mysqli_query($connection, $sql);
            echo json_encode(array("status" => "Thêm thể loại $nameCategory vào cửa hàng thành công"));
        }
        else 
        {
            echo json_encode(array("status" => "Thể loại $nameCategory đã tồn tại trong cửa hàng"));
        }
?>
