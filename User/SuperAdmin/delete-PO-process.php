<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{	
	
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";
		$DATEIME = GetDateTime();		
		$UserId = $_SESSION['UserId'];	

		$data = json_decode(file_get_contents("php://input"));
		$PkId = $data->PkId;
		$POrderId = $data->POrderId;

		$delete_status = 0;
		$data1 = array(); 
		$trueqty = 1;

		if($PkId!="")	
		{
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
			// $entrydate = date('Y-m-d',strtotime($entrydate));
		 //  	$shipdate = date('Y-m-d', strtotime($shipdate));

			$sql2 = $dbConnection->prepare("UPDATE PurcahseOrders SET DeleteStatus=? WHERE POrderId=?");
			$sql2->execute(array($delete_status,$POrderId));

			$sql3 = $dbConnection->prepare("UPDATE PurchaseOrderDetails SET DeleteStatus=? WHERE POrderId_PurchaseOrders=?");
			$sql3->execute(array($delete_status,$POrderId));

			$query12 = $dbConnection->prepare("SELECT PkId,TDate,Closing_Balance FROM Product_Transaction WHERE ReferenceId=?  ORDER BY PkId DESC LIMIT 0,1");
			$query12->execute(array($entrydate,$POrderId,$delete_status));


				// $query9 = $dbConnection->prepare("SELECT Quantity FROM InventoryMaster WHERE ProductId_ProductMaster=?");
				// $query9->execute(array($ProductId[$key]));
				// $row9 = $query9->fetch();
				// $AvlQuantity = $row9['Quantity'];

				// $FreshQty = $AvlQuantity+$quantity[$key];
				// $Ttotal = $quantity[$key]*$price[$key];
				// $query1 = $dbConnection->prepare("INSERT INTO PurchaseOrderDetails (POrderId_PurchaseOrders,ProductId_ProductMaster,Quantity,Price,Amount,CreatedTime) VALUES (?,?,?,?,?,?)");
				// $query1->execute(array($POrderId,$ProductId[$key],$quantity[$key],$price[$key],$Ttotal,$DATEIME));

				// $query12 = $dbConnection->prepare("SELECT PkId,TDate,Closing_Balance FROM Product_Transaction WHERE TDate=? AND ProductId_ProductMaster=? AND DeleteStatus=? ORDER BY PkId DESC LIMIT 0,1");
				// $query12->execute(array($entrydate,$ProductId[$key],$delete_status));
				// if($query12->rowCount()>=1)
				// {
				// 	//echo "Hi";
				// 	$row12 = $query12->fetch();
				// 	$pastcb = $row12['Closing_Balance'];
				// }
				// else
				// {
				// 	//echo "Bi";
				// 	$query12 = $dbConnection->prepare("SELECT PkId,TDate,Closing_Balance FROM Product_Transaction WHERE TDate<? AND ProductId_ProductMaster=? AND DeleteStatus=? ORDER BY PkId DESC LIMIT 0,1");
				// 	$query12->execute(array($entrydate,$ProductId[$key],$delete_status));
				// 	$row12 = $query12->fetch();
				// 	$pastcb = $row12['Closing_Balance'];
				// }
				// $freshcb = $pastcb+$quantity[$key];

				// $query4 = $dbConnection->prepare("INSERT INTO Product_Transaction (TDate,Type,Activity,ReferenceId,ProductId_ProductMaster,Quantity,Opening_Balance,Closing_Balance,CreatedTime) VALUES (?,?,?,?,?,?,?,?,?)");
				// $query4->execute(array($entrydate,'Purchase','ADD',$POrderId,$ProductId[$key],$quantity[$key],$pastcb,$freshcb,$DATEIME));

				// //set olddata loop
				// $query21 = $dbConnection->prepare("SELECT * FROM Product_Transaction WHERE TDate>=? AND DeleteStatus=? AND ProductId_ProductMaster=? AND TDate!=? ORDER BY TDate ASC");
				// $query21->execute(array($entrydate,$delete_status,$ProductId[$key],$entrydate));					
				// while($row21 = $query21->fetch()) 
				// {
				// 	# code...
				// 	 $SetPkId21 = $row21['PkId'];
				// 	$Opening_Balance = $row21['Opening_Balance'];
				// 	$Closing_Balance = $row21['Closing_Balance'];
					
				// 	$Freshob = $Opening_Balance+$quantity[$key];
				// 	$freshcb1 = $Closing_Balance+$quantity[$key];

				// 	$query14 = $dbConnection->prepare("UPDATE Product_Transaction SET Opening_Balance=?,Closing_Balance=? WHERE ProductId_ProductMaster=? AND PkId=?");
				// 	$query14->execute(array($Freshob,$freshcb1,$ProductId[$key],$SetPkId21));
				// }

				// $query5 = $dbConnection->prepare("UPDATE InventoryMaster SET Quantity=? WHERE ProductId_ProductMaster=?");
				// $query5->execute(array($FreshQty,$ProductId[$key]));
			
			$Post = "Deleted Size as on $DATEIME";
				$Type="Size";		
				$query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
				$query2->execute(array($SAId,$Post,$Type,$PkId,$DATEIME));
			echo "Success";
		}	
		else
		{
		echo "Invalid";
		}
}
?>