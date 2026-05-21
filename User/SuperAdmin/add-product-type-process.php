<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{	
	
		$data = json_decode(file_get_contents("php://input")); 
		 
		  $category = $_REQUEST['category'];
		 //  $subcategory = $_REQUEST['subcategory'];
		 //  $lvl2subcat = $_REQUEST['lvl2subcat'];
		 // $lvl3subcat = $_REQUEST['lvl3subcat'];
		  $productid = $_REQUEST['productid'];
		  $productname = ltrim(ucfirst($_REQUEST['productname']));
		  $sku = ltrim($_REQUEST['sku']);
		  $unit = $_REQUEST['unit'];
		  $costprice = $_REQUEST['costprice'];
		  $salesprice = $_REQUEST['salesprice'];
		  $productsize = trim($_REQUEST['productsize']);
		  $colour = $_REQUEST['colour'];
		  $invtype = $_REQUEST['invtype'];
		  if($_REQUEST['isreturn']==true)
		  {
		  	$isreturn = 1;
		  }
		  else
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
		  $openweight = $_REQUEST['openweight'];
		  if($_REQUEST['invtype']=="Finished")
		  {
		   	$openstock = $openstock;
		  }
		  if($_REQUEST['invtype']=="Raw")
		  {
		   	$openstock = $openweight;
		  }
		  $stockrate = $_REQUEST['stockrate'];
		  $reorderlevel = $_REQUEST['reorderlevel'];
		  $VendorId = $_REQUEST['VendorId'];
		  $vendorname = $_REQUEST['vendorname'];
		  $invtrack = $_REQUEST['invtrack'];
		  $serailnumber = $_REQUEST['serailnumber'];
		 $BatchNo = json_decode($_REQUEST['BatchNo']);
		 $ManfcBatch = json_decode($_REQUEST['ManfcBatch']);
		 $Mfgdate = json_decode($_REQUEST['Mfgdate']);
		 $Expdate = json_decode($_REQUEST['Expdate']);
		 $Quantity = json_decode($_REQUEST['Quantity']);
		 $Totalbatchcount = $_REQUEST['sum'];
		 

		// if($productsize!="" && $colour=="")
		// {
		// 	$NewProductName = $productname."-".$productsize;
		// }
		// else if($productsize=="" && $colour!="")
		// {
		// 	$NewProductName = $productname."-".$colour;
		// }
		// else if($productsize!="" && $colour!="")
		// {
		// 	$NewProductName = $productname."-".$productsize."-".$colour;
		// }
		// else if($productsize=="" && $colour=="")
		// {
		// 	$NewProductName = $productname;
		// }

		$NewProductName=rtrim($productname);
		$sku=rtrim($sku);


		 $delete_status = 1;
			$check = 0;
			$Errors .= "";
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
			$SAId = $_SESSION['UserId'];
			$DATEIME = GetDateTime();
			$todaydt = GetTDate();
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
			if($unit=="")
			{
				$check = 1;
				$Errors .= "Unit is required"."\n";
			}
			// $PArr = array();
			// $skuArr = array();
// 			if($micron=="" && $productsize!="")
// 			{
// 				$DData = "AND Size='$productsize'";	
// 				$DSet = 1;	
// 			}
// 			if($productsize=="" && $micron!="")
// 			{
// 				$DData = "AND Micron='$micron'";	
// 				$DSet = 2;	
// 			}
// 			if($productsize!="" || $micron!="")
// 			{
// 				$DData = "AND Micron='$micron' AND Size='$productsize'";
// 				$DSet = 3;		
// 			}
// 			if($productsize=="" || $micron=="")
// 			{
// 				$DData = "";
// 				$DSet = 4;
// 			}
// echo $DSet;
			$query1 = $dbConnection->prepare("SELECT ProductName,Micron FROM ProductMaster WHERE  DeleteStatus=? AND ProductName=? AND Micron=?");
			$query1->execute(array($delete_status,$NewProductName,$micron));
			if($query1->rowCount()>0)
			{
				$row1 = $query1->fetch();
				$PMicron = $row1['Micron'];
				$PSize = $row1['Size'];
				$PName = $row1['NewProductName'];

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
					$Errors .= "$micron $NewProductName already exists"."\n";	
				//}
				
			 }

	    if($check==1){
		echo $Errors;
		}
		if($check==0)
		{
			//echo "ok";

			$upload_dir = "../ProductImages/";
			//$validextensions = array("jpeg", "jpg", "png", "gif");
			 $temporary = explode(".", $_FILES["file"]["name"]);
			 $file_extension = end($temporary);

			$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
			
			if($_FILES['file']['name']=="")
			{
				$filename1="";	
			}else
			{
				$filename1 = $category."-".$sku.str_replace(' ', '', $_FILES['file']['name']);	
			}
			
			$targetPath = $upload_dir.$filename1; // Target path where file is to be stored


			move_uploaded_file($sourcePath,$targetPath); // Moving Uploaded file

			$query = $dbConnection->prepare("INSERT INTO ProductMaster (ProductId,PkId_Category,PkId_SubCategoryMaster,PkId_Level2SubCategoryMaster,PkId_Level3SubCategoryMaster,ProductName,SKU,Unit,FileName,Size,Micron,Colour,InventoryType,IsItReturnable,Dimensions,Weight,Manufacturer,Brand,UPC,MPN,EAN,ISBN,SalesPrice,PurchasePrice,OpeningStock,OpeningStockRateperUnit,ReorderPoint,TrackingMode,VendorId_VendorMaster,UserId_Users,CreatedTime) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$query->execute(array($productid,$category,$subcategory,$lvl2subcat,$lvl3subcat,$NewProductName,$sku,$unit,$filename1,$productsize,$micron,$colour,$invtype,$isreturn,$dimension,$weight,$manfacture,$brand,$upc,$mpn,$ean,$isbn,$salesprice,$costprice,$openstock,$stockrate,$reorderlevel,$invtrack,$VendorId,$SAId,$DATEIME));
			//	$PkId = $dbConnection->lastInsertId();
			// $query2 = $dbConnection->prepare("SELECT Size FROM SizeMaster WHERE Size=?");
			// $query2->execute(array($productsize));
			// if($query2->rowCount()==0)
			// {
			// 	$query3 = $dbConnection->prepare("INSERT INTO SizeMaster (Size,CreatedTime) VALUES (?,?)");
			//  	$query3->execute(array($productsize,$DATEIME));
			// }
			
			// if($invtrack=="None")
			// {
			// 	$query2 = $dbConnection->prepare("INSERT INTO InventoryMaster (ProductId_ProductMaster,OpeningQuantity,Quantity,CreatedTime) VALUES (?,?,?,?)");
			// 	$query2->execute(array($productid,$openstock,$openstock,$DATEIME));
			// }
			// if($invtrack=="Serial")
			// {
			// 	 $var=explode(',',$serailnumber);
			//    foreach($var as $row)
			//     {
			// 	# code...
			// 	$query2 = $dbConnection->prepare("INSERT INTO InventoryMaster (ProductId_ProductMaster,BatchNoORSrNo,OpeningQuantity,Quantity,CreatedTime) VALUES (?,?,?,?,?)");
			// 	$query2->execute(array($productid,$row,'1','1',$DATEIME));
			// 	}
			// }
			// if($invtrack=="Batch")
			// {
			// 	foreach ($BatchNo as $key => $value) {
			// 	# code...
			// 		$BatchNo1 = $BatchNo[$key];
			// 		$ManfcBatch1 = $ManfcBatch[$key];
			// 		$Mfgdate1 = date('Y-m-d',strtotime($Mfgdate[$key]));
			//  		$Expdate1 = date('Y-m-d',strtotime($Expdate[$key]));
		 // 			$Quantity1 = $Quantity[$key];

			// 	$query2 = $dbConnection->prepare("INSERT INTO InventoryMaster (ProductId_ProductMaster,BatchNoORSrNo,BatchManufacturer,ManfactureDate,ExpireDate,OpeningQuantity,Quantity,CreatedTime) VALUES (?,?,?,?,?,?,?,?)");
			// 	$query2->execute(array($productid,$BatchNo1,$ManfcBatch1,$Mfgdate1,$Expdate1,$Quantity1,$Quantity1,$DATEIME));
			// 	}
			// }
			
			// $InvPkId = $dbConnection->lastInsertId();
			// $query4 = $dbConnection->prepare("INSERT INTO Product_Transaction (TDate,Type,Activity,ReferenceId,ProductId_ProductMaster,Quantity,Opening_Balance,Closing_Balance,CreatedTime) VALUES (?,?,?,?,?,?,?,?,?)");
			// $query4->execute(array($todaydt,'Opening Stock','ADD',$InvPkId,$productid,$openstock,'0',$openstock,$DATEIME));

			$Post = "Created product with (ProductId: $productid,PkId_Category: $category,PkId_SubCategoryMaster: $subcategory,PkId_Level2SubCategoryMaster: $lvl2subcat,PkId_Level3SubCategoryMaster: $lvl3subcat,ProductName: $NewProductName,SKU: $sku,Unit: $unit,FileName: $filename1,Size: $productsize,Micron: $micron,Colour: $colour,InventoryType: $invtype,IsItReturnable: $isreturn,Dimensions: $dimension,Weight: $weight,Manufacturer: $manfacture,Brand: $brand,UPC: $upc,MPN: $mpn,EAN: $ean,ISBN: $isbn,SalesPrice: $salesprice,PurchasePrice: $costprice,OpeningStock: $openstock,OpeningStockRateperUnit: $stockrate,ReorderPoint: $reorderlevel,TrackingMode: $invtrack,VendorId_VendorMaster: $VendorId) as on $DATEIME by $SAId";
			$Type="Product";		
			$query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
			$query2->execute(array($SAId,$Post,$Type,$productid,$DATEIME));

			echo $result= "Success";
		}
					
	// }
	// else
	// {
	// 	echo $Errors = "Enter Mandatory Fields";
		
	// }
}	 
?>
