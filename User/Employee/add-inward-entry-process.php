<?php
error_reporting(0);
session_start();
header('Content-Type: application/json');

if($_SESSION['EmpId']=="")
{
	echo json_encode(array("result" => "Error", "message" => "Session expired. Please log in again."));
}
else
{		
	$data = json_decode(file_get_contents("php://input")); 
	
	$VendorId = $data->VendorId;
	$VendorPkId = $data->VendorPkId;
	$Material = trim($data->Material);
	$GSM = trim($data->GSM);
	$Thickness = trim($data->Thickness); // Micron
	$Width = trim($data->Width); // mm
	$Weight = trim($data->Weight); // kg
	$CostPerKg = trim($data->CostPerKg);
	$InvoiceNo = trim($data->InvoiceNo);
	$InvoiceDate = $data->InvoiceDate;
	$CoreSize = trim($data->CoreSize);
	$Notes = trim($data->Notes);
	
	$delete_status = 1;
	$check = 0;
	$Errors = "";
	
	if($VendorId=="" || $VendorPkId=="") { $check = 1; $Errors .= "Supplier is required.\n"; }
	if($Material=="") { $check = 1; $Errors .= "Material is required.\n"; }
	if($GSM=="") { $check = 1; $Errors .= "GSM is required.\n"; }
	if($Thickness=="") { $check = 1; $Errors .= "Thickness/Micron is required.\n"; }
	if($Width=="") { $check = 1; $Errors .= "Width is required.\n"; }
	if($Weight=="") { $check = 1; $Errors .= "Weight is required.\n"; }
	if($CostPerKg=="") { $check = 1; $Errors .= "Cost per Kg is required.\n"; }
	if($InvoiceNo=="") { $check = 1; $Errors .= "Invoice No is required.\n"; }
	if($InvoiceDate=="") { $check = 1; $Errors .= "Invoice Date is required.\n"; }
	if($CoreSize=="") { $check = 1; $Errors .= "Core Size is required.\n"; }
	
	if($check==1)
	{
		echo json_encode(array("result" => "Error", "message" => $Errors));
	}
	else
	{
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";
		
		$SAId = $_SESSION['EmpId'];
		$DATEIME = GetDateTime();
		$todaydt = GetTDate();
		$InvoiceDateFormatted = date("Y-m-d", strtotime($InvoiceDate));
		
		// 1. Generate Roll ID (e.g. JR-1024)
		// Check the last roll starting with JR- in InwardRolls
		$queryRoll = $dbConnection->prepare("SELECT RollId FROM InwardRolls WHERE RollId LIKE 'JR-%' ORDER BY PkId DESC LIMIT 0,1");
		$queryRoll->execute();
		if($queryRoll->rowCount() > 0)
		{
			$rowRoll = $queryRoll->fetch();
			$lastRollId = $rowRoll['RollId']; // e.g. JR-1024
			$lastNum = intval(substr($lastRollId, 3));
			$nextNum = $lastNum + 1;
			$RollId = "JR-" . $nextNum;
			$RollIdNum = $nextNum;
		}
		else
		{
			// If no rolls exist, start from JR-1001
			$RollId = "JR-1001";
			$RollIdNum = 1001;
		}
		
		// 2. Resolve Product in ProductMaster
		// Find active product with ProductName = Material, Micron = Thickness, Unit = "Kg"
		$queryProd = $dbConnection->prepare("SELECT ProductId FROM ProductMaster WHERE ProductName=? AND Micron=? AND Unit=? AND DeleteStatus=? LIMIT 0,1");
		$queryProd->execute(array($Material, $Thickness, 'Kg', $delete_status));
		
		if($queryProd->rowCount() > 0)
		{
			$rowProd = $queryProd->fetch();
			$ProductId = $rowProd['ProductId'];
		}
		else
		{
			// Create new Product in ProductMaster
			// Generate next ProductId
			$sqlPID = $dbConnection->prepare("SELECT ProductId FROM ProductMaster ORDER BY PkId DESC LIMIT 0,1");
			$sqlPID->execute();
			if($sqlPID->rowCount() > 0)
			{
				$rowPID = $sqlPID->fetch();	
				$valuePID = substr($rowPID['ProductId'], 3);
				$variablePID = intval($valuePID) + 1;
				$lengthPID = strlen($variablePID);
				if($lengthPID < 3)
				{
					switch($lengthPID)
					{
						case 2: $variablePID = "0".$variablePID; break;
						case 1: $variablePID = "00".$variablePID; break;
					}
				}
				$ProductId = substr($rowPID['ProductId'], 0, 3) . $variablePID;
			}
			else
			{
				$ProductId = "MRP001";
			}
			
			// Insert into ProductMaster
			$queryInsertProd = $dbConnection->prepare("INSERT INTO ProductMaster (ProductId, PkId_Category, ProductName, Unit, Micron, Size, InventoryType, TrackingMode, DeleteStatus, UserId_Users, CreatedTime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$queryInsertProd->execute(array($ProductId, 1, $Material, 'Kg', $Thickness, $Width, 'Raw', 'None', $delete_status, $SAId, $DATEIME));
		}
		
		// 3. Insert into InwardRolls
		$queryInward = $dbConnection->prepare("INSERT INTO InwardRolls (RollId, VendorId, Material, GSM, Thickness, Width, Weight, CostPerKg, InvoiceNo, InvoiceDate, Notes, DeleteStatus, CreatedTime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$queryInward->execute(array($RollId, $VendorId, $Material, $GSM, $Thickness, $Width, $Weight, $CostPerKg, $InvoiceNo, $InvoiceDateFormatted, "Core Size: " . $CoreSize . ". " . $Notes, $delete_status, $DATEIME));
		$InwardPkId = $dbConnection->lastInsertId();
		
		// 4. Generate Raw Purchase Master entry
		// Generate next RawPurchaseId
		$sqlRPID = $dbConnection->prepare("SELECT RawPurchaseId FROM RawPurchaseMaster ORDER BY PkId DESC LIMIT 0,1");
		$sqlRPID->execute();
		if($sqlRPID->rowCount() > 0)
		{
			$rowRPID = $sqlRPID->fetch();	
			$valueRPID = substr($rowRPID['RawPurchaseId'], 3);
			$variableRPID = intval($valueRPID) + 1;
			$lengthRPID = strlen($variableRPID);
			if($lengthRPID < 3)
			{
				switch($lengthRPID)
				{
					case 2: $variableRPID = "0".$variableRPID; break;
					case 1: $variableRPID = "00".$variableRPID; break;
				}
			}
			$RawPurchaseId = substr($rowRPID['RawPurchaseId'], 0, 3) . $variableRPID;
		}
		else
		{
			$RawPurchaseId = "MPO101";
		}
		
		// Insert into RawPurchaseMaster
		$queryRP = $dbConnection->prepare("INSERT INTO RawPurchaseMaster (RawPurchaseId, RawPODate, VendorId_VendorMaster, CreatedTime) VALUES (?, ?, ?, ?)");
		$queryRP->execute(array($RawPurchaseId, $InvoiceDateFormatted, $VendorId, $DATEIME));
		
		// Insert into RawPurchaseMasterDetails
		// Set RollNo = RollId (e.g. JR-1024), RollId = numeric part
		$queryRPD = $dbConnection->prepare("INSERT INTO RawPurchaseMasterDetails (RawPurchaseId_RawPurchaseMaster, ProductId_ProductMaster, ProductSize, TDate, RollId, RollNo, PurchaseQty, Remarks, CreatedTime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$queryRPD->execute(array($RawPurchaseId, $ProductId, $Width, $InvoiceDateFormatted, $RollIdNum, $RollId, $Weight, "Core Size: " . $CoreSize . ". " . $Notes, $DATEIME));
		$RPD_PkId = $dbConnection->lastInsertId();
		
		// 5. Insert into InventoryMaster
		$queryInv = $dbConnection->prepare("INSERT INTO InventoryMaster (PkId_RawPurchaseMasterDetails, ProductId_ProductMaster, ProductSize, UniqueRollNo, Quantity, Remarks, CreatedTime) VALUES (?, ?, ?, ?, ?, ?, ?)");
		$queryInv->execute(array($RPD_PkId, $ProductId, $Width, $RollId, $Weight, "Core Size: " . $CoreSize . ". " . $Notes, $DATEIME));
		
		// 6. Activity log
		$Post = "Created Inward Entry & Jumbo Roll (RollId: $RollId, Supplier: $VendorId, Material: $Material, GSM: $GSM, Thickness: $Thickness, Width: $Width, Weight: $Weight, CostPerKg: $CostPerKg, InvoiceNo: $InvoiceNo, InvoiceDate: $InvoiceDateFormatted) as on $DATEIME by $SAId";
		$Type = "InwardEntry";		
		$queryLog = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users, ActivityInfo, ModuleType, UpdatedId, CreatedTime) VALUES (?, ?, ?, ?, ?)");
		$queryLog->execute(array($SAId, $Post, $Type, $RollId, $DATEIME));
		
		// Return success response
		echo json_encode(array("result" => "Success", "RollId" => $RollId, "PkId" => $InwardPkId));
	}
}
?>
