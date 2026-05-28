<?php
error_reporting(0);
session_start();
header('Content-Type: application/json');

if($_SESSION['UserId']=="")
{
	echo json_encode(array("result" => "Error", "message" => "Session expired."));
}
else
{		
	$delete_status = 1;	
	
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	
	$data = json_decode(file_get_contents("php://input"));
	$search = isset($data->search) ? trim($data->search) : "";
	
	if($search != "")
	{
		$query = $dbConnection->prepare("
			SELECT 
				im.PkId,
				im.UniqueRollNo AS RollId,
				IF(im.UniqueRollNo LIKE 'JR-%', 'Full', 'Remnant') AS RollType,
				pm.ProductName AS Material,
				COALESCE(ir.GSM, '') AS GSM,
				pm.Micron AS Thickness,
				im.ProductSize AS Width,
				im.Quantity AS Weight,
				IF(im.Status = 1, 'Available', 'Dispatched') AS Status,
				COALESCE(gm.GoDownName, 'Main Godown') AS Location,
				COALESCE(ir.Notes, im.Remarks, '') AS Remarks,
				im.CreatedTime
			FROM InventoryMaster im
			JOIN ProductMaster pm ON im.ProductId_ProductMaster = pm.ProductId
			LEFT JOIN GoDownMaster gm ON im.PkId_GoDownMaster = gm.PkId
			LEFT JOIN RawPurchaseMasterDetails rpmd ON im.PkId_RawPurchaseMasterDetails = rpmd.PkId
			LEFT JOIN InwardRolls ir ON rpmd.RollNo = ir.RollId
			WHERE im.Quantity > 0
			  AND im.Status = 1
			  AND im.DeleteStatus = 1
			  AND (im.UniqueRollNo LIKE ? 
			       OR pm.ProductName LIKE ? 
			       OR ir.GSM LIKE ? 
			       OR pm.Micron LIKE ? 
			       OR im.ProductSize LIKE ? 
			       OR gm.GoDownName LIKE ? 
			       OR ir.Notes LIKE ? 
			       OR im.Remarks LIKE ?)
			ORDER BY im.PkId DESC
		");
		$searchTerm = "%" . $search . "%";
		$query->execute(array($searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm));
	}
	else
	{
		$query = $dbConnection->prepare("
			SELECT 
				im.PkId,
				im.UniqueRollNo AS RollId,
				IF(im.UniqueRollNo LIKE 'JR-%', 'Full', 'Remnant') AS RollType,
				pm.ProductName AS Material,
				COALESCE(ir.GSM, '') AS GSM,
				pm.Micron AS Thickness,
				im.ProductSize AS Width,
				im.Quantity AS Weight,
				IF(im.Status = 1, 'Available', 'Dispatched') AS Status,
				COALESCE(gm.GoDownName, 'Main Godown') AS Location,
				COALESCE(ir.Notes, im.Remarks, '') AS Remarks,
				im.CreatedTime
			FROM InventoryMaster im
			JOIN ProductMaster pm ON im.ProductId_ProductMaster = pm.ProductId
			LEFT JOIN GoDownMaster gm ON im.PkId_GoDownMaster = gm.PkId
			LEFT JOIN RawPurchaseMasterDetails rpmd ON im.PkId_RawPurchaseMasterDetails = rpmd.PkId
			LEFT JOIN InwardRolls ir ON rpmd.RollNo = ir.RollId
			WHERE im.Quantity > 0
			  AND im.Status = 1
			  AND im.DeleteStatus = 1
			ORDER BY im.PkId DESC
		");
		$query->execute();
	}
	
	$num_rows = $query->rowCount();
	if($num_rows > 0)
	{	
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
		
		// Add formatting if needed
		foreach($rows as $key => $row)
		{
			$rows[$key]['CreatedDateFormatted'] = date("d-M-Y", strtotime($row['CreatedTime']));
			// Convert weight and width to floats for display consistency
			$rows[$key]['Width'] = number_format(floatval($row['Width']), 2, '.', '');
			$rows[$key]['Weight'] = number_format(floatval($row['Weight']), 2, '.', '');
		}
		
		echo json_encode($rows);
	}
	else
	{
		echo json_encode("NoData");
	}
}
?>
