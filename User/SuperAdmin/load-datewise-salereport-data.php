<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']=="")
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

    $query = $dbConnection->prepare("SELECT InvoiceId,CustomerName,InvoiceDate FROM Invoices WHERE InvoiceDate BETWEEN '$FromDate' AND '$ToDate' AND DeleteStatus=? ORDER BY PkId DESC LIMIT $first,$itemsPerPage");
    $query->execute(array($delete_status));
    $num_rows = $query->rowCount();
    $a = "1";
    if($num_rows>0)
    {	
    	while($row = $query->fetch())
    	{
            $InvoiceId = $row['InvoiceId'];
            $CustomerName = $row['CustomerName'];
            $InvDate = $row['InvoiceDate'];
            
                $query1 = $dbConnection->prepare("SELECT * FROM InvoiceDetails WHERE InvoiceId_Invoices=? AND DeleteStatus=?");
                $query1->execute(array($InvoiceId,$delete_status));
                while($row1 = $query1->fetch())
                {

                    $Quantity = $row1['Quantity'];

                    $query2 = $dbConnection->prepare("SELECT Micron,ProductName,Unit FROM ProductMaster WHERE ProductId=?");
                    $query2->execute(array($row1['ProductId_ProductMaster']));
                    $row2 = $query2->fetch();
                    $Micron =$row2['Micron'];
                    $ProductName =$row2['ProductName'];
                    $Unit =$row2['Unit'];
                    $TotalName= "$Micron $ProductName"; 

                    $query3 = $dbConnection->prepare("SELECT UniqueRollNo,ProductSize,Remarks,PkId_RawPurchaseMasterDetails FROM InventoryMaster WHERE PkId=?");
                    $query3->execute(array($row1['PkId_InventoryMaster']));
                    $row3 = $query3->fetch();
                    $InvUniqueRollNo =$row3['UniqueRollNo'];            
                    $InvProductSize =$row3['ProductSize'];
                    $Remarks = $row3['Remarks'];
                    $IsSplitQty = $row3['IsSplitQty'];


                    $query4 = $dbConnection->prepare("SELECT rpm.RawPODate,rpm.PkId_GoDownMaster,rpm.VendorId_VendorMaster,prmd.ProductId_ProductMaster,prmd.PurchaseQty,prmd.RollNo,prmd.ProductSize FROM RawPurchaseMasterDetails AS prmd INNER JOIN  RawPurchaseMaster AS rpm ON prmd.RawPurchaseId_RawPurchaseMaster=rpm.RawPurchaseId AND prmd.PkId=?");
                    $query4->execute(array($row3['PkId_RawPurchaseMasterDetails']));
                    $row4 = $query4->fetch();
                    $ProductId_ProductMaster = $row4['ProductId_ProductMaster'];
                    $VendorId_VendorMaster = $row4['VendorId_VendorMaster'];
                    $PurchaseQty = $row4['PurchaseQty'];
                    $PkId_GoDownMaster = $row4['PkId_GoDownMaster'];
                    $RawPODate = $row4['RawPODate'];
                    $PurchaseRollNo = $row4['RollNo'];
                    $POProductSize = $row4['ProductSize'];

                    $query5 = $dbConnection->prepare("SELECT DisplayName FROM VendorMaster WHERE VendorId=?");
                    $query5->execute(array($VendorId_VendorMaster));
                    $row5 = $query5->fetch();
                    $VendorName = $row5['DisplayName'];

                    if($IsSplitQty==1)
                    {
                        $query8 = $dbConnection->prepare("SELECT Slitting.SlitId,Slitting.SlitDate,SlittingRolls.* FROM Slitting INNER JOIN SlittingRolls ON Slitting.SlitId=SlittingRolls.SlitId_Slitting WHERE  Slitting.PkId_InventoryMaster=?");
                        $query8->execute(array($row1['PkId_InventoryMaster']));
                        $IsSlitted = $query8->rowCount();
                        while($row8 = $query8->fetch())
                        {
                            $SlitId = $row8['SlitId'];
                            $SlitDate = $row8['SlitDate'];
                            $SplitSize = $row8['SplitSize'];
                            $SlitQty = $row8['SlitQty'];

                            $data4[] = array(
                                'SplitSize' => $SplitSize,
                                'SlitQty' => $SlitQty
                                );
                        }
                    }
                    else
                    {
                        $query9 = $dbConnection->prepare("SELECT Slitting.SlitId,Slitting.SlitDate,SlittingRolls.* FROM Slitting INNER JOIN SlittingRolls ON Slitting.SlitId=SlittingRolls.SlitId_Slitting WHERE  SlittingRolls.RollNo LIKE '%$InvUniqueRollNo%'");
                        $query9->execute(array());
                        $IsSlitted = $query9->rowCount();
                        $row9 = $query9->fetch();
                        $SlitId = $row9['SlitId'];
                        $SlitDate = $row9['SlitDate'];
                        $SplitSize = $row9['SplitSize'];
                        $SlitQty = $row9['SlitQty'];
                    }

                    $data2[] = array(
                            'PkId' => $row1['PkId'],
                            'UniqueRollNo'=>$UniqueRollNo,
                            'Remarks' => $Remarks,
                            'Quantity' => $Quantity,
                            'Micron' => $Micron,
                            'ProductSize' => $ProductSize,
                            'ProductName' => $ProductName,
                            'Unit' => $Unit,
                            'InvUniqueRollNo'=>$InvUniqueRollNo,'InvProductSize'=>$InvProductSize,
                            'IsSplitQty'=>$IsSplitQty,
                            'VendorName'=>$VendorName,'RawPODate'=>$RawPODate,'TotalName'=>$TotalName,'POProductSize'=>$POProductSize,
                            'PurchaseQty'=>$PurchaseQty,'PurchaseRollNo'=>$PurchaseRollNo,'IsSlitted'=>$IsSlitted,'SlitDate'=>$SlitDate,'SplitSize'=>$SplitSize,'SlitQty'=>$SlitQty,'data4'=>$data4,'IsInvoice'=>$IsInvoice
                        );
                }

    		$data1[] = array(
    			'PkId' => $row['PkId'],
                'InvoiceId'=>$InvoiceId,
                'CustomerName'=>$CustomerName,
                'InvDate'=>$InvDate,
                'data2' => $data2);
            unset($data2);
    	}
    	$sql = $dbConnection->prepare("SELECT COUNT(PkId) AS Total FROM Invoices WHERE InvoiceDate BETWEEN '$FromDate' AND '$ToDate' AND DeleteStatus=?");
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