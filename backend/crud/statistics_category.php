<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    $sql = "SELECT categories.id , categories.name as nameCategory, SUM(bill_details.quantity * bill_details.price) as totalPriceCategory 
    FROM bills JOIN bill_details ON bills.id = bill_details.bill_id 
    JOIN products ON bill_details.product_id = products.id 
    JOIN product_categories ON products.id = product_categories.product_id 
    JOIN categories ON product_categories.category_id = categories.id 
    WHERE bills.bill_status_id = 2 GROUP BY categories.id";
    $result = DataSQL::querySQL($sql);
    $data = array();
    while($row = mysqli_fetch_array($result))
    {
        $data[$row['nameCategory']] = $row['totalPriceCategory'];    
    }
    echo json_encode($data);
?>