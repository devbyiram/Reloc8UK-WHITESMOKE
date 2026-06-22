<?php

require("system/config.php");

if(isset($_POST['uploadFile']) && $_POST['uploadFile'] == 1){
	
	global $pdo;
	
	$filetype = $_POST['file_type'];
	$pid = $_POST['pid'];

	$file_name = $_FILES["property_files"]["name"];
	$file_name = str_replace(' ','',$file_name);
	$tmp_name = $_FILES["property_files"]['tmp_name'];
	$file_array = explode(".", $file_name);
	$file_extension = end($file_array);
	$file_name = $file_array[0] . '-'. rand() . '.' . $file_extension;

	$location = 'assets/property_files/' . $file_name;
	
	if(move_uploaded_file($tmp_name, $location))
	{
		
		$query = "INSERT INTO property_files (property_id,filename, filetype) 
		VALUES ( '".$pid."','".$file_name."','".$filetype."')
		";

		$statement = $pdo->prepare($query);
		$result = $statement->execute();
		
		if($result){
			header('Location: admin-edit-property.php?id='.$pid);
		}
	}

}

if(isset($_GET['action']) && $_GET['action'] == "delete"){
	
	$sql = "DELETE FROM property_files WHERE id=?";
	
	$stmt = $pdo->prepare($sql);
	
	if($stmt->execute([$_GET['fid']])){
		header('Location: admin-edit-property.php?id='.$_GET['pid'].'#propertyFiles');
	}
	
}

?>