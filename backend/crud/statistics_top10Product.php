<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    $sql = "SELECT products.id , products.name as nameProduct, SUM(bill_details.quantity * bill_details.price) as totalPrice 
    FROM bills 
    JOIN bill_details ON bills.id = bill_details.bill_id 
    JOIN products ON bill_details.product_id = products.id 
    WHERE bills.bill_status_id = 2 GROUP BY products.id, products.name ORDER BY totalPrice DESC LIMIT 10";
    $result = DataSQL::querySQL($sql);
    $data = array();
    while($row = mysqli_fetch_array($result))
    {
        $data[$row['nameProduct']] = $row['totalPrice'];
    }
    echo json_encode($data);
?>