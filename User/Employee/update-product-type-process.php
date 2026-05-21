<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{	

		$data = json_decode(file_get_contents("php://input"));	
		  $FormPkId = $_REQUEST['FormPkId'];
		  $category = $_REQUEST['category'];
		  $subcategory = $_REQUEST['subcategory'];
		  $lvl2subcat = $_REQUEST['lvl2subcat'];
		 $lvl3subcat = $_REQUEST['lvl3subcat'];
		  $productid = $_REQUEST['productid'];
		  $productname = ltrim(ucfirst($_REQUEST['productname']));
		  $sku = ltrim($_REQUEST['sku']);
		  $unit = $_REQUEST['unit'];
		  $FileName = $_REQUEST['FileName'];
		  $costprice = $_REQUEST['costprice'];
		  $salesprice = $_REQUEST['salesprice'];
		  $productsize = trim($_REQUEST['productsize']);
		  $colour = $_REQUEST['colour'];
		  $invtype = $_REQUEST['invtype'];
		  $_REQUEST['isreturn'];
		  if($_REQUEST['isreturn']=="true")
		  {
		  	$isreturn = 1;
		  }
		  if($_REQUEST['isreturn']=="false")
		  {
		  	$isreturn = 0;
		  }
		  $micron = $_REQUEST['micron'];
		  $dimension = $_REQUEST['dimension'];
		  $weight = $_REQUEST['weight'];
		  $manfacture = $_REQUEST['manfacture'];
		  $brand = $_REQUEST['brand'];
		  $mpn = $_REQUEST['mpn'];
		  $upc = $_REQUEST['upc'];
		  $isbn = $_REQUEST['isbn'];
		  $ean = $_REQUEST['ean'];
		  $openstock = $_REQUEST['openstock'];
		  $stockrate = $_REQUEST['stockrate'];
		  $reorderlevel = $_REQUEST['reorderlevel'];
		  $VendorId = $_REQUEST['VendorId'];
		  $vendorname = $_REQUEST['vendorname'];
		  $invtrack = $_REQUEST['invtrack'];
		  $oldiinvtrack = $_REQUEST['oldiinvtrack'];
		  $serailnumber = $_REQUEST['serailnumber'];
		 $EntryPkId = json_decode($_REQUEST['EntryPkId']); 
		 $BatchNo = json_decode($_REQUEST['BatchNo']);
		 $ManfcBatch = json_decode($_REQUEST['ManfcBatch']);
		 $Mfgdate = json_decode($_REQUEST['Mfgdate']);
		 $Expdate = json_decode($_REQUEST['Expdate']);
		 $Quantity = json_decode($_REQUEST['Quantity']);
		 $AvlQuantity = json_decode($_REQUEST['AvlQuantity']);
		 $Totalbatchcount = $_REQUEST['sum'];

// if($productsize!="" && $colour=="")
// 		{
// 			$NewProductName = $productname."-".$productsize;
// 		}
// 		else if($productsize=="" && $colour!="")
// 		{
// 			$NewProductName = $productname."-".$colour;
// 		}
// 		else if($productsize!="" && $colour!="")
// 		{
// 			$NewProductName = $productname."-".$productsize."-".$colour;
// 		}
// 		else if($productsize=="" && $colour=="")
// 		{
// 			$NewProductName = $productname;
// 		}

		$NewProductName=rtrim($productname);
		$sku=rtrim($sku);

		$schools_array = explode(",", $serailnumber);
		$result = count($schools_array);
		$snocount = substr_count( $serailnumber, ",") +1; 

	// 	$serialchk=array();	 
	// $serialArr=array();	 
 //   $var=explode(',',$serailnumber);
 //   foreach($var as $row)
 //    {
 //    	//echo $row.""."a\n";
 //    	if($row!="")
 //    	{
 //    		$serialchk[]=1;
 //    		$serialArr[]= $row;
 //    	}
 //    	else
 //    	{
 //    		$serialchk[]=0;
 //    		$serialArr[]= $row;
 //    	}
 //    }

 //    $BatchDtChk=array();
 //    if($invtrack=="Batch")
 //    {
	//   	foreach ($BatchNo as $key => $value) {
	// 	 	# code...
	// 	 if($Expdate[$key]!="" && $Mfgdate[$key]!="")	
	// 	 {
	// 	 	if(date('Y-m-d',strtotime($Expdate[$key]))>date('Y-m-d',strtotime($Mfgdate[$key])))
	//     	{
	//     		$BatchDtChk[]=1;	//true
	//     	}
	//     	else
	//     	{
	//     		$BatchDtChk[]=0; //false
	//     	}
	// 	 }
	// 	}
 //    }

 //    $Batchchk=array();
 //     $BatchArrDuplicate=array();	 
 //  	foreach ($BatchNo as $key => $value) {
	//  	# code...
	//  	if($BatchNo[$key]!="")
 //    	{
 //    		$Batchchk[]=1;
 //    		$BatchArrDuplicate[]=$BatchNo[$key];
 //    	}
 //    	else
 //    	{
 //    		$Batchchk[]=0;
 //    		$BatchArrDuplicate[]=$BatchNo[$key];
 //    	}
	//  }

	//   $Qtychk=array();	 
 //  	foreach ($Quantity as $key => $value) {
	//  	# code...
	//  	if($Quantity[$key]!="")
 //    	{
 //    		$Qtychk[]=1;	
 //    	}
 //    	else
 //    	{
 //    		$Qtychk[]=0;
 //    	}
	//  }
//    print_r($serialArr);

		 //$FileName = $_REQUEST['FileName'];
		 $status = $data->status;
		// if($FormPkId!="" && $productid!="" && $category!="" && $NewProductName!="" && $sku!="" && $unit!=""  && ($costprice!="" || $costprice=="0") && ($salesprice!="" || $salesprice=="0") && ($stockrate!="" || $stockrate=="0"))
		// {
			$check = 0;
			$Errors .= "";
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
			$SAId = $_SESSION['EmpId'];
			$DATEIME = GetDateTime();
			$delete_status = 1;
			if($category=="")
			{
				$check = 1;
				$Errors .= "Category Name required"."\n";
			}
			if($productid=="")
			{
				$check = 1;
				$Errors .= "ProductId required"."\n";
			}
			if($NewProductName=="")
			{
				$check = 1;
				$Errors .= "Product Name required"."\n";
			}
			// if($sku=="")
			// {
			// 	$check = 1;
			// 	$Errors .= "SKU is required"."\n";
			// }
			if($unit=="")
			{
				$check = 1;
				$Errors .= "Unit is required"."\n";
			}
			// if($costprice=="" || $costprice=="0")
			// {
			// 	$check = 1;
			// 	$Errors .= "Cost Price is required"."\n";
			// }
			// if($salesprice=="" || $costprice=="0")
			// {
			// 	$check = 1;
			// 	$Errors .= "Selling Price is required"."\n";
			// }
			// if($openstock=="")
			// {
			// 	$check = 1;
			// 	$Errors .= "Opening stock is required"."\n";
			// }
			// if($invtrack=="")
			// {
			// 	$check = 1;
			// 	$Errors .= "Select Inventory Track is required"."\n";
			// }
			// if($invtype=="")
			// {
			// 	$check = 1;
			// 	$Errors .= "Inventory Type is required"."\n";
			// }
			// if(count(array_unique($serialArr))<count($serialArr))
			// {
			//     // Array has duplicates
			//     $check = 1;
			// 	$Errors .= "Serial Numbers should not be duplicate"."\n";
			// }
			// if(count(array_unique($BatchArrDuplicate))<count($BatchArrDuplicate))
			// {
			//     // Array has duplicates
			//     $check = 1;
			// 	$Errors .= "Batch Numbers should not be duplicate"."\n";
			// }
			// $PArr = array();
			// $skuArr = array();
			// if($micron=="" && $productsize!="")
			// {
			// 	$DData = "AND Size='$productsize'";	
			// 	$DSet = 1;	
			// }
			// if($productsize=="" && $micron!="")
			// {
			// 	$DData = "AND Micron='$micron'";	
			// 	$DSet = 2;	
			// }
			// if($productsize!="" && $micron!="")
			// {
			// 	$DData = "AND Micron='$micron' AND Size='$productsize'";
			// 	$DSet = 3;		
			// }
			// if($productsize=="" && $micron=="")
			// {
			// 	$DData = "";
			// 	$DSet = 4;
			// }
			//echo $DSet;
			//echo "SELECT ProductName,Size,Micron FROM ProductMaster WHERE  DeleteStatus='$delete_status' AND PkId!='$FormPkId' AND ProductName='$NewProductName' $DData";
			$query1 = $dbConnection->prepare("SELECT * FROM ProductMaster WHERE  DeleteStatus=? AND PkId!=? AND ProductName=? AND Micron=?");
			$query1->execute(array($delete_status,$FormPkId,$NewProductName,$micron));
			if($query1->rowCount()>0)
			{
				$row1 = $query1->fetch();
				$PMicron = $row1['Micron'];
				//$PSize = $row1['productsize'];
				$PName = $row1['ProductName'];

				$check = 1;
				// if($DSet=="1")
				// {
				// 	$Errors .= "$NewProductName with size ($productsize) already exists"."\n";	
				// }
				// if($DSet=="2")
				// {
				// 	$Errors .= "$NewProductName with micron $micron already exists"."\n";	
				// }
				// if($DSet=="3")
				// {
				// 	$Errors .= "$NewProductName with size $productsize and micron $micron already exists"."\n";	
				// }
				// if($DSet=="4")
				// {
					$Errors .= "$PMicron $PName already exists"."\n";	
				//}
				
			 }
			// $query1 = $dbConnection->prepare("SELECT ProductName,SKU FROM ProductMaster WHERE  DeleteStatus=? AND PkId!=?");
			// $query1->execute(array($delete_status,$FormPkId));
			// while($row1 = $query1->fetch())
			// {
			// 	$PArr[] = $row1['ProductName'];
			// 	$skuArr[] = $row1['SKU'];
			// }

			// if(in_array($NewProductName, $PArr))
			// {
			// 	$check = 1;
			// 	$Errors .= "Product Name already exists"."\n";
			// }
			// if(in_array($sku, $skuArr))
			// {
			// 	$check = 1;
			// 	$Errors .= "SKU already exists"."\n";
			// }

			// if($invtrack=="Serial" && $serailnumber=="")
			// {
			// 	$check = 1;
			// 	$Errors .= "Please make sure that you have entered serial numbers for all the item units"."\n";
			// }
			// if($serailnumber!="" && $snocount!=$openstock)
			// {
			// 	$check = 1;
			// 	$Errors .= "Looks like there's a mismatch between the quantity and the serial numbers entered"."\n";
			// }
			// if($invtrack=="Serial" && in_array('0', $serialchk))
			// {
			// 	$check = 1;
			// 	$Errors .= "Looks like some of serial numbers is empty"."\n";
			// }
			// if($invtrack=="Batch" && in_array('0', $Batchchk))
			// {
			// 	$check = 1;
			// 	$Errors .= "BatchNo could not be empty"."\n";
			// }
			// if($invtrack=="Batch" &&  in_array('0', $BatchDtChk))
			// {
			// 	$check = 1;
			// 	$Errors .= "Expiry date should be greater than the Manufacture date"."\n";
			// }
			// if($invtrack=="Batch" &&  in_array('0', $Qtychk))
			// {
			// 	$check = 1;
			// 	$Errors .= "Quantity could not be empty"."\n";
			// }
			// if($invtrack=="Batch" && $Totalbatchcount!=$openstock)
			// {
			// 	$check = 1;
			// 	$Errors .= "Looks like there's a mismatch between the quantity and sum of batch count entered"."\n";
			// }
			if($check==1){
			echo $Errors;
			}
			else 
			{
				$upload_dir = "../ProductImages/";
				//$validextensions = array("jpeg", "jpg", "png", "gif");
				 $temporary = explode(".", $_FILES["file"]["name"]);
				 $file_extension = end($temporary);

				$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
				
				if($_FILES['file']['name']=="")
				{
					$filename1="$FileName";	
				}else
				{
					unlink("../ProductImages/".$FileName);
					$filename1 = $category."-".$sku.str_replace(' ', '', $_FILES['file']['name']);	
				}
				
				$targetPath = $upload_dir.$filename1; // Target path where file is to be stored


				move_uploaded_file($sourcePath,$targetPath); // Moving Uploaded file

				$query = $dbConnection->prepare("UPDATE ProductMaster SET PkId_Category=?,PkId_SubCategoryMaster=?,PkId_Level2SubCategoryMaster=?,PkId_Level3SubCategoryMaster=?,ProductName=?,SKU=?,Unit=?,FileName=?,Size=?,Micron=?,Colour=?,IsItReturnable=?,Dimensions=?,Weight=?,Manufacturer=?,Brand=?,UPC=?,MPN=?,EAN=?,ISBN=?,SalesPrice=?,PurchasePrice=?,OpeningStockRateperUnit=?,ReorderPoint=?,TrackingMode=?,VendorId_VendorMaster=? WHERE PkId=?");
				$query->execute(array($category,$subcategory,$lvl2subcat,$lvl3subcat,$NewProductName,$sku,$unit,$filename1,$productsize,$micron,$colour,$isreturn,$dimension,$weight,$manfacture,$brand,$upc,$mpn,$ean,$isbn,$salesprice,$costprice,$stockrate,$reorderlevel,$invtrack,$VendorId,$FormPkId));

				// $query2 = $dbConnection->prepare("SELECT Size FROM SizeMaster WHERE Size=?");
				// $query2->execute(array($productsize));
				// if($query2->rowCount()==0)
				// {
				// 	$query3 = $dbConnection->prepare("INSERT INTO SizeMaster (Size,CreatedTime) VALUES (?,?)");
				//  	$query3->execute(array($productsize,$DATEIME));
				// }
				// if($oldiinvtrack==$invtrack)
				// {
				// 	if($invtrack=="None")
				// 	{
				// 		$query2 = $dbConnection->prepare("UPDATE InventoryMaster SET OpeningQuantity=? WHERE ProductId_ProductMaster=?)");
				// 		$query2->execute(array($openstock,$productid));
				// 	}

				// 	if($invtrack=="Serial")
				// 	{
				// 		 $var=explode(',',$serailnumber);
				// 	   foreach($var as $row)
				// 	    {
				// 	    	$query1 = $dbConnection->prepare("SELECT * FROM InventoryMaster WHERE ProductId_ProductMaster=? AND BatchNoORSrNo=?");
				// 			$query1->execute(array($productid,$row));
				// 			if($query1->rowCount()>0)
				// 			{

				// 			}
				// 			else
				// 			{
				// 				$query2 = $dbConnection->prepare("INSERT INTO InventoryMaster (ProductId_ProductMaster,BatchNoORSrNo,OpeningQuantity,Quantity,CreatedTime) VALUES (?,?,?,?,?)");
				// 				$query2->execute(array($productid,$row,'1','1',$DATEIME));
				// 			}
							
				// 		}
				// 	}
				// 	if($invtrack=="Batch")
				// 	{
				// 		foreach ($EntryPkId as $key => $value) {
				// 		# code...
				// 			if($EntryPkId1=="")
				// 			{
				// 				$EntryPkId1 = $EntryPkId[$key];
				// 				$BatchNo1 = $BatchNo[$key];
				// 				$ManfcBatch1 = $ManfcBatch[$key];
				// 				$Mfgdate1 = date('Y-m-d',strtotime($Mfgdate[$key]));
				// 		 		$Expdate1 = date('Y-m-d',strtotime($Expdate[$key]));
				// 	 			$Quantity1 = $Quantity[$key];

				// 				$query2 = $dbConnection->prepare("INSERT INTO InventoryMaster (ProductId_ProductMaster,BatchNoORSrNo,BatchManufacturer,ManfactureDate,ExpireDate,OpeningQuantity,Quantity,CreatedTime) VALUES (?,?,?,?,?,?,?,?)");
				// 				$query2->execute(array($productid,$BatchNo1,$ManfcBatch1,$Mfgdate1,$Expdate1,$Quantity1,$Quantity1,$DATEIME));
				// 			}
				// 			else
				// 			{
				// 				$EntryPkId1 = $EntryPkId[$key];
				// 				$BatchNo1 = $BatchNo[$key];
				// 				$ManfcBatch1 = $ManfcBatch[$key];
				// 				$Mfgdate1 = date('Y-m-d',strtotime($Mfgdate[$key]));
				// 		 		$Expdate1 = date('Y-m-d',strtotime($Expdate[$key]));
				// 	 			$Quantity1 = $Quantity[$key];

				// 				$query2 = $dbConnection->prepare("UPDATE InventoryMaster SET BatchNoORSrNo=?,BatchManufacturer=?,ManfactureDate=?,ExpireDate=?,OpeningQuantity=? WHERE PkId=?)");
				// 				$query2->execute(array($BatchNo1,$ManfcBatch1,$Mfgdate1,$Expdate1,$Quantity1,$EntryPkId1));
				// 			}
							
				// 		}
				// 	}
				// }
				// else
				// {
				// 	$query2 = $dbConnection->prepare("UPDATE InventoryMaster SET DeleteStatus=? WHERE ProductId_ProductMaster=?");
				// 	$query2->execute(array('0',$productid));

				// 	if($invtrack=="None")
				// 	{
				// 		$query2 = $dbConnection->prepare("INSERT INTO InventoryMaster (ProductId_ProductMaster,OpeningQuantity,Quantity,CreatedTime) VALUES (?,?,?,?)");
				// 		$query2->execute(array($productid,$openstock,$openstock,$DATEIME));
				// 	}
				// 	if($invtrack=="Serial")
				// 	{
				// 		 $var=explode(',',$serailnumber);
				// 	   foreach($var as $row)
				// 	    {
				// 		# code...
				// 		$query2 = $dbConnection->prepare("INSERT INTO InventoryMaster (ProductId_ProductMaster,BatchNoORSrNo,OpeningQuantity,Quantity,CreatedTime) VALUES (?,?,?,?,?)");
				// 		$query2->execute(array($productid,$row,'1','1',$DATEIME));
				// 		}
				// 	}
				// 	if($invtrack=="Batch")
				// 	{
				// 		foreach ($BatchNo as $key => $value) {
				// 		# code...
				// 			$BatchNo1 = $BatchNo[$key];
				// 			$ManfcBatch1 = $ManfcBatch[$key];
				// 			$Mfgdate1 = date('Y-m-d',strtotime($Mfgdate[$key]));
				// 	 		$Expdate1 = date('Y-m-d',strtotime($Expdate[$key]));
				//  			$Quantity1 = $Quantity[$key];

				// 		$query2 = $dbConnection->prepare("INSERT INTO InventoryMaster (ProductId_ProductMaster,BatchNoORSrNo,BatchManufacturer,ManfactureDate,ExpireDate,OpeningQuantity,Quantity,CreatedTime) VALUES (?,?,?,?,?,?,?,?)");
				// 		$query2->execute(array($productid,$BatchNo1,$ManfcBatch1,$Mfgdate1,$Expdate1,$Quantity1,$Quantity1,$DATEIME));
				// 		}
				// 	}
				// }

				$Post = "Updated product with (ProductId: $productid,PkId_Category: $category,PkId_SubCategoryMaster: $subcategory,PkId_Level2SubCategoryMaster: $lvl2subcat,PkId_Level3SubCategoryMaster: $lvl3subcat,ProductName: $NewProductName,SKU: $sku,Unit: $unit,FileName: $filename1,Size: $productsize,Micron: $micron,Colour: $colour,InventoryType: $invtype,IsItReturnable: $isreturn,Dimensions: $dimension,Weight: $weight,Manufacturer: $manfacture,Brand: $brand,UPC: $upc,MPN: $mpn,EAN: $ean,ISBN: $isbn,SalesPrice: $salesprice,PurchasePrice: $costprice,OpeningStock: $openstock,OpeningStockRateperUnit: $stockrate,ReorderPoint: $reorderlevel,TrackingMode: $invtrack,VendorId_VendorMaster: $VendorId) as on $DATEIME by $SAId";
				$Type="Product";		
				$query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
				$query2->execute(array($SAId,$Post,$Type,$productid,$DATEIME));

				echo $result= "Success";
			}
}
?>
