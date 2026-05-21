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
    $godownname = $data->godownname;
 //    $todate = $data->todate;

	// $FromDate = date("Y-m-d",strtotime($fromdate));
	// $ToDate = date("Y-m-d",strtotime($todate));
 // echo $FromDate;
 // echo $ToDate;
   // echo "SELECT * FROM RawPurchaseMasterDetails AS prmd INNER JOIN  RawPurchaseMaster AS rpm ON prmd.RawPurchaseId_RawPurchaseMaster=rpm.RawPurchaseId AND rpm.PkId_GoDownMaster='$godownname' WHERE prmd.DeleteStatus='$delete_status' GROUP BY prmd.RawPurchaseId_RawPurchaseMaster";

    $query = $dbConnection->prepare("SELECT prmd.RawPurchaseId_RawPurchaseMaster,inv.* FROM RawPurchaseMasterDetails AS prmd INNER JOIN  RawPurchaseMaster AS rpm ON prmd.RawPurchaseId_RawPurchaseMaster=rpm.RawPurchaseId INNER JOIN InventoryMaster AS inv ON prmd.PkId=inv.PkId_RawPurchaseMasterDetails AND rpm.PkId_GoDownMaster=? WHERE prmd.DeleteStatus=? AND inv.Quantity>0 ");
    $query->execute(array($godownname,$delete_status));
    $num_rows = $query->rowCount();
    $a = "1";
    if($num_rows>0)
    {	
    	while($row1 = $query->fetch())
    	{
            $RawPurchaseId_RawPurchaseMaster = $row['RawPurchaseId_RawPurchaseMaster'];

          //  echo "SELECT * FROM InventoryMaster WHERE DeleteStatus='$delete_status' AND PkId_RawPurchaseMasterDetails='$RawPkId' AND Quantity>0";

            // $query1 = $dbConnection->prepare("SELECT * FROM InventoryMaster WHERE DeleteStatus=? AND PkId_RawPurchaseMasterDetails=? AND Quantity>0 ORDER BY PkId DESC");
            // $query1->execute(array($delete_status,$RawPkId));
            // if($query1->rowCount())
            // {
            //     while($row1 = $query1->fetch())
            //     {
                    $InvPkId = $row1['PkId'];
                    $UniqueRollNo = $row1['UniqueRollNo'];
                    $Quantity = $row1['Quantity'];
                    $ProductId_ProductMaster = $row1['ProductId_ProductMaster'];
                    $ProductSize = $row1['ProductSize'];

                    $query3 = $dbConnection->prepare("SELECT * FROM ProductMaster WHERE ProductId=?");
                    $query3->execute(array($ProductId_ProductMaster));
                    $row3 = $query3->fetch();
                    $Micron = $row3['Micron'];
                    $ProductName = $row3['ProductName'];
                    $Unit = $row3['Unit'];
                    if($ProductSize=="" || $ProductSize=="NA")
                    {
                        $TotalProduct = "$Micron $ProductName";
                    }
                    else
                    {
                        $TotalProduct = "$Micron $ProductName $ProductSize"." "."MM";
                    }
                    

                    $data2[] = array(
                    'InvPkId' => $InvPkId,
                    'UniqueRollNo'=>$UniqueRollNo,
                    'Quantity' => $Quantity,
                    'ProductId_ProductMaster' => $ProductId_ProductMaster,
                    'Unit' => $Unit,
                    'ProductName' => $ProductName,
                    'TotalProduct' => $TotalProduct,
                    );
                }

                // $data1[] = array('RawPkId' => $RawPkId,'data2' => $data2);    
                // unset($data2); 
            //} 
    	//}
    	// $sql = $dbConnection->prepare("SELECT prmd.PkId,prmd.RawPurchaseId_RawPurchaseMaster FROM RawPurchaseMasterDetails AS prmd INNER JOIN  RawPurchaseMaster AS rpm ON prmd.RawPurchaseId_RawPurchaseMaster=rpm.RawPurchaseId AND rpm.PkId_GoDownMaster=? WHERE prmd.DeleteStatus=?");
    	// $sql->execute(array($godownname,$delete_status));
    	// $Total = $sql->rowCount();

    	// $data3 = array("Total"=>$Total,"data1"=>$data1);
    
    	echo json_encode($data2);
    }
    else
    {
    	echo "NoData";
    }

}
?>