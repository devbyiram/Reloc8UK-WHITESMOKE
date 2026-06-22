<?php

require("system/config.php");

if(isset($_GET['action']) && $_GET['action'] == "fetch"){
	global $pdo;
	
	$pid = $_GET['pid'];
	
	$stmt = $pdo->prepare("SELECT * FROM `property_images` WHERE `property_id` = ?");
	
	$stmt->execute([$pid]);
	
	$result = $stmt->fetchAll();
	
	$number_of_rows = $stmt->rowCount();
	$output = '';
	$output .= '
	 <table class="table table-bordered table-striped">
	  <tr>
	   <th>ID</th>
	   <th>Image</th>
	   <th>Name</th>
	   <th>Order</th>
	   <th>Actions</th>
	  </tr>
	';
	if($number_of_rows > 0)
	{
	 $count = 0;
	 foreach($result as $row)
	 {
	  $count ++; 
	  $output .= '
	  <tr>
	   <td>'.$row["id"].'</td>
	   <td><img src="assets/property_images/'.$row["filename"].'" class="img-thumbnail" width="100" height="100" /></td>
	   <td>'.$row["filename"].'</td>
	   <td><input type="text" name="order" value="'.$row["order"].'"></td>
	   <td><button type="button" class="btn btn-danger btn-xs delete" id="'.$row["id"].'" data-image_name="'.$row["filename"].'">Delete</button></td>
	  </tr>
	  ';
	 }
	}
	else
	{
	 $output .= '
	  <tr>
	   <td colspan="6" align="center">No Data Found</td>
	  </tr>
	 ';
	}
	$output .= '</table>';
	echo $output;
	
}

if(isset($_GET['action']) && $_GET['action'] == "delete"){
	global $pdo;
	
	$id = $_POST['image_id'];
	
	$sql = "DELETE FROM property_images WHERE id = '".$id."'";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute();
	
	return true;
}

if(isset($_FILES["file"]["name"]) && count($_FILES["file"]["name"]) > 0){
	global $pdo;
	
	sleep(3);
	for($count=0; $count<count($_FILES["file"]["name"]); $count++)
	{
		$file_name = $_FILES["file"]["name"][$count];
		$file_name = str_replace(' ','',$file_name);
		$tmp_name = $_FILES["file"]['tmp_name'][$count];
		$file_array = explode(".", $file_name);
		$file_extension = end($file_array);
		$file_name = $file_array[0] . '-'. rand() . '.' . $file_extension;

		$location = 'assets/property_images/' . $file_name;
		
		if(move_uploaded_file($tmp_name, $location))
		{
			correctImageOrientation($location);
			
			$query = "INSERT INTO property_images (property_id,filename) 
			VALUES ( '".$_GET['pid']."','".$file_name."')
			";
			$statement = $pdo->prepare($query);
			$statement->execute();
		}
	}
	
}

function file_already_uploaded($file_name, $connect){
	global $pdo;
	$query = "SELECT * FROM property_images WHERE filename = '".$file_name."'";
	$statement = $pdo->prepare($query);
	$statement->execute();
	$number_of_rows = $statement->rowCount();
	if($number_of_rows > 0){
		return true;
	}else{
		return false;
	}
}

if(isset($_POST['uploadVideo']) && $_POST['uploadVideo'] == 1){
	
	global $pdo;
	
	$pid = $_POST['pid'];
	
	$video = $_FILES['propertyVideo']['name'];
	$video = str_replace(' ','',$video);
	$video = time() . "-" . $video;
	$tmpVideo = $_FILES['propertyVideo']['tmp_name'];
	
	$allowedExts = array("mp4","avi");
	$extension = pathinfo($video, PATHINFO_EXTENSION);
	
	$location = "assets/property_videos/".$video;
	
	if (($_FILES["propertyVideo"]["type"] == "video/mp4" || $_FILES["propertyVideo"]["type"] == "video/avi")
		&& ($_FILES["propertyVideo"]["size"] < 20000000) && in_array($extension, $allowedExts)) {
		if ($_FILES["propertyVideo"]["error"] > 0){
			echo "Return Code: " . $_FILES["propertyVideo"]["error"] . "<br />";
		}else{
			if (file_exists("assets/property_videos/" . $video)){
			  echo $_FILES["propertyVideo"]["name"] . " already exists. ";
			}else{
			  if(move_uploaded_file($tmpVideo,$location)){
					$query = "INSERT INTO property_videos (property_id,filename) VALUES ( '$pid','$video')";
					$statement = $pdo->prepare($query);
					if($statement->execute()){
						header('Location: admin-edit-property.php?id='.$pid);
					}
				}else{
					echo "Error moving file";
				}
			}
		}
	}else{
		echo "Invalid file";
	}

}

if(!empty($_GET['action']) && $_GET['action'] == "deleteVideo"){
	
	global $pdo;
	
	$sql = "DELETE FROM property_videos WHERE id=?";
	
	$stmt = $pdo->prepare($sql);
	
	if($stmt->execute([$_GET['vid']])){
		header('Location: admin-edit-property.php?id='.$_GET['pid']);
	}	
}
?>