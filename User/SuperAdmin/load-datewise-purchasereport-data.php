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

    $query = $dbConnection->prepare("SELECT RawPurchaseMasterDetails.*,RawPurchaseMaster.* FROM RawPurchaseMasterDetails INNER JOIN RawPurchaseMaster ON RawPurchaseMasterDetails.RawPurchaseId_RawPurchaseMaster=RawPurchaseMaster.RawPurchaseId WHERE RawPurchaseMasterDetails.TDate BETWEEN '$FromDate' AND '$ToDate' AND RawPurchaseMasterDetails.DeleteStatus=? ORDER BY RawPurchaseMasterDetails.PkId DESC LIMIT $first,$itemsPerPage");
    $query->execute(array($delete_status));
    $num_rows = $query->rowCount();
    $a = "1";
    if($num_rows>0)
    {	
    	while($row = $query->fetch())
    	{
            $TDate = $row['TDate'];
            $Quantity = $row['PurchaseQty'];
            $RollNo = $row['RollNo'];
            $PkId_GoDownMaster = $row['PkId_GoDownMaster'];
            $ProductSize =$row['ProductSize'];
            $Comments = $row['Remarks'];

            $query2 = $dbConnection->prepare("SELECT Micron,ProductName,Unit FROM ProductMaster WHERE ProductId=?");
            $query2->execute(array($row['ProductId_ProductMaster']));
            $row2 = $query2->fetch();
            $Micron =$row2['Micron'];
            $ProductName =$row2['ProductName'];
            $Unit =$row2['Unit'];

            $query3 = $dbConnection->prepare("SELECT GoDownName FROM GoDownMaster WHERE PkId=?");
            $query3->execute(array($PkId_GoDownMaster));
            $row3 = $query3->fetch();
            $GoDownName =$row3['GoDownName'];

    		$data1[] = array(
    			'PkId' => $row['PkId'],
                'TDate'=>$TDate,
    			'UniqueRollNo'=>$RollNo,
                'GoDownName'=>$GoDownName,
    			'Quantity' => $Quantity,
                'Micron' => $Micron,
                'ProductSize' => $ProductSize,
                'ProductName' => $ProductName,
                'Unit' => $Unit,
                "Comments" => $Comments,
    		);
    	}
    	$sql = $dbConnection->prepare("SELECT COUNT(PkId) AS Total FROM RawPurchaseMasterDetails WHERE TDate BETWEEN '$FromDate' AND '$ToDate' AND DeleteStatus=?");
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