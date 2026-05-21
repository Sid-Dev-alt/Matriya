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

    $query = $dbConnection->prepare("SELECT Invoices.InvoiceId,Invoices.CustomerName,InvoiceDetails.* FROM Invoices INNER JOIN InvoiceDetails ON Invoices.InvoiceId=InvoiceDetails.InvoiceId_Invoices WHERE InvoiceDetails.InvDate BETWEEN '$FromDate' AND '$ToDate' AND Invoices.DeleteStatus=? ORDER BY Invoices.PkId DESC LIMIT $first,$itemsPerPage");
    $query->execute(array($delete_status));
    $num_rows = $query->rowCount();
    $a = "1";
    if($num_rows>0)
    {	
    	while($row = $query->fetch())
    	{
            $InvoiceId = $row['InvoiceId'];
            $CustomerName = $row['CustomerName'];
            $InvDate = $row['InvDate'];
            $Quantity = $row['Quantity'];
            
            $query2 = $dbConnection->prepare("SELECT Micron,ProductName,Unit FROM ProductMaster WHERE ProductId=?");
            $query2->execute(array($row['ProductId_ProductMaster']));
            $row2 = $query2->fetch();
            $Micron =$row2['Micron'];
            $ProductName =$row2['ProductName'];
            $Unit =$row2['Unit'];

            $query3 = $dbConnection->prepare("SELECT UniqueRollNo,ProductSize,Remarks FROM InventoryMaster WHERE PkId=?");
            $query3->execute(array($row['PkId_InventoryMaster']));
            $row3 = $query3->fetch();
            $UniqueRollNo =$row3['UniqueRollNo'];            
            $ProductSize =$row3['ProductSize'];
            $Remarks = $row3['Remarks'];

    		$data1[] = array(
    			'PkId' => $row['PkId'],
                'InvoiceId'=>$InvoiceId,
                'CustomerName'=>$CustomerName,
                'InvDate'=>$InvDate,
    			'UniqueRollNo'=>$UniqueRollNo,
    			'Remarks' => $Remarks,
    			'Quantity' => $Quantity,
                'Micron' => $Micron,
                'ProductSize' => $ProductSize,
                'ProductName' => $ProductName,
                'Unit' => $Unit,
    		);
    	}
    	$sql = $dbConnection->prepare("SELECT COUNT(PkId) AS Total FROM InvoiceDetails WHERE InvDate BETWEEN '$FromDate' AND '$ToDate' AND DeleteStatus=?");
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