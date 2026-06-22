<?php

$propertyStatus = array(
	0	=>	"Available",
	1 	=> 	"Reserved",
	2 	=>	"Held",
	3	=>	"Under Offer",
	4	=>	"Let"
);

$propertyTypes = array(
	1	=>	"Terrace",
	2	=>	"End Terrace",
	3	=>	"Detached",
	4	=>	"Semi-detached",
	5	=>	"Flat",
	6	=>	"Bungalow",
	7	=>	"Maisonette"
);

function getLatestProperties($count=8){
	global $pdo;
	// Retrieve last 5 properties added to the website.
	$sql = "SELECT properties.*,GROUP_CONCAT(property_images.filename) AS images FROM properties LEFT JOIN property_images ON properties.id = property_images.property_id WHERE properties.soft_deleted = '0000-00-00 00:00:00' GROUP BY properties.id ORDER BY properties.listed DESC LIMIT $count";
	
	$stmt = $pdo->prepare($sql);

	//Execute.
	$stmt->execute();

	//Fetch row.
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProperty($id){
	
	global $pdo;
	
	$sql = "SELECT p.*, GROUP_CONCAT(pi.filename) AS images,pv.filename, pv.id AS vid FROM properties p LEFT JOIN property_images pi ON (p.id = pi.property_id) LEFT JOIN property_videos pv ON (p.id = pv.property_id) WHERE p.id = :id";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->bindValue(':id',$id);
	
	$stmt->execute();
	
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getAllProperties($status=null,$max_rent=null,$min_bedrooms=null,$furniture_included=null,$same_day_move=null,$page=1,$per_page=20,$order="desc"){

	global $pdo;
	
		$limit_start = ($page - 1) * $per_page;
	
	// First check if any of the attributes are not null, so we can start the $where var
	if(isset($max_rent) || isset($min_bedrooms) || isset($furniture_included) || isset($same_day_move) || isset($status) || $_SESSION['user_type'] == '1'){
		$where = "WHERE listed < NOW()";
	}
	// Check if $status is not null, if so add to the $where var
	if($status != ""){
    		switch($status){
    			case "available":
    				$status = "0";
    				break;
    			case "reserved":
    				$status = "1";
    				break;
    			case "held":
    				$status = "2";
    				break;
    			case "underoffer":
    				$status = "3";
    				break;
    			case "let":
    				$status = "4";
    				break;
    			default:
    				$status = "0";
    				break;
    		}
        $where .= " AND status = ".$status;
	}
	
	// Check if $rent_pcm is not null, if so add to the $where var
	if(isset($max_rent) && $max_rent != ""){
		$where .= " AND rent_pcm <= '$max_rent'";
	}
	// Check if $min_bedrooms is not null, if so add to the $where var
	if(isset($min_bedrooms) && $min_bedrooms != ""){
	    
	    if(count($min_bedrooms) > 0){
	        $where .= ' AND bedrooms IN (' . implode(',', $min_bedrooms) . ')';
	        //$where .= " AND (";
	        //foreach($min_bedrooms as $minbed){
    	    //    $where .= " bedrooms = '$minbed' OR";
    	    //}
    	    //$where .= ")";
	    }
	    
		
	}
	// Check if $furniture_included is not null, if so add to the $where var
	if(isset($furniture_included) && $furniture_included != 0){
		$where .= " AND furniture_included = '1'";
	}
	// Check if $same_day_move is not null, if so add to the $where var
	if(isset($same_day_move) && $same_day_move != 0){
		$where .= " AND same_day_move = '1'";
	}
	
	$where .= " AND soft_deleted = '0000-00-00 00:00:00'";
	
	$sql = "SELECT properties.*,GROUP_CONCAT(property_images.filename) AS images FROM properties LEFT JOIN property_images ON properties.id = property_images.property_id $where GROUP BY properties.id ORDER BY properties.status ASC, properties.listed $order, property_images.id ASC  LIMIT $limit_start, $per_page";
    
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute();
	
	$results = array();
	
	$results["properties"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$results["totalCount"] = totalPropertyCount("SELECT properties.*,GROUP_CONCAT(property_images.filename) AS images FROM properties LEFT JOIN property_images ON properties.id = property_images.property_id $where GROUP BY properties.id ORDER BY properties.listed, property_images.id $order");
	
	return $results;
}

function getAllPropertiesAdmin($status=null,$max_rent=null,$min_bedrooms=null,$furniture_included=null,$same_day_move=null,$page=1,$per_page=20,$order="desc"){

	global $pdo;
	
	
	// First check if any of the attributes are not null, so we can start the $where var
	if(isset($max_rent) || isset($min_bedrooms) || isset($furniture_included) || isset($same_day_move) || isset($status) || $_SESSION['user_type'] == '1'){
		$where = "WHERE listed < NOW()";
	}
	// Check if $status is not null, if so add to the $where var
	if($status != ""){
    		switch($status){
    			case "available":
    				$status = "0";
    				break;
    			case "reserved":
    				$status = "1";
    				break;
    			case "held":
    				$status = "2";
    				break;
    			case "underoffer":
    				$status = "3";
    				break;
    			case "let":
    				$status = "4";
    				break;
    			default:
    				$status = "0";
    				break;
    		}
        $where .= " AND status = ".$status;
	}
	
	// Check if $rent_pcm is not null, if so add to the $where var
	if(isset($max_rent) && $max_rent != ""){
		$where .= " AND rent_pcm <= '$max_rent'";
	}
	// Check if $min_bedrooms is not null, if so add to the $where var
	if(isset($min_bedrooms) && $min_bedrooms != ""){
	    
	    if(count($min_bedrooms) > 0){
	        $where .= ' AND bedrooms IN (' . implode(',', $min_bedrooms) . ')';
	        //$where .= " AND (";
	        //foreach($min_bedrooms as $minbed){
    	    //    $where .= " bedrooms = '$minbed' OR";
    	    //}
    	    //$where .= ")";
	    }
	    
		
	}
	// Check if $furniture_included is not null, if so add to the $where var
	if(isset($furniture_included) && $furniture_included != 0){
		$where .= " AND furniture_included = '1'";
	}
	// Check if $same_day_move is not null, if so add to the $where var
	if(isset($same_day_move) && $same_day_move != 0){
		$where .= " AND same_day_move = '1'";
	}
	
	$where .= " AND soft_deleted = '0000-00-00 00:00:00'";
	
	$sql = "SELECT properties.*,GROUP_CONCAT(property_images.filename) AS images FROM properties LEFT JOIN property_images ON properties.id = property_images.property_id $where GROUP BY properties.id ORDER BY properties.listed $order, property_images.id ASC";
    
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute();
	
	$results = array();
	
	$results["properties"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$results["totalCount"] = totalPropertyCount("SELECT properties.*,GROUP_CONCAT(property_images.filename) AS images FROM properties LEFT JOIN property_images ON properties.id = property_images.property_id $where GROUP BY properties.id ORDER BY properties.listed, property_images.id $order");
	
	return $results;
}

function totalPropertyCount($sql){
    global $pdo;
    
    $sql = $sql;
    
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute();
    
    return $stmt->rowCount();
}
function getFeaturedProperty(){
	
	global $pdo;
	
	$sql = "SELECT p.*, GROUP_CONCAT(pi.filename) AS images FROM properties p LEFT JOIN property_images pi ON (p.id = pi.property_id) WHERE p.is_featured = '1' ORDER BY RAND()";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute();
	
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updatePropertyStatus($pid,$uid,$status,$notes=null,$amount=null){
	
	global $pdo,$propertyStatus;
	
	$sql = "UPDATE properties SET status = '".$status."' WHERE id = '".$pid."'";
	
	$stmt	=	$pdo->prepare($sql);
	
	$stmt->execute();
	
	$sql = "INSERT INTO property_council (property_id, user_id, type, bid_notes) VALUES(?,?,?,?)";
	
	$stmt	=	$pdo->prepare($sql);
	
	$bid = $stmt->execute([$pid,$uid,$status,$notes]);
	
	return $pdo->lastInsertId();
}

function deleteProperty($pid){
	global $pdo;
	
	$sql = "UPDATE properties SET `soft_deleted` = NOW() WHERE id=?";
	
	$stmt = $pdo->prepare($sql);
	
	if($stmt->execute([$pid])){
		return true;
	}else{
		return false;
	}
}

function deletePropertyBids($pid){
	global $pdo;
	
	$sql = "DELETE FROM property_council WHERE property_id=?";
	
	$stmt = $pdo->prepare($sql);
	
	if($stmt->execute([$pid])){
		return true;
	}else{
		return false;
	}
}

function getPropertyFiles($pid){
	
	global $pdo;
	
	$sql = "SELECT * FROM property_files WHERE property_id = ?";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute([$pid]);
	
	if($results = $stmt->fetchAll(PDO::FETCH_ASSOC)){
		foreach($results as $result){
			$output = '<div class="col text-center">';
			$output .= '<a href="/assets/property_files/'.$result['filename'].'" target="_blank">';
			switch ($result['filetype']){
				case "EPC":
					$title = "Property EPC";
					break;
				case "Electric Cert":
					$title = "Electrical Cert";
					break;
				case "Gas Safety":
					$title = "Gas Safety Cert";
					break;
				case "Brochure":
				    $title = "Property Brochure";
				    break;
				default:
					$title = "Other Document";
					break;
			}
			$output .= '<img src="assets/img/pdf.png" width="64" class="img-fluid d-block mx-auto">';
			$output .= '<h6 class="text-center">'.$title.'</h6>';
			$output .= '</a>';
			$output .= '</div>';
			
			echo $output;
		}
	}
}

function adminPropertyFiles($pid){
	
	global $pdo;
	
	$sql = "SELECT * FROM property_files WHERE property_id = ?";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute([$pid]);
	
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	foreach($results as $result){
		?>
		<tr>
			<td><?php echo $result['id']; ?></td>
			<td><?php echo $result['filename']; ?></td>
			<td><?php echo $result['filetype']; ?></td>
			<td><a href="admin-manage-files.php?action=delete&pid=<?php echo $pid; ?>&fid=<?php echo $result['id']; ?>" class="btn btn-danger">Delete</a></td>
		</tr>
	<?php
	}
}

function notifyUserHoldApplication($bid){
	global $pdo;

	$sql = "SELECT property_council.*,users.email_address,users.name,properties.address1,properties.address2,properties.town FROM property_council LEFT JOIN users ON users.id = property_council.user_id LEFT JOIN properties ON properties.id = property_council.property_id WHERE property_council.id='$bid'";

	$stmt = $pdo->prepare($sql);

	$stmt->execute();

	$result = $stmt->fetch();

	$template = file_get_contents("views/emails/hold-submitted.php");
	
	$template = str_replace('{{pid}}',$result['property_id'],$template);
	$template = str_replace('{{name}}',$result['name'],$template);
	$template = str_replace('{{address}}',$result['address1'].', '.$result['address2'].', '.$result['town'],$template);

	$message = $template;
	
	$to = $result['email_address'];
	$subject = "Property Hold Submitted";   
	$from = "Reloc8UK Portal <info@reloc8uk.co.uk>";  
	$headers = "From: $from \r\n";  
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
	$mail = mail($to,$subject,$message,$headers);  
	
	if($mail){
		return true;
	}else{
		return false;
	}

}

function notifyUserReserved($bid){
	
	global $pdo;
	
	$sql = "SELECT property_council.*,users.email_address,users.name,properties.address1,properties.address2,properties.town FROM property_council LEFT JOIN users ON users.id = property_council.user_id LEFT JOIN properties ON properties.id = property_council.property_id WHERE property_council.id='$bid'";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute();
	
	$result = $stmt->fetch();
	
	if($result['type']==2){
		$template = file_get_contents("views/emails/bid-approved-hold.php");
	}else{
		$template = file_get_contents("views/emails/bid-approved.php");
	}
	
	
	$template = str_replace('{{pid}}',$result['property_id'],$template);
	$template = str_replace('{{name}}',$result['name'],$template);
	$template = str_replace('{{address}}',$result['address1'].', '.$result['address2'].', '.$result['town'],$template);
	$template = str_replace('{{date}}',date('d/m/Y H:i', strtotime($result['finalised'])),$template);

	$message = $template;
	
	$to = $result['email_address'];
	$subject = "Property Bid Accepted";   
	$from = "Reloc8UK Portal <info@reloc8uk.co.uk>";  
	$headers = "From: $from \r\n";  
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
	$mail = mail($to,$subject,$message,$headers);  
	
	if($mail){
		return true;
	}else{
		return false;
	}
}

function notifyCouncilPropertyStatus($pid,$status='let'){
	global $pdo;

	$sql = "SELECT property_council.*,users.email_address,users.name,properties.address1,properties.address2,properties.town FROM property_council LEFT JOIN users ON users.id = property_council.user_id LEFT JOIN properties ON properties.id = property_council.property_id WHERE property_council.property_id='$pid' AND property_council.status='5'";

	$stmt = $pdo->prepare($sql);
	
	$stmt->execute();
	
	$result = $stmt->fetch();
	
	$template = file_get_contents("views/emails/property-let.php");
	
	$template = str_replace('{{pid}}',$result['property_id'],$template);
	$template = str_replace('{{name}}',$result['name'],$template);
	$template = str_replace('{{address}}',$result['address1'].', '.$result['address2'].', '.$result['town'],$template);

	$message = $template;
	
	$to = $result['email_address'];
	$subject = "Property Now Let [#".$result['property_id']."]";   
	$from = "Reloc8UK Portal <info@reloc8uk.co.uk>";  
	$headers = "From: $from \r\n";  
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
	$mail = mail($to,$subject,$message,$headers);  
	
	if($mail){
		return true;
	}else{
		return false;
	}

}

function notifyUserRejected($bid){
	
	global $pdo;
	
	$sql = "SELECT property_council.*,users.email_address,users.name,properties.address1,properties.address2,properties.town FROM property_council LEFT JOIN users ON users.id = property_council.user_id LEFT JOIN properties ON properties.id = property_council.property_id WHERE property_council.id='$bid'";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute();
	
	$result = $stmt->fetch();
	
	$template = file_get_contents("views/emails/bid-rejected.php");
	
	$template = str_replace('{{pid}}',$result['property_id'],$template);
	$template = str_replace('{{name}}',$result['name'],$template);
	$template = str_replace('{{address}}',$result['address1'].', '.$result['address2'].', '.$result['town'],$template);

	$message = $template;
	
	$to = $result['email_address'];
	$subject = "Property Bid Rejected";   
	$from = "Reloc8UK Portal <info@reloc8uk.co.uk>";  
	$headers = "From: $from \r\n";  
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
	$mail = mail($to,$subject,$message,$headers);  
	
	if($mail){
		return true;
	}else{
		return false;
	}
}

function managePropertyBids($pid,$bid,$action){

	global $pdo;

	$stmt = $pdo->prepare("SELECT * FROM property_council WHERE property_id=?");

	$stmt->execute([$pid]);

	$results = $stmt->fetchAll();

	foreach($results as $result){
		if($result['id'] == $bid && $action == "accept"){
			$status = 5;
			$notifyTemplate = "accept";
		}else{
			$status = 4;
			$notifyTemplate = "reject";
		}

		$stmt= $pdo->prepare("UPDATE property_council SET status=?, finalised=? WHERE id=?");
		$upd = $stmt->execute([$status,date('Y-m-d H:i:s'),$result['id']]);

		if($upd){
			$status == 5 ? notifyUserReserved($result['id']) : notifyUserRejected($result['id']);
		}
	}
	return true;
}

function getPropertyVideo($pid){
	
	global $pdo;
	
	$sql = "SELECT * FROM property_videos WHERE property_id=? ORDER BY id DESC LIMIT 1";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute([$pid]);
	
	$result = $stmt->fetch();
	
	if(!empty($result)){
?>
<div align="center" class="embed-responsive embed-responsive-16by9 p-3">
    <video loop controls class="embed-responsive-item">
        <source src="assets/property_videos/<?php echo $result['filename']; ?>" type="video/mp4">
    </video>
</div>
<?php
	}else{
?>
	<i>No video for this property</i>
<?php 
	}
}

function insertPropertyBid($pid,$uid,$status,$notes=null){
	global $pdo;
	
	$sql = "INSERT INTO property_council (property_id,user_id,type,bid_notes) VALUES(?,?,?,?)";
	
	$stmt = $pdo->prepare($sql);
	
	if($stmt->execute([$pid,$uid,$status,$notes])){
		return $pdo->lastInsertId();
	}else{
		return 0;
	}
}

function checkExpiredPropertyBids(){
    global $pdo;
    
    $sql = "SELECT * FROM property_council WHERE created >= now() - INTERVAL 1 DAY";
    
    $stmt = $pdo->prepare($sql);
	
	$stmt->execute();
	
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	foreach($results as $result){
	    
	    $updateSql = "UPDATE properties SET status = '0' WHERE id = '".$result['property_id']."'";
	    
	    $updateStmt = $pdo->prepare($sql);
	    
	    $updateStmt->execute();
	    
	}
    
}