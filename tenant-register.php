<?php
require('system/config.php'); 

$councils = getAllCouncils();

$errors = array();

if(isset($_POST['register'])){
	// Create array() in preparation for any errors 
	$errors = array();
	
	// Check if fields are filled out
	if(!isset($_POST['name']) || $_POST['name'] == ""){
		$errors["name"] = "Your name is required";
	}
	if(!isset($_POST['email_address']) || $_POST['email_address'] == ""){
		$errors["email"] = "Your email address is required";
	}
	if(!isset($_POST['contact_number']) || $_POST['contact_number'] == ""){
		$errors["contact_number"] = "Your contact number is required";
	}
	if(!isset($_POST['ni_number']) || $_POST['ni_number'] == ""){
		$errors["ni_number"] = "Your NI number is required";
	}
	if(!isset($_POST['council_id']) || $_POST['council_id'] == ""){
		$errors["council_id"] = "A council is required";
	}
	if(!isset($_POST['preferred_area']) || $_POST['preferred_area'] == ""){
		$errors["preferred_area"] = "At least 1 preferred area is required";
	}
	if(!isset($_POST['reason_for_move']) || $_POST['reason_for_move'] == ""){
		$errors["reason_for_move"] = "A reason for your move is required";
	}
	if(!isset($_POST['consent']) || $_POST['consent'] == ""){
		$errors["consent"] = "Your consent is required for us to be able to proceed";
	}
	if(!isset($_POST['date_of_birth']) || $_POST['date_of_birth'] == ""){
		$errors["date_of_birth"] = "Your D.O.B is required for us to be able to proceed";
	}
	if(!isset($_POST['bedrooms_required']) || $_POST['bedrooms_required'] == ""){
		$errors["bedrooms_required"] = "Your bedroom requirement is required";
	}
	
	$exists = checkEmailExists($_POST['email_address']);
	
	if($exists){
		$errors["email_exists"] = "Your email address is already registered, please try <a href='login.php'>logging in</a>.";
	}
	
	$username = generate_username($_POST['name']);
	$name = $_POST['name'];
	$email_address = $_POST['email_address'];
	$contact_number = $_POST['contact_number'];
	$ni_number = $_POST['ni_number'];
	$council_id = $_POST['council_id'];
	$preferred_area = $_POST['preferred_area'];
	$reason_for_move = $_POST['reason_for_move'];
	$password = $_POST['password'];
	$housing_officer = $_POST['housing_officer'];
	$additional_info = $_POST['other_information'];
	$date_of_birth = $_POST['date_of_birth'];
	$bedrooms_required = $_POST['bedrooms_required'];
	$consent = $_POST['consent'];
	$user_type = 3;
	
	$hashed_password = password_hash($password,PASSWORD_DEFAULT);
	
	if(count($errors) == 0){
	
		$sql = "INSERT INTO users (username,name, email_address, contact_number, ni_number, council_id, preferred_area, reason_for_move, password, housing_officer, other_information, date_of_birth, bedrooms_required,consent, user_type) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		
		$stmt = $pdo->prepare($sql);
		
		$stmt->execute([$username, $name, $email_address, $contact_number, $ni_number, $council_id, $preferred_area, $reason_for_move, $hashed_password, $housing_officer, $additional_info, $date_of_birth, $bedrooms_required, $consent, $user_type]);
		
		$_SESSION['user_id'] = $pdo->lastInsertId();
		$_SESSION['logged_in'] = time();
		$_SESSION['user_name'] = $name;
		$_SESSION['username'] = $username;
		$_SESSION['user_type'] = $user_type;
		
		header("Location: index.php");
	}
	
}
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Tenant Registration - Reloc8UK Portal</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/x-icon" href="assets/favicon.ico">
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Crimson+Text">
<link rel="stylesheet" type="text/css" href="assets/css/admin/auth.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js" integrity="sha256-sPB0F50YUDK0otDnsfNHawYmA5M0pjjUf4TvRJkGFrI=" crossorigin="anonymous"></script>
</head>
<body class="auth-page-register">
<div class="auth-wrapper">
	<div class="auth-container">
		<div class="auth-header">
			<img src="assets/img/Reloc8Logo.jpg" alt="Reloc8UK Tenant Registration">
			<h1>Reloc8UK Tenant Registration</h1>
			<p>Register for a tenant account to be able to view the latest available properties.</p>
		</div>

		<form action="tenant-register.php" method="post" id="registerForm">
			<?php
				if(count($errors) > 0) {
				?>
				<div class="alert-error">
				<?php
					foreach($errors as $key=>$value){
				?>
					<p><?php echo $value; ?></p>
				<?php
					}
				?>
				</div>
				<?php
				}
				?>

			<div class="auth-form-row">
				<div class="form-group">
					<label>Full Name</label>
					<input type="text" name="name" placeholder="Full Name" autocomplete="false" required>
				</div>
				<div class="form-group">
					<label>Email Address</label>
					<input type="email" name="email_address" placeholder="Email Address" autocomplete="false" required>
				</div>
			</div>

			<div class="auth-form-row">
				<div class="form-group">
					<label>Contact Number</label>
					<input type="tel" name="contact_number" placeholder="Contact Number" autocomplete="false" required>
				</div>
				<div class="form-group">
					<label>Password</label>
					<input type="password" name="password" placeholder="Password" autocomplete="new-password" required>
				</div>
			</div>

			<div class="auth-form-row">
				<div class="form-group">
					<label>Date of Birth</label>
					<input type="date" name="date_of_birth" placeholder="Date of Birth" autocomplete="false" required>
				</div>
				<div class="form-group">
					<label>N.I Number</label>
					<input type="text" name="ni_number" placeholder="National Insurance Number" autocomplete="false" required>
				</div>
			</div>

			<div class="auth-form-row">
				<div class="form-group">
					<label>Bedrooms Required</label>
					<select name="bedrooms_required" required>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
					</select>
				</div>
				<div class="form-group">
					<label>Council</label>
					<select name="council_id" required>
						<?php
							foreach($councils as $council){
								$group[$council['council_group']][] = $council;
							}
							foreach($group as $key=>$values){
								echo '<optgroup label="'.$key.'">';
								 foreach ($values as $value) 
								 {
									 echo '<option value="'.$value['id'].'">'.$value['council_name'].'</option>';
								 }
								 echo '</optgroup>';
							}
						?>
					</select>
				</div>
			</div>

			<div class="auth-form-row">
				<div class="form-group">
					<label>Housing Officer <small class="form-hint">(If applicable)</small></label>
					<input type="text" name="housing_officer" placeholder="Housing Officer" autocomplete="false">
				</div>
				<div class="form-group">
					<label>Preferred Area(s)</label>
					<input type="text" name="preferred_area" placeholder="Preferred Area(s)" autocomplete="false" required>
				</div>
			</div>

			<div class="auth-form-row">
				<div class="form-group">
					<label>Reason for moving</label>
					<textarea class="textarea" name="reason_for_move" rows="4" placeholder="Reason for moving" autocomplete="false" required></textarea>
				</div>
				<div class="form-group">
					<label>Additional Information</label>
					<textarea class="textarea" name="other_information" rows="4" placeholder="Additional information" autocomplete="false"></textarea>
				</div>
			</div>

			<div class="auth-form-row auth-form-row--full">
				<div class="remember-me">
					<input type="checkbox" name="consent" value="1" id="consent" required>
					<label for="consent"><small>I consent to Reloc8 UK using my details to assist me in finding a property. I also consent to my local authority being informed by Reloc8 UK that I am actively looking to move, and passing on any relevant details where necessary.
<br/>
Please note Reloc8 UK will never pass your details onto a third party without your permission. </small>
</label>
				</div>
			</div>

			<div class="auth-primary-actions">
				<button type="submit" class="btn-login">Register</button>
				<a href="login.php" class="btn-login">Already registered?</a>
			</div>

			<input type="hidden" name="register" value="1">
		</form>
	</div>
</div>
<script type="text/javascript">
$("#registerForm").validate();
</script>
</body>
</html>