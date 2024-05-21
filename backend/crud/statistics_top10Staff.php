<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    $sql = "SELECT users.id as idStaff, users.fullname as nameStaff, SUM(bills.total_price) as totalPrice
    FROM bills 
    JOIN users ON bills.staff_id = users.id 
    GROUP BY idStaff ORDER BY totalPrice DESC";
    $result = DataSQL::querySQL($sql);
    $data = array();
    while($row = mysqli_fetch_array($result))
    {
        $data[$row['nameStaff']] = $row['totalPrice'];
    }
    echo json_encode($data);
    
?>