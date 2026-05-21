<?php
error_reporting(0);
session_start();

if (empty($_SESSION['EmpId'])) {
    echo "<script>window.location='../../index.php';</script>";
    exit;
}

$data = json_decode(file_get_contents("php://input"));
$delete_status = 1;
$itemsPerPage = $data->itemsPerPage;
$pagenumber = $data->pagenumber;
$first = ($pagenumber - 1) * $itemsPerPage;

include "../../CommonUtilities/Connections.php";

$query = $dbConnection->prepare("
    SELECT 
        RPM.PkId AS FormPkId,
        RPM.RawPurchaseId,
        RPM.RawPODate,
        RPM.VendorId_VendorMaster,
        VM.DisplayName AS VendorName,
        RPM.PkId_GoDownMaster,
        GDM.GoDownName,
        RPM.Comments
    FROM RawPurchaseMaster RPM
    LEFT JOIN VendorMaster VM ON RPM.VendorId_VendorMaster = VM.VendorId
    LEFT JOIN GoDownMaster GDM ON RPM.PkId_GoDownMaster = GDM.PkId
    WHERE RPM.DeleteStatus = ?
    ORDER BY RPM.PkId DESC
    LIMIT ?, ?
");
$query->execute([$delete_status, $first, $itemsPerPage]);
$results = $query->fetchAll();

if (empty($results)) {
    echo "NoData";
    exit;
}

$data1 = [];
foreach ($results as $row) {
    $RawPurchaseId = $row['RawPurchaseId'];

    // Fetch RawPurchaseMasterDetails for each RawPurchaseId
    $detailsQuery = $dbConnection->prepare("
        SELECT 
            RPD.PkId AS EntryPkId,
            RPD.ProductId_ProductMaster,
            RPD.PurchaseQty,
            RPD.RollNo,
            RPD.ProductSize,
            RPD.IsSplitQty,
            RPD.Remarks AS DetailRemarks,
            PM.ProductName,
            PM.Micron,
            PM.Unit,
            (
                SELECT COUNT(*) 
                FROM InventoryMaster IM
                INNER JOIN InvoiceDetails ID ON IM.PkId = ID.PkId_InventoryMaster
                WHERE IM.PkId_RawPurchaseMasterDetails = RPD.PkId AND ID.DeleteStatus = ?
            ) AS IsInvoiced
        FROM RawPurchaseMasterDetails RPD
        LEFT JOIN ProductMaster PM ON RPD.ProductId_ProductMaster = PM.ProductId
        WHERE RPD.RawPurchaseId_RawPurchaseMaster = ? AND RPD.DeleteStatus = ?
    ");
    $detailsQuery->execute([$delete_status, $RawPurchaseId, $delete_status]);
    $details = $detailsQuery->fetchAll();

    // Calculate TotalQty and prepare details array
    $TotalQty = 0;
    $data2 = [];
    foreach ($details as $detail) {
        $TotalQty += $detail['PurchaseQty'];
        $TotalProductName = empty($detail['ProductSize']) || $detail['ProductSize'] == "NA" 
            ? "{$detail['Micron']} {$detail['ProductName']}" 
            : "{$detail['Micron']} {$detail['ProductName']} {$detail['ProductSize']} MM";

        $data2[] = [
            'EntryPkId' => $detail['EntryPkId'],
            'ProductId_ProductMaster' => $detail['ProductId_ProductMaster'],
            'ProductName' => $detail['ProductName'],
            'TotalProductName' => $TotalProductName,
            'ProductSize' => $detail['ProductSize'],
            'Micron' => $detail['Micron'],
            'PurchaseQty' => $detail['PurchaseQty'],
            'Remarks' => $detail['DetailRemarks'],
            'RollNo' => $detail['RollNo'],
            'Unit' => $detail['Unit'],
            'IsSplitQty' => $detail['IsSplitQty'],
            'Isinvoiced' => $detail['IsInvoiced'],
        ];
    }

    $data1[] = [
        'FormPkId' => $row['FormPkId'],
        'RawPurchaseId' => $row['RawPurchaseId'],
        'VendorId_VenodrMaster' => $row['VendorId_VendorMaster'],
        'VendorName' => $row['VendorName'],
        'RawPODate' => $row['RawPODate'],
        'PkId_GoDownMaster' => $row['PkId_GoDownMaster'],
        'GoDownName' => $row['GoDownName'],
        'Comments' => $row['Comments'],
        'data2' => $data2,
        'TotalQty' => $TotalQty,
    ];
}

// Fetch Total Count for Pagination
$countQuery = $dbConnection->prepare("SELECT COUNT(PkId) AS Total FROM RawPurchaseMaster WHERE DeleteStatus = ?");
$countQuery->execute([$delete_status]);
$total = $countQuery->fetchColumn();

echo json_encode(['Total' => $total, 'data1' => $data1]);
?>