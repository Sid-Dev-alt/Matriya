<?php
// include "../../CommonUtilities/Connections.php";
// 			include_once "../../CommonUtilities/Functions.php";
// $query2 = $dbConnection->prepare("SELECT RollNo FROM RawProductionDetails WHERE TDate=? AND DeleteStatus=? ORDER BY pkid DESC LIMIT 0,1");
// 						$query2->execute(array($printdate,$delete_status));
						// if($query2->rowCount()>0)
						// {
						// 	$row2 = $query2->fetch();
			$row2['RollNo'] = "99999";
							 $variable = $row2['RollNo'] + 1;
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
							}
							echo $RollNo = $variable;
						// }
						// else
						// {
						// 	$RollNo = "001";
						// }
						?>