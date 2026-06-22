<?php
require('system/config.php');
 
/**
 * Check if the user is logged in.
 */
if(!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])){
    //User not logged in. Redirect them back to the login.php page.
    header('Location: login.php');
    exit;
}

if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 2){
	// User isn't an administrator, redirect back to home page.
	header('Location: index.php');
	exit;
}

if(isset($_POST['edit-user']) && $_POST['edit-user'] == 1){
	
	$id = $_POST['id'];
	$name = $_POST['name'];
	$username = $_POST['username'];
	$email_address = $_POST['email_address'];
	$user_type = $_POST['user_type'];
	if(isset($_POST['updatedPassword'])){
	    $password = $_POST['updatedPassword'];
	    $hashedPassword = hashPassword($password);
	}
	
	if($user_type == 3){
		$ni_number = $_POST['ni_number'];
		$housing_officer = $_POST['housing_officer'];
		$reason_for_move = $_POST['reason_for_move'];
		$other_information = $_POST['other_information'];
		$council_id = $_POST['council_id'];
		$date_of_birth = date("Y-d-m", strtotime($_POST['date_of_birth']));  
		$bedrooms_required = $_POST['bedrooms_required'];
		$preferred_area = $_POST['preferred_area'];
		
		$sql = "UPDATE users SET name='$name',username='$username',email_address='$email_address',user_type='$user_type',ni_number='$ni_number',housing_officer='$housing_officer',reason_for_move='$reason_for_move',other_information='$other_information',date_of_birth='$date_of_birth',council_id='$council_id',bedrooms_required='$bedrooms_required',preferred_area='$preferred_area' WHERE id='$id'";
	}else{
	    
		$sql = "UPDATE users SET name='$name',username='$username',email_address='$email_address',user_type='$user_type'";
		if(!empty($hashedPassword)){
		    $sql .= ",password='$hashedPassword' ";
		}
		$sql .= "WHERE id='$id'";
	}
	
	$stmt = $pdo->prepare($sql);
	
	if($stmt->execute()){
		header('Location: admin-edit-user.php?id='.$id.'&updated=true');
	}
	
}


$user = getUser($_GET['id']);
$page = "admin";
?>
<!DOCTYPE html>
<head>
<title>Reloc8UK Admin - Edit User <?php echo $property['id']; ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<style type="text/css">
#primary-nav {
  background-color: #545659;
  color: white;
}
#primary-nav a {
	color: white;
}

</style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/font/css/open-iconic-bootstrap.min.css" integrity="sha256-BJ/G+e+y7bQdrYkS2RBTyNfBHpA9IuGaPmf9htub5MQ=" crossorigin="anonymous" />
<link rel="stylesheet" type="text/css" href="assets/css/styles.css?ver=<?php echo time(); ?>">
<script src="https://code.jquery.com/jquery-3.4.1.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
</head>
<body class="bg-light">
<?php
include_once("views/navbar.php");
?>
	<main role="main" class="container">
		<div class="my-3 p-3 bg-white rounded shadow-sm">
			<h4 class="border-bottom border-gray pb-2 mb-0">Edit User</h4>
			<p class="lead">You are currently editing user: <?php echo $user['name']; ?></p>
			<?php if(!empty($_GET['updated']) && $_GET['updated'] == "true"){ ?>
			<div class="alert alert-success">User updated!</div>
			<?php } ?>
		</div>
		<div class="my-3 p-3 bg-white rounded shadow-sm">
		<form method="post" action="admin-edit-user.php?id=<?php echo $user['id']; ?>" id="user-edit-form">
			<input type="hidden" name="edit-user" value="1">
			<input type="hidden" name="id" value="<?php echo $user['id']; ?>" />
			<div class="p-2 bg-light mb-2">
				<p class="lead">User Information</p>
				<div class="form-group row my-2">
					<div class="col-md-3 col-sm-12">
						<label for="name">Full Name</label>
						<input type="text" name="name" class="form-control" placeholder="User's full name" value="<?php echo $user['name']; ?>" />
					</div>
					<div class="col-md-3 col-sm-12">
						<label for="email_address">Email Address</label>
						<input type="email" name="email_address" class="form-control" placeholder="User's email address" value="<?php echo $user['email_address']; ?>" />
					</div>
					<div class="col-md-3 col-sm-12">
						<label for="username">Username</label>
						<input type="text" name="username" class="form-control" placeholder="Username" value="<?php echo $user['username']; ?>" />
					</div>
					<div class="col-md-3 col-sm-12">
						<label for="user_type">User Type</label>
						<select name="user_type" class="form-control" required>
							<option value="1" <?php if($user['user_type'] == 1){ echo "selected"; } ?>>Council</option>
							<option value="4" <?php if($user['user_type'] == 4){ echo "selected"; } ?>>Council (limited)</option>
							<option value="2" <?php if($user['user_type'] == 2){ echo "selected"; } ?>>Administrator</option>
							<option value="3" <?php if($user['user_type'] == 3){ echo "selected"; } ?>>Tenant</option>
						</select>
					</div>
					<div class="col-md-12 col-sm-12 mt-2">
					    <label for="updatedPassword">Update Password</label>
					    <input type="text" name="updatedPassword" class="form-control" placeholder="Enter new password" />
					    <span class="small">Only enter a new password if you require a manual password change.</span>
					</div>
				</div>	
			</div>
			<?php if($user['user_type'] == 3) { ?>
			<div class="p-2 bg-light mb-2">
				<p class="lead">Tenant Information</p>
				<div class="form-group row my-2">
					<div class="col-md-3 col-sm-12">
						<label for="ni_number">N.I Number</label>
						<input type="text" name="ni_number" class="form-control" placeholder="National Insurance Number" value="<?php echo $user['ni_number']; ?>" />
					</div>
					<div class="col-md-3 col-sm-12">
						<label for="housing_officer">Housing Officer</label>
						<input type="text" name="housing_officer" class="form-control" placeholder="Housing Officer" value="<?php echo $user['housing_officer']; ?>" />
					</div>
					<div class="col-md-3 col-sm-12">
						<label for="date_of_birth">Date of Birth</label>
						<input id="date_of_birth" name="date_of_birth" class="form-control js-date--west" type="text" placeholder="_ _ /_ _ /_ _ _ _" value="<?php echo date("d-m-Y", strtotime($user['date_of_birth']));  ; ?>">
					</div>
					<div class="col-md-3 col-sm-12">
						<label for="council_id">Council</label>
						<select name="council_id" class="form-control">
						<?php
							$councils = getAllCouncils();
							foreach($councils as $council){
								$group[$council['council_group']][] = $council;
							}
							foreach($group as $key=>$values){
								echo '<optgroup label="'.$key.'">';
								 foreach ($values as $value) 
								 {
									 echo '<option value="'.$value['id'].'" ';
									 if($user['council_id'] == $value['id']){ echo "selected"; }
									 echo '>'.$value['council_name'].'</option>';
								 }
								 echo '</optgroup>';
							}
						?>
						</select>
					</div>
				</div>
				<div class="form-group row my-2">
					<div class="col-md-3 col-sm-12">
						<label for="bedrooms_required">Bedrooms Required</label>
						<input type="num" name="bedrooms_required" class="form-control" placeholder="Bedrooms Required" value="<?php echo $user['bedrooms_required']; ?>" />
					</div>
					<div class="col-md-3 col-sm-12">
						<label for="preferred_area">Preferred Area(s)</label>
						<input type="text" name="preferred_area" class="form-control" placeholder="Preferred Area(s)" value="<?php echo $user['preferred_area']; ?>" />
					</div>
					<div class="col-md-3 col-sm-12">
						<label for="reason_for_move">Reason for Move</label>
						<textarea name="reason_for_move" class="form-control" placeholder="Reason for Move"><?php echo $user['reason_for_move']; ?></textarea>
					</div>
					<div class="col-md-3 col-sm-12">
						<label for="other_information">Additional Information</label>
						<textarea name="other_information" class="form-control" placeholder="Additional Information"><?php echo $user['other_information']; ?></textarea>
					</div>
				</div>
			</div>
			<?php } ?>
			<div class="p-2 bg-light mb-2">
				<div class="form-group row my-2 d-flex justify-content-center">
					<button type="submit" class="btn btn-lg btn-dark d-block">Update User</button>
				</div>
			</div>
		</form>
	</div>
	</main>
	<footer>
		<div class="copyright text-center py-2">
			<small>&copy; Copyright <?php echo date("Y"); ?> Midland Relocations Limited.</small>
		</div>
	</footer>
<script type="text/javascript">
$(document).ready(function() {
  $('.js-date--west').mask('00/00/0000');
});
</script>
</body>
</html>