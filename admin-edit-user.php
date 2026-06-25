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
$page_title = 'Reloc8UK Admin - Edit User';
$portal_extra_head = '<link rel="stylesheet" type="text/css" href="assets/css/admin-crm-forms.css?ver='.time().'">';
include_once("views/header.php");
?>
		<div class="admin-crm-page">
		<div class="card admin-crm-page-header mb-4">
			<div class="card-body">
				<div class="admin-crm-page-header__row">
					<div>
						<h1 class="admin-crm-page-header__title">Edit User</h1>
						<p class="admin-crm-page-header__subtitle">You are currently editing user: <?php echo $user['name']; ?></p>
					</div>
				</div>
				<?php if(!empty($_GET['updated']) && $_GET['updated'] == "true"){ ?>
				<div class="alert alert-success mb-0 mt-3">User updated!</div>
				<?php } ?>
			</div>
		</div>
		<div class="card admin-crm-panel mb-4">
			<div class="card-header">
				<span class="iconify" data-icon="mdi:account-edit-outline"></span>
				<h5>User details</h5>
			</div>
			<div class="card-body">
		<form method="post" action="admin-edit-user.php?id=<?php echo $user['id']; ?>" id="user-edit-form">
			<input type="hidden" name="edit-user" value="1">
			<input type="hidden" name="id" value="<?php echo $user['id']; ?>" />
			<div class="admin-crm-section">
				<h6 class="admin-crm-section__title"><span class="iconify" data-icon="mdi:account-outline"></span>User Information</h6>
				<div class="row g-3">
					<div class="col-md-3 col-sm-12">
						<label for="name" class="admin-crm-label">Full Name</label>
						<input type="text" name="name" class="form-control admin-crm-control" placeholder="User's full name" value="<?php echo $user['name']; ?>" />
					</div>
					<div class="col-md-3 col-sm-12">
						<label for="email_address" class="admin-crm-label">Email Address</label>
						<input type="email" name="email_address" class="form-control admin-crm-control" placeholder="User's email address" value="<?php echo $user['email_address']; ?>" />
					</div>
					<div class="col-md-3 col-sm-12">
						<label for="username" class="admin-crm-label">Username</label>
						<input type="text" name="username" class="form-control admin-crm-control" placeholder="Username" value="<?php echo $user['username']; ?>" />
					</div>
					<div class="col-md-3 col-sm-12">
						<label for="user_type" class="admin-crm-label">User Type</label>
						<select name="user_type" class="form-select admin-crm-control" required>
							<option value="1" <?php if($user['user_type'] == 1){ echo "selected"; } ?>>Council</option>
							<option value="4" <?php if($user['user_type'] == 4){ echo "selected"; } ?>>Council (limited)</option>
							<option value="2" <?php if($user['user_type'] == 2){ echo "selected"; } ?>>Administrator</option>
							<option value="3" <?php if($user['user_type'] == 3){ echo "selected"; } ?>>Tenant</option>
						</select>
					</div>
					<div class="col-md-12 col-sm-12">
					    <label for="updatedPassword" class="admin-crm-label">Update Password</label>
					    <input type="text" name="updatedPassword" class="form-control admin-crm-control" placeholder="Enter new password" />
					    <span class="admin-crm-field-hint">Only enter a new password if you require a manual password change.</span>
					</div>
				</div>
			</div>
			<?php if($user['user_type'] == 3) { ?>
			<div class="admin-crm-section">
				<h6 class="admin-crm-section__title"><span class="iconify" data-icon="mdi:home-account"></span>Tenant Information</h6>
				<div class="row g-3">
					<div class="col-md-3 col-sm-12">
						<label for="ni_number" class="admin-crm-label">N.I Number</label>
						<input type="text" name="ni_number" class="form-control admin-crm-control" placeholder="National Insurance Number" value="<?php echo $user['ni_number']; ?>" />
					</div>
					<div class="col-md-3 col-sm-12">
						<label for="housing_officer" class="admin-crm-label">Housing Officer</label>
						<input type="text" name="housing_officer" class="form-control admin-crm-control" placeholder="Housing Officer" value="<?php echo $user['housing_officer']; ?>" />
					</div>
					<div class="col-md-3 col-sm-12">
						<label for="date_of_birth" class="admin-crm-label">Date of Birth</label>
						<input id="date_of_birth" name="date_of_birth" class="form-control admin-crm-control js-date--west" type="text" placeholder="_ _ /_ _ /_ _ _ _" value="<?php echo date("d-m-Y", strtotime($user['date_of_birth']));  ; ?>">
					</div>
					<div class="col-md-3 col-sm-12">
						<label for="council_id" class="admin-crm-label">Council</label>
						<select name="council_id" class="form-select admin-crm-control">
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
				<div class="row g-3 mt-1">
					<div class="col-md-3 col-sm-12">
						<label for="bedrooms_required" class="admin-crm-label">Bedrooms Required</label>
						<input type="num" name="bedrooms_required" class="form-control admin-crm-control" placeholder="Bedrooms Required" value="<?php echo $user['bedrooms_required']; ?>" />
					</div>
					<div class="col-md-3 col-sm-12">
						<label for="preferred_area" class="admin-crm-label">Preferred Area(s)</label>
						<input type="text" name="preferred_area" class="form-control admin-crm-control" placeholder="Preferred Area(s)" value="<?php echo $user['preferred_area']; ?>" />
					</div>
					<div class="col-md-3 col-sm-12">
						<label for="reason_for_move" class="admin-crm-label">Reason for Move</label>
						<textarea name="reason_for_move" class="form-control admin-crm-control" placeholder="Reason for Move"><?php echo $user['reason_for_move']; ?></textarea>
					</div>
					<div class="col-md-3 col-sm-12">
						<label for="other_information" class="admin-crm-label">Additional Information</label>
						<textarea name="other_information" class="form-control admin-crm-control" placeholder="Additional Information"><?php echo $user['other_information']; ?></textarea>
					</div>
				</div>
			</div>
			<?php } ?>
			<div class="admin-crm-submit-wrap">
				<button type="submit" class="btn btn-crm-primary btn-lg"><span class="iconify" data-icon="mdi:content-save-outline"></span>Update User</button>
			</div>
		</form>
			</div>
		</div>
		</div>
<?php
include_once("views/footer.php");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
  $('.js-date--west').mask('00/00/0000');
});
</script>
</body>
</html>