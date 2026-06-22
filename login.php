<?php
/**
 * Include Configuration file.
 */
require('system/config.php'); 
 
//If the POST var "login" exists (our submit button), then we can
//assume that the user has submitted the login form.
if(isset($_POST['login'])){
    
    //Retrieve the field values from our login form.
    $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
    $passwordAttempt = !empty($_POST['password']) ? trim($_POST['password']) : null;
    
    //Retrieve the user account information for the given username.
    $sql = "SELECT id, username, password, user_type, name FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    
    //Bind value.
    $stmt->bindValue(':username', $username);
    
    //Execute.
    $stmt->execute();
    
    //Fetch row.
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    //If $row is FALSE.
    if($user === false){
        //Could not find a user with that username!
        //PS: You might want to handle this error in a more user-friendly manner!
        die('Incorrect username / password combination!');
    } else{
        //User account found. Check to see if the given password matches the
        //password hash that we stored in our users table.
        
        //Compare the passwords.
        $validPassword = password_verify($passwordAttempt, $user['password']);
        
        //If $validPassword is TRUE, the login has been successful.
        if($validPassword){
			
			$pdo->prepare("UPDATE users SET last_login=NOW() WHERE id='".$user['id']."'")->execute();
            
            //Provide the user with a login session.
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['logged_in'] = time();
			$_SESSION['user_name'] = $user['name'];
			$_SESSION['username'] = $user['username'];
			$_SESSION['user_type'] = $user['user_type'];
			
			if($user['user_type'] == 2){
			    checkExpiredPropertyBids();
			}
            
			if(!empty($_GET['redirect'])){
				header('Location: '.$_GET['redirect']);
			}elseif($user['user_type'] == "3"){
				header('Location: properties.php');
			}else{
				header('Location: index.php');
			}
            exit;
            
        } else{
            //$validPassword was FALSE. Passwords do not match.
            die('Incorrect username / password combination!');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Reloc8UK Portal Login</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/x-icon" href="assets/favicon.ico">
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Crimson+Text">
<link rel="stylesheet" type="text/css" href="assets/css/admin/auth.css?ver=<?php echo time(); ?>">
</head>
<body>
<div class="auth-wrapper">
	<div class="auth-container">
		<div class="auth-header">
			<img src="assets/img/Reloc8Logo.jpg" alt="Reloc8UK Portal Login">
			<h1>Reloc8UK Property Portal</h1>
			<p>Login or register from here to access the Reloc8UK Property Portal.</p>
		</div>

		<form action="login.php" method="post">
			<div class="form-group">
				<label for="username">User Name</label>
				<input type="text" id="username" name="username" placeholder="User Name">
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input type="password" id="password" name="password" placeholder="Password">
			</div>

			<div class="auth-primary-actions">
				<button type="submit" class="btn-login">Login</button>
				<a href="tenant-register.php" class="btn-login">Tenant? Register here</a>
			</div>

			<div class="auth-link-container">
				<a href="forgot-password.php" class="auth-link">Forgot password?</a>
			</div>

			<input type="hidden" name="login" value="1">
		</form>
	</div>
</div>
</body>
</html>