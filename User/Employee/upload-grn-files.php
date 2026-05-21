<?php 
error_reporting(0);
    $GRNId=$_REQUEST['GRNId'];
//echo $_FILES["file"]["name"];
    $upload_dir = "../GRNFiles/";
    //while(true){
        if(isset($_FILES["file"]["type"]))
        {
            $data = array();
            //$validextensions = array("jpeg", "jpg", "png", "gif");
            $temporary = explode(".", $_FILES["file"]["name"]);
            $file_extension = end($temporary);
            /*if ((($_FILES["file".$i]["type"] == "image/png") || ($_FILES["file".$i]["type"] == "image/jpg") || ($_FILES["file".$i]["type"] == "image/gif") || ($_FILES["file".$i]["type"] == "image/jpeg")) && in_array($file_extension, $validextensions)) {
                if ($_FILES["file".$i]["error"] > 0){
                    echo "Return Code: " . $_FILES["file".$i]["error"] . "<br/><br/>";
                } else {
                    if (file_exists($upload_dir.$_FILES["file".$i]["name"])) {                
                        echo 'file already exist';
                    } else {*/
                        $sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
                        $filename = $GRNId."-".rand().$_FILES['file']['name'];
                        $targetPath = $upload_dir.$filename; // Target path where file is to be stored
                        move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
                        //echo 'success';
                        //array_push($data, 'Success');
                    /*}
                }
            } */
            echo $filename;
            //array_push($data, $targetPath);
            //echo json_encode($data);
        }
        else{
            //break;
        }
   // }
    // $paths = trim($paths, ", ,");
    // echo $paths;
?>