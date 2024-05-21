<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    $sql = "SELECT MONTH(date_create) as month, SUM(total_price) as totalPrice FROM bills GROUP BY MONTH(date_create)";
    $result = DataSQL::querySQL($sql);
    $data = array_fill(1, 12, 0);
    while($row = mysqli_fetch_array($result))
    {   
        $month = $row['month'];
        $totalPrice = $row['totalPrice'];
        $data[$month] = $totalPrice;
    }
    
    echo json_encode($data);
?>