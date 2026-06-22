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
$portal_extra_head = '<link rel="stylesheet" type="text/css" href="assets/css/admin-crm-forms.css?ver='.time().'">';
include_once("views/header.php");
?>
		<div class="admin-crm-page">
		<div class="card admin-crm-page-header mb-4">
			<div class="card-body">
				<div class="admin-crm-page-header__row">
					<div>
						<h1 class="admin-crm-page-header__title">My Contacts</h1>
						<p class="admin-crm-page-header__subtitle">Manage contacts used when offering properties to tenants.</p>
					</div>
				</div>
			</div>
		</div>
		<?php 
		if($success == 1){
		?>
		<div class="alert alert-success text-center mb-4">
			Contact added to your contacts list
		</div>
		<?php } ?>
		<?php 
		if($failure == 1){
		?>
		<div class="alert alert-danger text-center mb-4">
			Error adding contact to your contact list, please try again.
		</div>
		<?php } ?>
		<div class="card admin-crm-panel mb-4">
			<div class="card-header">
				<span class="iconify" data-icon="mdi:account-group-outline"></span>
				<h5>Your contacts</h5>
			</div>
			<div class="card-body">
		<div class="admin-crm-table-wrap">
			<table class="table table-striped table-bordered table-hover mb-0">
				<thead>
				<tr>
					<th>Contact Name</th>
					<th>Contact Email</th>
					<th>Contact Type</th>
					<th></th>
				</tr>
				</thead>
				<tbody>
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
				</tr>
					<?php
					}
				?>
				</tbody>
			</table>
		</div>
			</div>
		</div>
		<div class="card admin-crm-panel mb-4">
			<div class="card-header">
				<span class="iconify" data-icon="mdi:account-plus-outline"></span>
				<h5>Add contact</h5>
			</div>
			<div class="card-body">
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<input type="hidden" name="addcontact" value="1">
			<input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
			<div class="admin-crm-section">
				<div class="row g-3">
					<div class="col-md-4 col-sm-12">
						<label for="contact_name" class="admin-crm-label">Full Name</label>
						<input type="text" name="contact_name" id="contact_name" class="form-control admin-crm-control" placeholder="Contacts full name" />
					</div>
					<div class="col-md-4 col-sm-12">
						<label for="contact_email" class="admin-crm-label">Email Address</label>
						<input type="email" name="contact_email" id="contact_email" class="form-control admin-crm-control" placeholder="Contacts email address" />
					</div>
					<div class="col-md-4 col-sm-12">
						<label for="contact_type" class="admin-crm-label">Contact Type</label>
						<select name="contact_type" id="contact_type" class="form-select admin-crm-control" required>
							<option value="1">Social Worker</option>
							<option value="2">Manager</option>
							<option value="3">Finance</option>
							<option value="4">West London</option>
							<option value="5">Other</option>
						</select>
					</div>
				</div>
			</div>
			<div class="admin-crm-subpanel mb-3">
				<p class="mb-0">NOTE: The contacts entered on this page are for the purpose of offering a property. You will be able to select a contact from each dropdown within the offer property page and it will email a copy of the tenants information to each contact.</p>
			</div>
			<div class="admin-crm-submit-wrap">
				<button type="submit" class="btn btn-crm-primary btn-lg"><span class="iconify" data-icon="mdi:account-plus-outline"></span>Add Contact</button>
			</div>
		</form>
			</div>
		</div>
		</div>
<?php
include_once("views/footer.php");
?>
</body>
</html>
