<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{	
	if($_SERVER["REQUEST_METHOD"]==="POST")
	{
			$data = json_decode(file_get_contents("php://input")); 
			$FormPkId = $data->FormPkId;
			$ContactName = $data->ContactName;
			$ContactPhone = $data->ContactPhone;
			$ContactEmailId = $data->ContactEmailId;

			if($FormPkId!="" && $ContactPhone!=""  && $ContactName!="" && $ContactEmailId!="")
			{

				include "../../CommonUtilities/Connections.php";
				include_once "../../CommonUtilities/Functions.php";
				
				$SAId = $_SESSION['UserId'];
				$From = $_SESSION['AppName'];
				$AppURL = $_SESSION['AppURL'];
				$FromId = $_SESSION['FromEmailId'];
				$PrimaryEmailId = $_SESSION['PrimaryEmailId'];
				$Cc = $_SESSION['Cc'];
				$Bcc = $_SESSION['Bcc'];
				$DATEIME = GetDateTime();	

				$sql = $dbConnection->prepare("SELECT UserId FROM Users ORDER BY PkId DESC ") ;
				$sql->execute();
				$rowCount = $sql->rowCount();
				if($rowCount>0)
				{
					$row = $sql->fetch();	
				 	$value = substr($row['UserId'],3);
					$variable = $value + 1;
					$length = strlen($variable);
					if(strlen($variable)<3)
					{
						switch($length)
						{
							case 2:
							$variable = "0".$variable;
							break;
							case 1:
							$variable = "00".$variable;
							break;
						}
						$UserId = substr($row['UserId'],0,3).$variable;
					}
					else
					{
					 $UserId = substr( $row['UserId'],0,3).$variable;
					}
				}
				else
				{
					 $UserId = "SCU101";
				}

				$query1 = $dbConnection->prepare("UPDATE VendorRegistrations SET Status=? WHERE PkId=?");
				$query1->execute(array('1',$FormPkId));

				$query = $dbConnection->prepare("INSERT INTO Users (UserId,RoleId_Roles,Name,EmailId,MobileNo,CreatedTime) VALUES (?,?,?,?,?,?)");
				$query->execute(array($UserId,'2',$ContactName,$ContactEmailId,$ContactPhone,$DATEIME));

				$PkId = $dbConnection->lastInsertId();

				$newpwd = uniqid(rand(), true);	
				$fnewpwd = substr($newpwd, 0, 8);			
				$hash = hash('sha256', $fnewpwd);					
				$md5 = md5(uniqid(rand(), TRUE));					
				$salt = substr($md5, 0, 3);					
				$enc_pwd = hash('sha256', $salt . $hash);
								
				$log = $dbConnection->prepare("INSERT INTO Parolni (UserId_Users,ParolString,TuzString,CreatedTime) VALUES(?,?,?,?)");
				$log->execute(array($UserId,$enc_pwd,$salt,$DATEIME));

				/*Sent Email start */
				$subject = "Your request was approved [$From]";
				$message = "Dear $ContactName,<p>Your registarion was approved by $From.</p><p>Your login details are below</p><p>UserName: $ContactEmailId</p><p>Password: $fnewpwd</p>\n\nTo Login with your password, visit the following address:\n\n[ $AppURL ]<p>Regards,<br>Support Team,<br>$From</p>";	
				$headers = "Reply-To: $From <$FromId>\r\n";
				$headers .= "Return-Path: $From <$FromId>\r\n";
				$headers .= "From: $From <$FromId>\r\n";
				$headers .= "Organization: $From\r\n";
				$headers .= "Content-Type: text/html\r\n";
				$headers .= "X-Sender: <$FromId>\n";
				$headers .= "X-Mailer: PHP\n"; // mailer
				$headers .= "X-Priority: 1\n"; //1 Urgent Message, 3 Normal
				//$headers .= "Bcc:$BCC"."\n";
				//$headers .= "Cc:$Cc\n";
				
				mail($ContactEmailId,$subject,$message,$headers);
				/*Sent Email end */
				// 	$studentmail = [
				//     'FromEmail' => $FromId,
				//     'FromName' => $From,
				//     'Subject' => "Dear $customername, $From added as a Customer",
				//     'Html-part' => "<!DOCTYPE html> <html> <head> <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'> <meta name='viewport' content='width=device-width, initial-scale=1.0'> <title>My First Email</title> <style type='text/css'> @media(max-width:480px){table[class=main_table],table[class=layout_table]{width:300px !important;}table[class=layout_table] tbody tr td.header_image img{width:300px !important;height:auto !important;}}a{color:#37aadc}table p{font-family:Arial, Helvetica, sans-serif;font-size:16px;line-height:22px;text-align:justify;}</style> </head> <body> <table border='0' cellpadding='0' cellspacing='0' width='100%' style='background:#F7F7F7;padding: 25px;'> <tbody><tr>&nbsp;</tr></tr><tr> <td align='center' valign='top'> <table border='0' cellpadding='0' cellspacing='0' class='main_table' width='650'> <tbody> <tr> <td> <table border='0' cellpadding='0' cellspacing='0' class='layout_table' style='border-radius: 5px;border:1px solid #CCCCCC;background: #ffffff;' width='100%' > <tbody> <tr><td style='font-size:13px;line-height:13px;margin:0;padding:0;'>&nbsp;</td></tr><tr> <td align='center' valign='top'> <table align='center' border='0' cellpadding='0' cellspacing='0' width='85%'> <tbody> <tr> <td align='left'> <p>Dear $username,<p><p>$From created as a Customer.</p><p>Below are the your login customername.</p></td></tr><tr><td><p>UserName: <strong>$emailid</strong></p><p>Password: <strong>$fnewpwd</strong></p></td></tr><tr><td><p>Click below to Login into your account.</p></td></tr><tr><td><span style='padding:10px;background-color: #00A859;font-size:15px;'><a class='btn' style='color:#fff;text-decoration:none;' href='$AppURL' target='_blank'>LOGIN</a></span></td></tr></tbody> </table> </td></tr><tr><td>&nbsp;</td></tr></tbody> </table> </td></tr></tbody> </table> </td></tr></tbody> </table> </body> </html>",
				//     'Recipients' => [
				// 	[
				// 	    'Email' => $emailid,
				// 	]
				//     ]
				// ];
			//$response = $mailjet->post(Mailjet\Resources::$Email, ['body' => $studentmail]);
		
				$Post = "$SAId approved vendor registarion (Id: $FormPkId) as on $DATEIME";
						
				$Type="Registarion Approved";		
				$query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
				$query2->execute(array($SAId,$Post,$Type,$FormPkId,$DATEIME));
						
					$result= "Success";
					echo $result;
			}
			else
			{
				$result= "Enter mandatory fields";
				echo $result;
			}
		
	}
	else
	{
		$result= "Invalid Data";
		echo $result;
	}
	
}
?>