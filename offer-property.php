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
$error = array();
if(isset($_POST['status']) && $_POST['status'] == 1){
	
	//$hasPropertyBeenLet = hasPropertyBeenLet($_POST['pid']);
	
	$hasPropertyBeenLeft = false;
	
	if($hasPropertyBeenLet){
		$error["let"] = "This property has already been let.";
	}else{
		global $pdo;
		
		$sql = "INSERT user_property_offer 
			(property_id,user_id,council_id,officer_name,officer_telephone,officer_email,applicant_name,applicant_telephone,applicant_email,applicant_dob,applicant_ni_number,family_benefit_cap,benefit_status,family_travel,removals_required,beds_required,electric_cooker,fridge_freezer,additional_items,required_property_location,required_bedrooms,scheduled_move_date,property_held_from,incentive_amount,other_requirements)
			VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		
		$stmt = $pdo->prepare($sql);
		
		foreach($_POST as $key=>$value){
			$key = $value;
		}
		
		$execute = $stmt->execute([$_POST['pid'],$_POST['uid'],$_POST['council_id'],$_POST['officer_name'],$_POST['officer_telephone'],$_POST['officer_email'],$_POST['applicant_name'],$_POST['applicant_telephone'],$_POST['applicant_email'],$_POST['applicant_dob'],$_POST['applicant_ni_number'],$_POST['family_benefit_cap'],$_POST['benefit_status'],$_POST['family_travel'],$_POST['removals_required'],$_POST['beds_required'],$_POST['electric_cooker'],$_POST['fridge_freezer'],$_POST['additional_items'],$_POST['required_property_location'],$_POST['required_bedrooms'],$_POST['scheduled_move_date'],$_POST['property_held_from'],$_POST['incentive_amount'],$_POST['other_requirements']]);
		
		if($execute){
			$pid = $_POST['pid'];
			$uid = $_POST['uid'];
			$notes = $_POST['notes'];
			if(updatePropertyStatus($pid,$uid,3,$notes)){
				$user = getUser($uid);
				$prop = getProperty($pid);
				notifyAdmin("offer",$user,$prop);
				header('Location: my-bids.php');
			}
		}
	}
}

$property = getProperty($_GET['id']);
$propertyImages = explode(",",$property['images']);
$page = "mybids";
$councils = getAllCouncils();

$getContacts = getAllContacts($_SESSION['user_id']);

$page_title = "Reloc8UK Portal Offer Property";
include_once("views/header.php");
?>
<main class="portal-main container">
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="savelater">
	<input type="hidden" name="pid" value="<?php echo $property['id']; ?>">
	<input type="hidden" name="uid" value="<?php echo $_SESSION['user_id']; ?>">
	<input type="hidden" name="status" value="1">
	<div class="portal-card mb-4">
		<div class="portal-card-header">
			<h4>Offer Property</h4>
		</div>
		<div class="portal-card-body">
			<p class="lead mb-0">By placing an offer on this property, you confirm that you have offered this to a tenant and the tenant has accepted the property.</p>
		</div>
	</div>
	<div class="portal-card mb-4 border border-secondary">
		<div class="portal-card-body">
		<div class="row">
			<div class="col-md-3 col-sm-12 justify-content-center">
				<img src="assets/property_images/<?php echo $propertyImages[0]; ?>" width="128" class="img-fluid mx-auto d-block">
			</div>
			<div class="col-md-9 col-sm-12">
				<p class="lead"><a href="view-property.php?id=<?php echo $property['id']; ?>"><?php echo $property['address1'] . ", " . $property['county']; ?></a></p>
				<p>Rent PCM: &pound;<?php echo $property['rent_pcm']; ?></p>
				<p>Bedrooms: <?php echo $property['bedrooms']; ?> | Bathrooms: <?php echo $property['bathrooms']; ?></p>
			</div>
		</div>
		</div>
	</div>
	<div class="portal-card mb-4">
		<div class="portal-card-body">
		<p class="lead">Please fill this form out as completely as possible. A member of the team will approve your offer shortly after submission, so please check your inbox.</p>
		<div class="form-group row my-4">
			<div class="col-md-3">
				<label for="council_id">Council</label>
				<?php
					$councils = getAllCouncils(); 
				?>
				<select name="council_id" class="form-control" required>
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
			<div class="col-md-3">
				<label for="officer_name">Officer Name</label>
				<input type="text" name="officer_name" class="form-control" required placeholder="Officer name" />
			</div>
			<div class="col-md-3">
				<label for="officer_telephone">Officer Telephone</label>
				<input type="text" name="officer_telephone" class="form-control" required placeholder="Officer telephone" />
			</div>
			<div class="col-md-3">
				<label for="officer_email">Officer Email</label>
				<input type="email" name="officer_email" class="form-control" required placeholder="Officer email" />
			</div>
		</div>
		<hr>
		<div class="form-group row my-4">
			<div class="col-md-3">
				<label for="applicant_name">Applicant's Name</label>
				<input type="text" name="applicant_name" class="form-control" required placeholder="Applicant name" />
			</div>
			<div class="col-md-2">
				<label for="applicant_email">Applicant's Email</label>
				<input type="email" name="applicant_email" class="form-control" placeholder="Applicant email" />
			</div>
			<div class="col-md-2">
				<label for="applicant_telephone">Applicant's Phone</label>
				<input type="tel" name="applicant_telephone" class="form-control" placeholder="Applicant phone" />
			</div>
			<div class="col-md-3">
				<label for="applicant_dob">Applicant's D.O.B</label>
				<input type="date" name="applicant_dob" class="form-control" placeholder="Applicant DOB" />
			</div>
			<div class="col-md-2">
				<label for="applicant_ni_number">Applicant's NI No</label>
				<input type="text" name="applicant_ni_number" class="form-control" placeholder="Applicant NI No" />
			</div>
		</div>
		<hr>
		<div class="form-group row my-4">
			<div class="col-md-3">
				<label for="family_benefit_cap">Benefit Cap?</label>
				<select name="family_benefit_cap" class="form-control" required>
					<option value="" selected disabled>Select option</label>
					<option value="1">Yes</option>
					<option value="0">No</option>
				</select>
			</div>
			<div class="col-md-3">
				<label for="benefit_status">Benefit Status</label>
				<input type="text" name="benefit_status" class="form-control" placeholder="Benefit status" required />
			</div>
			<div class="col-md-3">
				<label for="family_travel">How will the family travel?</label>
				<input type="text" name="family_travel" class="form-control" placeholder="How will the family travel?" required />
			</div>
			<div class="col-md-3">
				<label for="removals_required">Are removals required?</label>
				<select name="removals_required" class="form-control" required>
					<option value="" selected disabled>Select option</option>
					<option value="1">Yes</option>
					<option value="0">No</option>
				</select>
			</div>
		</div>
		<hr>
		<div class="form-group row my-4">
			<div class="col-md-3">
				<label for="beds_required">Beds Required?</label>
				<input type="text" name="beds_required" class="form-control" placeholder="Bed's required?" />
			</div>
			<div class="col-md-3">
				<label for="electric_cooker">Cooker Required?</label>
				<input type="text" name="electric_cooker" class="form-control" placeholder="Cooker required?" />
			</div>
			<div class="col-md-3">
				<label for="fridge_freezer">Fridge/freezer Required?</label>
				<input type="text" name="fridge_freezer" class="form-control" placeholder="Fridge/freezer required?" />
			</div>
			<div class="col-md-3">
				<label for="additional_items">Additional Items?</label>
				<textarea class="textarea form-control" name="additional_items"></textarea>
			</div>
		</div>
		<hr>
		<div class="form-group row my-4">
			<div class="col-md-3">
				<label for="required_property_location">Required Property Location</label>
				<input type="text" name="required_property_location" class="form-control" placeholder="Required Property Location" />
			</div>
			<div class="col-md-3">
				<label for="required_bedrooms">Required Bedrooms</label>
				<select name="required_bedrooms" class="form-control" required>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
				</select>
			</div>
			<div class="col-md-3">
				<label for="scheduled_move_date">Scheduled Move Date</label>
				<input type="date" name="scheduled_move_date" class="form-control" placeholder="Scheduled move date" required />
			</div>
			<div class="col-md-3">
				<label for="property_held_from">Property Held From</label>
				<input type="date" name="property_held_from" class="form-control" placeholder="Property held from" required />
			</div>
		</div>
		<hr>
		<div class="form-group row my-4">
			<div class="col-md-4">
				<label for="tenancy_type">Tenancy Type</label>
				<select name="tenancy_type" class="form-control" required>
					<option value="" selected disabled>Select option</option>
					<option value="AST/Nightly">AST/Nightly</option>
					<option value="EHL">EHL</option>
				</select>
			</div>
			<div class="col-md-4">
				<label for="incentive_amount">Incentive Amount</label>
				<input type="text" name="incentive_amount" class="form-control" placeholder="Incentive amount" />
			</div>
			<div class="col-md-4">
				<label for="other_requirements">Other Requirements</label>
				<textarea name="other_requirements" class="textarea form-control"></textarea>
			</div>
		</div>
		<div class="form-group row my-2 d-flex justify-content-center">
			<div class="col-md-12">
				<p class="lead">Add notes relevant to this offer</p>
				<textarea class="textarea form-control" name="notes" placeholder="Add notes"></textarea>
			</div>
		</div>
		<!--<div class="form-group row my-4 mx-2 p-2 bg-light">
			<p class="lead">Who should this form be sent to? Choose one of each from below. <strong><u>You must have added the contact first</u>.</strong></p>
			<div class="col-md-3">
				<label for="social_worker">Social Worker</label>
				<select name="email_to[social_worker]" class="form-control">
					<option value="" selected disabled>Select contact</option>
					<?php 
						$socialWorkers = getContacts($_SESSION['user_id'],"1"); 
						
						foreach($socialWorkers as $sw){
							echo '<option value="'.$sw['contact_email'].'">'.$sw['contact_name'].'</option>';
						}
					?>
				</select>
			</div>
			<div class="col-md-3">
				<label for="manager">Manager</label>
				<select name="email_to[manager]" class="form-control">
					<option value="" selected disabled>Select contact</option>
					<?php 
						$managers = getContacts($_SESSION['user_id'],"2"); 
						
						foreach($managers as $mg){
							echo '<option value="'.$mg['contact_email'].'">'.$mg['contact_name'].'</option>';
						}
					?>
				</select>
			</div>
			<div class="col-md-3">
				<label for="west_london">West London</label>
				<select name="email_to[west_london]" class="form-control">
					<option value="" selected disabled>Select contact</option>
					<?php 
						$wls = getContacts($_SESSION['user_id'],"4"); 
						
						foreach($wls as $wl){
							echo '<option value="'.$wl['contact_email'].'">'.$wl['contact_name'].'</option>';
						}
					?>
				</select>
			</div>
			<div class="col-md-3">
				<label for="finance">Finance</label>
				<select name="email_to[finance]" class="form-control">
					<option value="" selected disabled>Select contact</option>
					<?php 
						$finances = getContacts($_SESSION['user_id'],"3"); 
						
						foreach($finances as $fc){
							echo '<option value="'.$fc['contact_email'].'">'.$fc['contact_name'].'</option>';
						}
					?>
				</select>
			</div>
		</div>-->
		<div class="form-group row my-2">
			<div class="col-md-12 text-center">
				<div class="alert alert-secondary text-center" style="display: none;"></div>
			</div>
		</div>
		<div class="form-group row my-2 d-flex justify-content-center">
			
			<div class="btn-group">
				<button id="clearSavedData" class="btn btn-lg btn-danger">Clear Form</button>
				<button type="submit" id="save" class="btn-lg btn btn-primary">Save For Later</button>
				<button class="btn btn-secondary btn-lg d-block" type="submit">Submit Offer</button>
			</div>
		</div>
		</div>
	</div>
	</form>
</main>
<?php
include_once("views/footer.php");
?>
<script type="text/javascript" src="assets/js/form.js?ver=<?php echo time(); ?>"></script>
</body>
</html>
