<?php 
    include "../../frontend/includes/config.php";
        $nameAuthor = $_POST['name_author'];
        $sql_check = "SELECT * FROM authors WHERE name = '$nameAuthor'";
        $result = mysqli_query($connection, $sql_check);
        $row_check = mysqli_num_rows($result);
        if($row_check == 0)
        {
            $sql = "INSERT INTO authors(name) VALUES ('$nameAuthor')";
            mysqli_query($connection, $sql);
            echo json_encode(array("status" => "Thêm tác giả $nameAuthor vào cửa hàng thành công"));
        }
        else 
        {
            echo json_encode(array("status" => "Tác giả $nameAuthor đã tồn tại trong cửa hàng"));
        }
?>
