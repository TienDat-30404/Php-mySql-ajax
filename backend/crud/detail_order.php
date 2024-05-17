<?php 
$idOrder = $_GET['id_order'];
include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";

$sql = "SELECT products.name as nameProduct, products.image as imageProduct, products.price as priceProduct, users.fullname as nameUser, bills.date_create as dateCreate,
bills.payment_method as paymentMethod, bills.number_account_bank as numberAccountBank, bills.total_price as totalPrice, 
bill_details.quantity as quantityProduct, bills.address as address FROM products JOIN bill_details ON products.id = bill_details.product_id JOIN bills 
ON bill_details.bill_id = bills.id JOIN users ON bills.user_id = users.id WHERE bills.id = '$idOrder'";

$data = array();
$result = DataSQL::querySQL($sql);
while($row = mysqli_fetch_array($result)) {
    $data[] = $row;
}

$sqlNameStaff = "SELECT users.fullname as nameStaff FROM users JOIN bills ON users.id = bills.staff_id WHERE bills.id = '$idOrder'";
$resultNameStaff = DataSQL::querySQL($sqlNameStaff);
$rowNameStaff = mysqli_fetch_array($resultNameStaff);

// Kiểm tra xem có nhân viên không, nếu có thì thêm vào từng phần tử trong mảng chi tiết đơn hàng
if ($rowNameStaff) {
    foreach ($data as &$detail) {
        $detail['nameStaff'] = $rowNameStaff['nameStaff'];
    }
}
echo json_encode($data);
?>