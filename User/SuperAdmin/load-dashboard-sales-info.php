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
    
 //    $itemsPerPage = $data->itemsPerPage;
 //    $pagenumber = $data->pagenumber;
 //    $first = ($pagenumber-1)*$itemsPerPage;
 //    $fromdate = $data->fromdate;
 //    $todate = $data->todate;

	// $FromDate = date("Y-m-d",strtotime($fromdate));
	// $ToDate = date("Y-m-d",strtotime($todate));
    $todaydt = date("Y-m-d");
 // echo $FromDate;
 // echo $ToDate;

    $query = $dbConnection->prepare("SELECT * FROM InvoiceDetails WHERE InvDate=? AND DeleteStatus=? ORDER BY PkId DESC");
    $query->execute(array($todaydt,$delete_status));
    $num_rows = $query->rowCount();
    $a = "1";
    if($num_rows>0)
    {	
    	while($row = $query->fetch())
    	{
            $InvDate = $row['InvDate'];
            $Quantity = $row['Quantity'];

            $query2 = $dbConnection->prepare("SELECT Micron,ProductName,Size,Unit FROM ProductMaster WHERE ProductId=?");
            $query2->execute(array($row['ProductId_ProductMaster']));
            $row2 = $query2->fetch();
            $Micron =$row2['Micron'];
            $ProductName =$row2['ProductName'];
            $Unit =$row2['Unit'];

            $query3 = $dbConnection->prepare("SELECT UniqueRollNo,ProductSize FROM InventoryMaster WHERE PkId=?");
            $query3->execute(array($row['PkId_InventoryMaster']));
            $row3 = $query3->fetch();
            $UniqueRollNo =$row3['UniqueRollNo'];
            $ProductSize =$row3['ProductSize'];

    		$data1[] = array(
    			'PkId' => $row['PkId'],
                'InvDate'=>$InvDate,
    			'UniqueRollNo'=>$UniqueRollNo,
    			'Quantity' => $Quantity,
                'Micron' => $Micron,
                'ProductSize' => $ProductSize,
                'ProductName' => $ProductName,
                'Unit' => $Unit,
    		);
    	}
    	// $sql = $dbConnection->prepare("SELECT * FROM InvoiceDetails WHERE InvDate BETWEEN '$FromDate' AND '$ToDate' AND DeleteStatus=?");
    	// $sql->execute(array($delete_status));
    	// $Total = $sql->rowCount();
    	// $data3 = array("Total"=>$Total,"data1"=>$data1);
    	echo json_encode($data1);
    }
   // else
    //{
    	//echo "NoData";
    //}

}
?>
