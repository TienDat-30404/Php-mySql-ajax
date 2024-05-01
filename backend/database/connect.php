<?php
class DataSQL{
    public static function querySQl($sql){
        include(__DIR__ . '/../../frontend/includes/config.php');
        $result = mysqli_query($connection, $sql);

        if(!$result){
            echo "Không thể truy vấn: " . mysqli_error($connection);
        }
        mysqli_close($connection);

        return $result;
    }
}
?>
