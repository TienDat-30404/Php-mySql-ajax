<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
    $sql = "SELECT  months.month AS month, 
    COALESCE(b.revenue, 0) AS revenue, 
    COALESCE(e.total_capital, 0) AS total_capital, 
    (COALESCE(b.revenue, 0) - COALESCE(e.total_capital, 0)) AS profit
    FROM (SELECT 1 AS month UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12) months
    LEFT JOIN 
    (SELECT 
         DATE_FORMAT(date_create, '%m') AS month, 
         SUM(total_price) AS revenue 
     FROM 
         bills 
     WHERE 
         bills.bill_status_id = 2
     GROUP BY 
         DATE_FORMAT(date_create, '%m')
    ) b
    ON months.month = b.month
    LEFT JOIN 
    (SELECT 
         DATE_FORMAT(date_entry, '%m') AS month, 
         SUM(total_price) AS total_capital 
     FROM 
         entry_slips 
     GROUP BY 
         DATE_FORMAT(date_entry, '%m')
    ) e 
    ON 
    months.month = e.month
    ORDER BY 
    month";
    $result = DataSQL::querySQL($sql);
    $data = array();
    while($row = mysqli_fetch_array($result))
    {
        $data[$row['month']] = array(
            "revenue" => $row['revenue'],
            "totalCapital" => $row['total_capital'],
            "profit" => $row['profit']
        );
    }
    echo json_encode($data);
?>