<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']=="")
{
	echo "Unauthorized";
	exit;
}

include "../../CommonUtilities/Connections.php";
include_once "../../CommonUtilities/Functions.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    $data = $_POST;
}

$orderId = isset($data['OrderId']) ? $data['OrderId'] : '';
$customerName = isset($data['CustomerName']) ? $data['CustomerName'] : '';
$orderDate = isset($data['OrderDate']) ? $data['OrderDate'] : '';
$totalAmount = isset($data['TotalAmount']) ? $data['TotalAmount'] : 0.00;
$items = isset($data['items']) ? $data['items'] : [];

if ($orderId != "" && $customerName != "" && $orderDate != "") {
    $orderDateFormatted = date('Y-m-d', strtotime($orderDate));
    $DATEIME = GetDateTime();
    
    // Begin Transaction
    $dbConnection->beginTransaction();
    try {
        // Insert into orders
        $query = $dbConnection->prepare("INSERT INTO orders (OrderId, CustomerName, OrderDate, TotalAmount, CreatedTime) VALUES (?, ?, ?, ?, ?)");
        $query->execute(array($orderId, $customerName, $orderDateFormatted, $totalAmount, $DATEIME));
        
        // Insert into order_details
        foreach ($items as $item) {
            $productName = $item['ProductName'];
            $quantity = floatval($item['Quantity']);
            $price = 0.00;
            $amount = 0.00;
            $remarks = isset($item['Remarks']) ? $item['Remarks'] : '';
            
            $queryDetail = $dbConnection->prepare("INSERT INTO order_details (OrderId, ProductName, Quantity, Price, Amount, Remarks, CreatedTime) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $queryDetail->execute(array($orderId, $productName, $quantity, $price, $amount, $remarks, $DATEIME));
        }
        
        $dbConnection->commit();
        echo "Success";
    } catch (Exception $e) {
        $dbConnection->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Enter Mandatory Fields";
}
?>
