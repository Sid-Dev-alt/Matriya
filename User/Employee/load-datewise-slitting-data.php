<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{	
	$delete_status = 1;	
    $data1 = array();
    $data2 = array();
    include "../../CommonUtilities/Connections.php";
    include_once "../../CommonUtilities/Functions.php";
    $data = json_decode(file_get_contents("php://input"));
    
    $itemsPerPage = $data->itemsPerPage;
    $pagenumber = $data->pagenumber;
    $first = ($pagenumber-1)*$itemsPerPage;
    $fromdate = $data->fromdate;
    $todate = $data->todate;

	$FromDate = date("Y-m-d",strtotime($fromdate));
	$ToDate = date("Y-m-d",strtotime($todate));
 // echo $FromDate;
 // echo $ToDate;
    $query = $dbConnection->prepare("SELECT * FROM Slitting WHERE DeleteStatus=? AND SlitDate BETWEEN '$FromDate' AND '$ToDate' ORDER BY PkId DESC LIMIT $first,$itemsPerPage");
    $query->execute(array($delete_status));
    $num_rows = $query->rowCount();
    if($num_rows>0)
    {   
        while($row = $query->fetch())
        {
            $FormPkId = $row['PkId'];
            $SlitId = $row['SlitId'];
            $SlitDate = $row['SlitDate'];
            
            $PkId_InventoryMaster = $row['PkId_InventoryMaster'];
            $Remarks = $row['Remarks'];


            $query2 = $dbConnection->prepare("SELECT PkId_RawPurchaseMasterDetails,ProductId_ProductMaster,UniqueRollNo,ProductSize,Remarks FROM InventoryMaster WHERE PkId=?");
            $query2->execute(array($PkId_InventoryMaster));
            $row2 = $query2->fetch();
            $PkId_RawPurchaseMasterDetails = $row2['PkId_RawPurchaseMasterDetails'];
            $UniqueRollNo = $row2['UniqueRollNo'];
            $ProductId_ProductMaster = $row2['ProductId_ProductMaster'];
            $ProductSize = $row2['ProductSize'];
            $DetailRemarks = $row2['Remarks'];

            $query9 = $dbConnection->prepare("SELECT RawPurchaseMaster.PkId_GoDownMaster,GoDownMaster.GoDownName FROM RawPurchaseMasterDetails INNER JOIN RawPurchaseMaster ON RawPurchaseMasterDetails.RawPurchaseId_RawPurchaseMaster=RawPurchaseMaster.RawPurchaseId INNER JOIN GoDownMaster ON RawPurchaseMaster.PkId_GoDownMaster=GoDownMaster.PkId WHERE RawPurchaseMasterDetails.PkId=?");
            $query9->execute(array($PkId_RawPurchaseMasterDetails));
            $row9 = $query9->fetch();
            $GoDownName = $row9['GoDownName'];


            $query4 = $dbConnection->prepare("SELECT ProductName,Micron,Unit FROM ProductMaster WHERE ProductId=?");
            $query4->execute(array($ProductId_ProductMaster));
            $row4 = $query4->fetch();
            $ProductName = $row4['ProductName'];
            $Micron = $row4['Micron'];
            $Unit = $row4['Unit'];


            $query3 = $dbConnection->prepare("SELECT * FROM SlittingRolls WHERE SlitId_Slitting=? AND DeleteStatus=?");
            $query3->execute(array($SlitId,$delete_status));
            while($row3 = $query3->fetch())
            {
                $EntryPkId = $row3['PkId'];
                $SplitSize = $row3['SplitSize'];
                $RollNo = $row3['RollNo'];
                $SlitQty = $row3['SlitQty'];    
                
                // $query6 = $dbConnection->prepare("SELECT ProductName,Micron,Unit FROM ProductMaster WHERE ProductId=?");
                // $query6->execute(array($NewProductId_ProductMaster));
                // $row6 = $query6->fetch();
                // $NewProductName = $row6['ProductName'];
                // $NewMicron = $row6['Micron'];
                // $NewUnit = $row6['Unit'];

                $data2[] = array(
                'EntryPkId' => $EntryPkId,
                'NewProductId_ProductMaster' => $ProductId_ProductMaster,
                'NewProductName' => $ProductName,
                'NewProductSize' => $SplitSize,
                'NewMicron' => $Micron,
                'NewUnit' => $Unit,
                'SplitSize' => $SplitSize,
                'RollNo' => $RollNo,
                'SlitQty' => $SlitQty
                );
            }

            $data1[] = array(
                'FormPkId' => $FormPkId,
                'SlitId' => $SlitId,
                'GoDownName' => $GoDownName,
                'ProductId_ProductMaster' => $ProductId_ProductMaster,
                'ProductName' => $ProductName,
                'ProductSize' => $ProductSize,
                'Micron' => $Micron,
                'Unit' => $Unit,
                'SlitDate' => $SlitDate,
                'DetailRemarks' => $DetailRemarks,
                'UniqueRollNo' => $UniqueRollNo,
                'Remarks'=>$Remarks,
                'data2'=>$data2,
            );
            unset($data2);
        }
        $sql = $dbConnection->prepare("SELECT COUNT(PkId) AS Total FROM Slitting WHERE DeleteStatus=? AND SlitDate BETWEEN '$FromDate' AND '$ToDate'");
        $sql->execute(array($delete_status));
        $row4 = $sql->fetch();
        $Total = $row4['Total'];
        $data3 = array("Total"=>$Total,"data1"=>$data1);
        echo json_encode($data3);
    }
    else
    {
        echo "NoData";
    }

}
?>