<?php
require('system/config.php'); 

//If the POST var "login" exists (our submit button), then we can
//assume that the user has submitted the login form.
if(isset($_POST['forgot-password'])){
	
    $user = userExists($_POST['email_address']);
	if(!empty($user)){
		$newPassword = randomPassword();
		
		$newHashedPassword = password_hash($newPassword,PASSWORD_DEFAULT);
		
		updateUserPassword($user['id'],$newHashedPassword);
		
		sendUserNewPassword($user['id'],$newPassword);
		
		header("Location: login.php?newpassword=1");
	}else{
		header("Location: login.php?invaliduser=1");
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Reloc8UK Portal Forgot Password</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/x-icon" href="assets/favicon.ico">
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Crimson+Text">
<link rel="stylesheet" type="text/css" href="assets/css/admin/auth.css">
</head>
<body>
<div class="auth-wrapper">
	<div class="auth-container">
		<div class="auth-header">
			<img src="assets/img/Reloc8Logo.jpg" alt="Reloc8UK Portal Forgot Password">
			<h1>Forgot Password</h1>
			<p>Request a new password if you have forgotten your existing one.</p>
		</div>

		<form action="forgot-password.php" method="post">
			<div class="form-group">
				<label for="email_address">Email Address</label>
				<input type="text" id="email_address" name="email_address" placeholder="Email Address">
			</div>

			<button type="submit" class="btn-login">Request Password</button>

			<div class="auth-link-container">
				<a href="login.php" class="auth-link">Back to login</a>
			</div>

			<input type="hidden" name="forgot-password" value="1">
		</form>
	</div>
</div>
</body>
</html>