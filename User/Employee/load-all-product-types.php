<?php
error_reporting(0);
session_start();

if (empty($_SESSION['EmpId'])) {
    echo "<script>window.location='../../index.php';</script>";
    exit;
}

include_once "../../CommonUtilities/Connections.php";

$data = json_decode(file_get_contents("php://input"));
$delete_status = 1;

$query = $dbConnection->prepare("
    SELECT 
        PM.*, 
        cat.CategoryName,
        (SELECT SUM(Quantity) FROM InventoryMaster WHERE ProductId_ProductMaster = PM.ProductId AND DeleteStatus = ?) AS AvlQty
    FROM ProductMaster PM
    INNER JOIN Category cat ON cat.PkId = PM.PkId_Category
    WHERE PM.DeleteStatus = ?
    ORDER BY PM.PkId DESC
");
$query->execute([$delete_status, $delete_status]);
$products = $query->fetchAll();

if (empty($products)) {
    echo "NoData";
    exit;
}

$data1 = [];
foreach ($products as $product) {
    $ProductSize = $product['Size'];
    $Micron = $product['Micron'];
    $ProductName = $product['ProductName'];
    $TotalProductName = ($ProductSize && $ProductSize != "NA") ? "$Micron $ProductName $ProductSize MM" : "$Micron $ProductName";

    $displaystatus = $product['Status'] == 1 ? "Active" : "Inactive";

    // Fetch available inventory for the product
    $inventoryQuery = $dbConnection->prepare("
        SELECT 
            PkId, UniqueRollNo, Quantity 
        FROM InventoryMaster 
        WHERE ProductId_ProductMaster = ? AND DeleteStatus = ? AND Quantity > 0
    ");
    $inventoryQuery->execute([$product['ProductId'], $delete_status]);
    $inventories = $inventoryQuery->fetchAll();

    $data2 = [];
    foreach ($inventories as $inventory) {
        $data2[] = [
            "InvPkId" => $inventory['PkId'],
            "UniqueRollNo" => $inventory['UniqueRollNo'],
            "Quantity" => $inventory['Quantity']
        ];
    }

    $data1[] = [
        "PkId" => $product['PkId'],
        "ProductId" => $product['ProductId'],
        "PkId_Category" => $product['PkId_Category'],
        "CategoryName" => $product['CategoryName'],
        "PkId_SubCategoryMaster" => $product['PkId_SubCategoryMaster'],
        "PkId_Level2SubCategoryMaster" => $product['PkId_Level2SubCategoryMaster'],
        "PkId_Level3SubCategoryMaster" => $product['PkId_Level3SubCategoryMaster'],
        "InventoryType" => $product['InventoryType'],
        "FileName" => $product['FileName'],
        "ProductName" => $product['ProductName'],
        "TotalProductName" => $TotalProductName,
        "AvlQty" => $product['AvlQty'],
        "Micron" => $Micron,
        "ProductSize" => $ProductSize,
        "Unit" => $product['Unit'],
        "Status" => $product['Status'],
        "displaystatus" => $displaystatus,
        "data2" => $data2
    ];
}

echo json_encode($data1);
?>