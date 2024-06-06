<?php 
    $choice = isset($_POST['choice']) ? $_POST['choice'] : "";
    switch($choice) 
    {
        case 'statistics_product':
            StatisticsProduct();
            break;
        case 'statistics_category':
            StatisticsCategory();
            break;
        case 'statistics_top10_product':
            StatisticsTop10Product();
            break;
        case 'statistics_profit':
            StatisticsProfit();
            break;
        case 'statistics_top_10_staff':
            StatisticsTop10Staff();
            break;
    }
    function StatisticsProduct()
    {
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
    }
    function StatisticsCategory()
    {
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
    }
    function StatisticsTop10Product()
    {
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
    }
    function StatisticsProfit()
    {
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
    }
    function StatisticsTop10Staff()
    {
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
    }
?>