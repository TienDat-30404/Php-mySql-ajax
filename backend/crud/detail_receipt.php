<?php 
$idReceipt = $_GET['id_receipt'];
include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";

$sql = "SELECT entry_slips.total_price as totalPrice, products.image as imageProduct, entry_slips.date_entry as dateCreate, 
        suppliers.name as nameSupplier, users.fullname as nameStaff, 
        products.name as nameProduct, products.price as priceProduct, entry_slip_details.quantity as quantityProduct 
        FROM entry_slips JOIN suppliers ON entry_slips.supplier_id = suppliers.id
        JOIN users ON entry_slips.staff_id = users.id
        JOIN entry_slip_details ON entry_slips.id = entry_slip_details.entry_slip_id
        JOIN products ON entry_slip_details.product_id = products.id WHERE entry_slips.id = '$idReceipt'";
$data = array();
$result = DataSQL::querySQL($sql);
while($row = mysqli_fetch_array($result)) {
    $data[] = $row;
}

echo json_encode($data);
?>