<?php
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function getUserBids($user_id = null){
	
	// Global DB Connection
	global $pdo;
	
	// Generate SQL query
	$sql = "SELECT property_council.*,properties.address1,properties.town, properties.postcode, properties.rent_pcm FROM property_council LEFT JOIN properties ON property_council.property_id = properties.id WHERE user_id = :user_id";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->bindValue(':user_id',$user_id);
	
	$stmt->execute();
	
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllBids(){
	
	global $pdo;
	
	$sql = "SELECT property_council.*, properties.address1, properties.address2, properties.town, users.username, users.name FROM property_council LEFT JOIN properties ON property_council.property_id = properties.id LEFT JOIN users ON property_council.user_id = users.id ORDER BY property_council.created DESC";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute();
	
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUsers(){
	
	// Global DB Connection
	global $pdo;
	
	// Generate SQL query
	$sql = "SELECT * FROM users ORDER BY `last_login` DESC";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute();
	
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUser($id){
	
	global $pdo;
	
	$sql = "SELECT * FROM users WHERE `id`=?";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute([$id]);
	
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getAllCouncils(){
	// Global DB Connection
	global $pdo;
	
	// Generate SQL Query
	$sql = "SELECT * FROM councils ORDER BY council_name ASC";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute();
	
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function hasReservedBefore($user_id=null,$property_id=null){
	global $pdo;
	
	$sql = "SELECT * FROM property_council WHERE property_id = '".$property_id."' AND user_id = '".$user_id."' AND type = '1'";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute();
	
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function hasHeldBefore($user_id=null,$property_id=null){
	global $pdo;
	
	$sql = "SELECT * FROM property_council WHERE property_id = '".$property_id."' AND user_id = '".$user_id."' AND type = '2'";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute();
	
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function notifyAdmin($type=null,$user=null,$property=null,$note=null){
	
	global $pdo;
	
	$sql = "SELECT * FROM users WHERE user_type = '2'";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute();
	
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	switch ($type){
		case "reserved":
			$subject = "New Property Reservation";
			$template = "reserved.php";
			break;
		case "held":
			$subject = "New Property Hold";
			$template = "hold.php";
			break;
		case "offer":
			$subject = "New Property Offer";
			$template = "offer.php";
			break;
		default:
			$subject = "New Property Reservation";
			$template = "reserved.php";
			break;
	}
	
	
	
	$template = file_get_contents("views/emails/".$template);
	
	$template = str_replace('{{pid}}',$property['id'],$template);

	$template = str_replace('{{propadd}}',$property['address1'],$template);

	$template = str_replace('{{username}}', $user['name'], $template);
	
	$template = str_replace('{{propertyAddress}}',$property['address1'] . ", " . $property['town'],$template);
	
	$template = str_replace('{{uid}}',$user['id'],$template);
	
	$template = str_replace('{{username}}',$user['username'],$template);
	
	$template = str_replace('{{note}}',$note,$template);

	$message = $template;
	
	$to = "info@reloc8uk.co.uk";
	$subject = $subject;   
	$from = "Reloc8UK Portal <info@reloc8uk.co.uk>";  
	$headers = "From: $from \r\n";  
	foreach($results as $result){
		$headers .= "Bcc: ".$result['email_address']."\r\n";
	}
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
	$mail = mail($to,$subject,$message,$headers);  
	
	if($mail){
		return true;
	}else{
		return false;
	}
}

function checkEmailExists($email=null){
	
	global $pdo;
	
	$sql = "SELECT * FROM users WHERE email_address = '".$email."'";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute();
	
	$rows = $stmt->fetchAll();
	
	if(count($rows) > 0){
		return true;
	}else{
		return false;
	}
}

function generate_username($string_name="Mike Tyson", $rand_no = 200){
	$username_parts = array_filter(explode(" ", strtolower($string_name))); //explode and lowercase name
	$username_parts = array_slice($username_parts, 0, 2); //return only first two arry part

	$part1 = (!empty($username_parts[0]))?substr($username_parts[0], 0,8):""; //cut first name to 8 letters
	$part2 = (!empty($username_parts[1]))?substr($username_parts[1], 0,5):""; //cut second name to 5 letters
	$part3 = ($rand_no)?rand(0, $rand_no):"";
	
	$username = $part1. str_shuffle($part2). $part3; //str_shuffle to randomly shuffle all characters 
	return $username;
}

function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function hashPassword($password){
	
	$hash = password_hash($password,PASSWORD_DEFAULT);
	
	return $hash;
}

function userExists($email){

	global $pdo;
	
	$sql = "SELECT id FROM users WHERE email_address = '".$email."'";

	$result = $pdo->query($sql)->fetch();
	
	if($result){
		return $result;
	}else{
		return false;
	}
}

function updateUserPassword($uid,$pw){
	
	global $pdo;
	
	$sql = "UPDATE users SET password = '".$pw."' WHERE id = '".$uid."'";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute();
	
}


function sendUserNewPassword($uid,$pw){
	
	global $pdo;
	
	$sql = "SELECT * FROM users WHERE id = '".$uid."'";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute();
	
	$result = $stmt->fetch();
	
	$template = file_get_contents("views/emails/forgot-password.php");
	
	$template = str_replace('{{name}}',$result['name'],$template);
	
	$template = str_replace('{{username}}',$result['username'],$template);
	
	$template = str_replace('{{newPassword}}',$pw,$template);

	$message = $template;
	
	$to = $result['email_address'];  
	$subject = "New Password";    
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

function lookuppostcode($postcode) {
    $postcode = str_replace(" ", "", $postcode);
	$postcode = strtoupper($postcode);
    $key = 'lQT30nDJI0edlwoTuJUevA37690';
    $request = 'https://api.getAddress.io/find/' . $postcode . '?api-key=' . $key . '&sort=true&expand=true&format=true';
    $response = file_get_contents($request);
	$response = json_decode($response,true);
	
    $returnData = array();

    $returnData['totalAddresses'] = count($response['addresses']);

    $addressArray = explode(',', $response['addresses'][0]);
    $returnData['locality'] = $addressArray[4];
    $returnData['city'] = $addressArray[5];
    $returnData['county'] = $addressArray[6];


    $returnData['addresses'] = array();
    foreach ($response['addresses'] as $address)
    {
        $fullAddress = '';
        foreach ($address as $item){
            if($item!=' '){
                $fullAddress .= $item . ", ";
            }
        }
        $fullAddress = trim($fullAddress, ', ');
		$fullAddress = $fullAddress . ", " . $postcode;

        $returnData['addresses'][] = array(
            'line1' => $address[0],
            'line2' => $address[1],
            'city' => $address[3],
            'county' => $address[4],
			'postcode' => $postcode,
            'fullAddress' => $fullAddress,
        );
    }
    return $returnData;
}

function notifyAllCouncils($type=null,$pid=null){
	
	global $pdo;
	
	// Fetch Property
	$sql = "SELECT * FROM properties WHERE id=?";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute([$pid]);
	
	$property = $stmt->fetch(PDO::FETCH_ASSOC);
	
	// Fetch All Councils
	$sql = "SELECT * FROM users WHERE user_type=?";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute([1]);
	
	$councils = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	$template = file_get_contents("views/emails/".$type.".php");
	
	$template = str_replace('{{address1}}',$property['address1'],$template);
	$template = str_replace('{{address2}}',$property['address2'],$template);
	$template = str_replace('{{town}}',$property['town'],$template);
	$template = str_replace('{{county}}',$property['county'],$template);
	$template = str_replace('{{postcode}}',$property['postcode'],$template);
	$template = str_replace('{{bedrooms}}',$property['bedrooms'],$template);
	$template = str_replace('{{bathrooms}}',$property['bathrooms'],$template);
	$template = str_replace('{{rent_pcm}}',$property['rent_pcm'],$template);
	$template = str_replace('{{id}}',$property['id'],$template);

	$message = $template;
	
	$to = "info@reloc8uk.co.uk";  
	$subject = "New Property Added [Reloc8UK]";    
	$from = "Reloc8UK Portal <info@reloc8uk.co.uk>";  
	$headers = "From: $from \r\n";  
	
	foreach($councils as $council){
		$headers .= "Bcc: ".$council['email_address'] . "\r\n";
	}
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
	$mail = mail($to,$subject,$message,$headers);  
	
}

function image_fix_orientation($image) {
    if (method_exists($image, 'getImageProperty')) {
        $orientation = $image->getImageProperty('exif:Orientation');
    } else {
        $filename = $image->getImageFilename();

        if (empty($filename)) {
            $filename = 'data://image/jpeg;base64,' . base64_encode($image->getImageBlob());
        }

        $exif = exif_read_data($filename);
        $orientation = isset($exif['Orientation']) ? $exif['Orientation'] : null;
    }

    if (!empty($orientation)) {
        switch ($orientation) {
            case 3:
                $image->rotateImage('#000000', 180);
                break;

            case 6:
                $image->rotateImage('#000000', -90);
                break;

            case 8:
                $image->rotateImage('#000000', 90);
                break;
        }
    }
}

function markPropertyHeld($pid=null,$uid=null){
	
}
function correctImageOrientation($filename) {
  if (function_exists('exif_read_data')) {
    $exif = exif_read_data($filename);
    if($exif && isset($exif['Orientation'])) {
      $orientation = $exif['Orientation'];
      if($orientation != 1){
        $img = imagecreatefromjpeg($filename);
        $deg = 0;
        switch ($orientation) {
          case 3:
            $deg = 180;
            break;
          case 6:
            $deg = 270;
            break;
          case 8:
            $deg = 90;
            break;
        }
        if ($deg) {
          $img = imagerotate($img, $deg, 0);        
        }
        // then rewrite the rotated image back to the disk as $filename 
        imagejpeg($img, $filename, 95);
      } // if there is some rotation necessary
    } // if have the exif orientation info
  } // if function exists      
}

function getAllContacts($uid){
	global $pdo;
	
	$sql = "SELECT * FROM user_contacts WHERE user_id=?";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute([$uid]);
	
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	return $results;
}

function getContacts($uid,$type=null){
	global $pdo;
	
	$sql = "SELECT * FROM user_contacts WHERE user_id=? AND contact_type=? ORDER BY contact_name ASC";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute([$uid,$type]);
	
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	return $results;
}

function getBid($id){
	global $pdo;
	
	$sql = "SELECT property_council.*, user_property_offer.*, users.name, users.email_address, users.contact_number, properties.address1,properties.address2,properties.town,properties.county,properties.postcode FROM property_council LEFT JOIN user_property_offer ON property_council.property_id = user_property_offer.property_id LEFT JOIN users ON property_council.user_id = users.id LEFT JOIN properties ON property_council.property_id = properties.id WHERE property_council.id=?";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute([$id]);
	
	$result = $stmt->fetch();
	
	return $result;
}

function getCouncilInfo($id){
	global $pdo;
	
	$sql = "SELECT * FROM councils WHERE id=?";
	
	$stmt = $pdo->prepare($sql);
	
	$stmt->execute([$id]);
	
	$result = $stmt->fetch();
	
	return $result;
}

function deleteUser($id){
    global $pdo;
    
    $sql = "DELETE FROM users WHERE id='$id'";
    
    $stmt = $pdo->prepare($sql);
    
    $result = $stmt->execute();
    
    return $result;
    
}

function generateUserReport($council=null,$dateFrom=null,$dateTo=null,$datePredefined=null,$export=false){
	global $pdo;

	$councilSql = $council=="all" ? '' : ' AND `council_id` = \''.$council.'\'';

	if($datePredefined!=null){
		switch($datePredefined){
			case "1":
				$where = "MONTH(users.created) = ".date('m')." AND YEAR(users.created) = ".date('Y') . $councilSql;
				break;
			case "2":
				$where = "MONTH(users.created) = ".date('m',strtotime(date('Y-m')." -1 month"))." AND YEAR(users.created) = ".date('Y') . $councilSql;
				break;
			case "3":
				$where = "YEAR(users.created) = ".date('Y') . $councilSql;
				break;
			default:
				$where = "MONTH(users.created) = ".date('m')." AND YEAR(users.created) = ".date('Y') . $councilSql;
				break;
		}

		
		
	}else{
		$where = "created BETWEEN '".$dateFrom."' AND '".$dateTo."'" . $councilSql;
	}
		$sql = "SELECT users.*, councils.council_name, councils.council_group FROM users LEFT JOIN councils ON users.council_id=councils.id WHERE user_type = '3' AND ".$where. " ORDER BY created DESC";

		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetchAll();


	if($export==1){
		$filename = 'UserReportExport-'.date('d.m.Y').'.csv';

	    $data = fopen('php://memory', 'w');

	    $d=array();
	    $headers = array('ID','Name','Email Address','Contact Number','Council','Date Signed Up','NI Number','D.O.B','Bedrooms Required','Preferred Area','Reason for move','Other information','Housing Officer');
	    fputcsv($data,$headers);
	    foreach ($results as $row) {
	    	$d = array($row['id'],$row['name'],$row['email_address'],$row['contact_number'],$row['council_name'],$row['created'],$row['ni_number'],$row['date_of_birth'],$row['bedrooms_required'],$row['preferred_area'],$row['reason_for_move'],$row['other_information'],$row['housing_officer']);
	        fputcsv($data, $d);
	    }
	    fseek($data, 0);

	    header('Content-Type: text/csv');
	    header('Content-Disposition: attachment; filename="' . $filename . '";');

	    // Output all the remaining data on a file pointer
	    fpassthru($data);

	    fclose($data);

	    exit;
	}else{
		return $results;
	}
}

function getCouncilUsers(){
	global $pdo;

	$sql = "SELECT * FROM users WHERE user_type='1' ORDER BY name";

	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	$results = $stmt->fetchAll();

	return $results;
}