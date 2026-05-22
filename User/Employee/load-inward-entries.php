<?php
error_reporting(0);
session_start();
header('Content-Type: application/json');

if($_SESSION['EmpId']=="")
{
	echo json_encode(array("result" => "Error", "message" => "Session expired."));
}
else
{		
	$delete_status = 1;	
	
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	
	$data = json_decode(file_get_contents("php://input"));
	$search = trim($data->search);
	
	if($search != "")
	{
		$query = $dbConnection->prepare("
			SELECT ir.*, v.DisplayName as SupplierName 
			FROM InwardRolls ir 
			JOIN VendorMaster v ON ir.VendorId = v.VendorId 
			WHERE ir.DeleteStatus=? 
			  AND (ir.RollId LIKE ? OR v.DisplayName LIKE ? OR ir.Material LIKE ? OR ir.InvoiceNo LIKE ?) 
			ORDER BY ir.PkId DESC
		");
		$searchTerm = "%" . $search . "%";
		$query->execute(array($delete_status, $searchTerm, $searchTerm, $searchTerm, $searchTerm));
	}
	else
	{
		$query = $dbConnection->prepare("
			SELECT ir.*, v.DisplayName as SupplierName 
			FROM InwardRolls ir 
			JOIN VendorMaster v ON ir.VendorId = v.VendorId 
			WHERE ir.DeleteStatus=? 
			ORDER BY ir.PkId DESC
		");
		$query->execute(array($delete_status));
	}
	
	$num_rows = $query->rowCount();
	if($num_rows > 0)
	{	
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
		
		// Add formatted date
		foreach($rows as $key => $row)
		{
			$rows[$key]['InvoiceDateFormatted'] = date("d-M-Y", strtotime($row['InvoiceDate']));
		}
		
		echo json_encode($rows);
	}
	else
	{
		echo json_encode("NoData");
	}
}
?>
