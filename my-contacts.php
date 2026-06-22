<?php
require('system/config.php');
 
/**
 * Check if the user is logged in.
 */
if(!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])){
    //User not logged in. Redirect them back to the login.php page.
    header('Location: login.php?redirect=properties.php');
    exit;
}
$success = 0;
$failure = 0;
if(isset($_POST['addcontact']) && $_POST['addcontact'] == 1){
	
	global $pdo;
	
	$sql = "INSERT INTO user_contacts (user_id,contact_email,contact_name,contact_type) VALUES(?,?,?,?)";
	
	$stmt = $pdo->prepare($sql);
	
	$result = $stmt->execute([$_POST['user_id'],$_POST['contact_email'],$_POST['contact_name'],$_POST['contact_type']]);
	
	if($result){
		$success = 1;
	}else{
		$failure = 1;
	}
}
$page = "mycontacts";
$page_title = "Reloc8UK Portal - My Contacts";
include_once("views/header.php");
?>
<main class="portal-main container">
	<div class="portal-card mb-4">
		<div class="portal-card-header">
			<h6>My Contacts</h6>
		</div>
		<div class="portal-card-body">
		<?php 
		if($success == 1){
		?>
		<div class="alert alert-success text-center">
			Contact added to your contacts list
		</div>
		<?php } ?>
		<?php 
		if($failure == 1){
		?>
		<div class="alert alert-danger text-center">
			Error adding contact to your contact list, please try again.
		</div>
		<?php } ?>
		</div>
	</div>
	<div class="portal-card mb-4">
		<div class="portal-card-body">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<tr>
					<th>Contact Name</th>
					<th>Contact Email</th>
					<th>Contact Type</th>
					<th></th>
				</tr>
				<?php
					$contacts = getAllContacts($_SESSION['user_id']); 
					foreach($contacts as $contact){
				?>
				<tr>
					<td><?php echo $contact['contact_name']; ?></td>
					<td><?php echo $contact['contact_email']; ?></td>
					<td><?php switch($contact['contact_type']) {
						case "1":
							echo "Social Worker";
							break;
						case "2":
							echo "Manager";
							break;
						case "3":
							echo "Finance";
							break;
						case "4":
							echo "West London";
							break;
						case "5":
							echo "Other";
							break;
						default:
							echo "Other";
							break;
					}
					?></td>
					<td></td>
					<?php
					}
				?>
			</table>
		</div>
		</div>
	</div>
	<div class="portal-card mb-4">
		<div class="portal-card-body">
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<input type="hidden" name="addcontact" value="1">
			<input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
			<div class="p-2 bg-light mb-2">
				<div class="form-group row my-2">
					<div class="col-md-4 col-sm-12">
						<label for="contact_name">Full Name</label>
						<input type="text" name="contact_name" class="form-control" placeholder="Contacts full name" />
					</div>
					<div class="col-md-4 col-sm-12">
						<label for="contact_email">Email Address</label>
						<input type="email" name="contact_email" class="form-control" placeholder="Contacts email address" />
					</div>
					<div class="col-md-4 col-sm-12">
						<label for="contact_type">Contact Type</label>
						<select name="contact_type" class="form-control" required>
							<option value="1">Social Worker</option>
							<option value="2">Manager</option>
							<option value="3">Finance</option>
							<option value="4">West London</option>
							<option value="5">Other</option>
						</select>
					</div>
				</div>	
			</div>
			<div class="p-2 bg-light mb-2">
				<div class="form-group row my-2">
					<div class="col-md-12">
						<p class="lead">NOTE: The contacts entered on this page are for the purpose of offering a property. You will be able to select a contact from each dropdown within the offer property page and it will email a copy of the tenants information to each contact.</p>
					</div>
				</div>
				<div class="form-group row my-2">
					<div class="col-md-12 text-center justify-content-center">
						<button class="btn btn-secondary btn-lg">Add Contact</button>
					</div>
				</div>
			</div>
		</form>
		</div>
	</div>
</main>
<?php
include_once("views/footer.php");
?>
</body>
</html>
