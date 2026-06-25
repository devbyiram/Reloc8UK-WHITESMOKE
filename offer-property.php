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
$portal_extra_head = '<link rel="stylesheet" type="text/css" href="assets/css/admin-crm-forms.css?ver='.time().'">
<link rel="stylesheet" type="text/css" href="assets/css/property-card.css?ver='.time().'">
<link rel="stylesheet" type="text/css" href="assets/css/property-card-rightmove.css?ver='.time().'">';
include_once("views/header.php");
?>
		<div class="admin-crm-page">
		<div class="card admin-crm-page-header mb-4">
			<div class="card-body">
				<div class="admin-crm-page-header__row">
					<div>
						<h1 class="admin-crm-page-header__title">Offer Property</h1>
						<p class="admin-crm-page-header__subtitle">Submit a tenant offer for this property.</p>
						<span class="admin-crm-page-header__meta"><span class="iconify" data-icon="mdi:file-document-outline"></span>Offer submission</span>
					</div>
					<a href="view-property.php?id=<?php echo $property['id']; ?>" class="btn btn-crm-secondary"><span class="iconify" data-icon="mdi:eye-outline"></span>View Property</a>
				</div>
			</div>
		</div>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="savelater">
		<input type="hidden" name="pid" value="<?php echo $property['id']; ?>">
		<input type="hidden" name="uid" value="<?php echo $_SESSION['user_id']; ?>">
		<input type="hidden" name="status" value="1">
		<div class="card admin-crm-panel mb-4">
			<div class="card-header">
				<span class="iconify" data-icon="mdi:information-outline"></span>
				<h5>Offer confirmation</h5>
			</div>
			<div class="card-body">
				<p class="mb-0 text-muted">By placing an offer on this property, you confirm that you have offered this to a tenant and the tenant has accepted the property.</p>
			</div>
		</div>
		<div class="property-listings-grid mb-4">
			<div class="property-listings-grid__item d-flex">
				<article class="property-card property-card--rightmove card w-100 h-100">
					<div class="property-card__media">
						<div class="property-card-carousel">
							<div class="property-card-carousel__sources" aria-hidden="true" hidden>
								<?php if($property['images']){ ?>
									<?php foreach($propertyImages as $imageFile){ ?>
									<span class="property-card-carousel__source" data-src="./system/thumbs.php?src=assets/property_images/<?php echo $imageFile; ?>&w=640&h=420"></span>
									<?php } ?>
								<?php }else{ ?>
									<span class="property-card-carousel__source" data-src="./system/thumbs.php?src=assets/property_images/property.png&w=640&h=420"></span>
								<?php } ?>
							</div>
							<div class="property-card-carousel__badge">
								<span class="iconify property-card-carousel__badge-icon" data-icon="mdi:floor-plan"></span>
								<span class="property-card-carousel__badge-sep" aria-hidden="true"></span>
								<span class="iconify property-card-carousel__badge-icon" data-icon="mdi:camera-outline"></span>
								<span class="property-card-carousel__count">1/<?php if($property['images']){ echo count($propertyImages); }else{ echo "1"; } ?></span>
							</div>
							<div class="property-card-carousel__layout">
								<div class="property-card-carousel__pane property-card-carousel__pane--main">
									<div class="property-card-image"></div>
									<button type="button" class="property-card-carousel__nav property-card-carousel__nav--prev" aria-label="Previous image">
										<span class="iconify" data-icon="mdi:chevron-left" aria-hidden="true"></span>
									</button>
									<button type="button" class="property-card-carousel__nav property-card-carousel__nav--next" aria-label="Next image">
										<span class="iconify" data-icon="mdi:chevron-right" aria-hidden="true"></span>
									</button>
								</div>
								<div class="property-card-carousel__pane property-card-carousel__pane--thumbs">
									<div class="property-card-carousel__thumb">
										<div class="property-card-image"></div>
									</div>
									<div class="property-card-carousel__thumb">
										<div class="property-card-image"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="property-card-price-strip">
							<div class="property-card-price"><?php if($property['rent_pcm'] == "0.00") { echo "TBA"; }else{ echo "&pound;" . $property['rent_pcm'] . " pcm"; } ?></div>
						</div>
					</div>
					<div class="card-body property-card__content d-flex flex-column">
						<div class="property-card__summary">
							<div class="property-card-tags property-card-tags--top">
								<?php if($property['same_day_move'] == "1"){ ?>
								<span class="property-card-tag property-card-tag--sameday"><span class="iconify" data-icon="mdi:truck-fast-outline"></span> Same day</span>
								<?php } ?>
								<?php if($property['status'] == 0){ ?>
									<span class="property-card-tag property-card-tag--status property-card-tag--status-available">Available</span>
								<?php }elseif($property['status'] == 1){ ?>
									<span class="property-card-tag property-card-tag--status property-card-tag--status-reserved">Reserved</span>
								<?php }elseif($property['status'] == 2){ ?>
									<span class="property-card-tag property-card-tag--status property-card-tag--status-held">Held</span>
								<?php }elseif($property['status'] == 3){ ?>
									<span class="property-card-tag property-card-tag--status property-card-tag--status-offer">Under Offer</span>
								<?php }elseif($property['status'] == 4){ ?>
									<span class="property-card-tag property-card-tag--status property-card-tag--status-let">Let</span>
								<?php } ?>
							</div>
							<div class="property-card-address property-card-address--rightmove">
								<div class="property-card-address-line"><?php echo $property['address1']; ?></div>
								<div class="property-card-address-postcode"><?php echo $property['postcode']; ?></div>
							</div>
							<div class="property-card-keyinfo property-card-keyinfo--rightmove">
								<?php if(!empty($property['property_type']) && isset($propertyTypes[$property['property_type']])){ ?>
								<span class="property-card-stat property-card-stat--type"><?php echo $propertyTypes[$property['property_type']]; ?></span>
								<span class="property-card-keyinfo__sep" aria-hidden="true"></span>
								<?php } ?>
								<span class="property-card-stat"><span class="iconify" data-icon="mdi:bed-king-outline"></span><?php echo $property['bedrooms']; ?></span>
								<span class="property-card-keyinfo__sep" aria-hidden="true"></span>
								<span class="property-card-stat"><span class="iconify" data-icon="mdi:bathtub-outline"></span><?php echo $property['bathrooms']; ?></span>
							</div>
							<?php if(!empty(trim($property['notes']))){ ?>
							<p class="property-card-description"><?php echo strip_tags($property['notes']); ?></p>
							<?php } ?>
						</div>
						<div class="property-card__footer">
							<div class="property-card-meta"><span class="iconify" data-icon="mdi:clock-outline"></span>Added <?php echo time_elapsed_string($property['listed']); ?></div>
							<div class="property-actions d-flex">
								<a href="view-property.php?id=<?php echo $property['id']; ?>" class="btn btn-sm property-card-btn property-card-btn--view"><span class="iconify" data-icon="mdi:eye-outline"></span>View</a>
							</div>
						</div>
					</div>
				</article>
			</div>
		</div>
		<div class="card admin-crm-panel mb-4">
			<div class="card-header">
				<span class="iconify" data-icon="mdi:clipboard-text-outline"></span>
				<h5>Offer details</h5>
			</div>
			<div class="card-body">
		<p class="text-muted mb-4">Please fill this form out as completely as possible. A member of the team will approve your offer shortly after submission, so please check your inbox.</p>
		<div class="admin-crm-section">
			<h6 class="admin-crm-section__title"><span class="iconify" data-icon="mdi:account-tie-outline"></span>Council &amp; officer</h6>
			<div class="row g-3">
			<div class="col-md-4">
				<label for="council_id" class="admin-crm-label">Council</label>
				<?php
					$councils = getAllCouncils(); 
				?>
				<select name="council_id" class="form-control admin-crm-control" required>
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
			<div class="col-md-4">
				<label for="officer_name" class="admin-crm-label">Officer Name</label>
				<input type="text" name="officer_name" class="form-control admin-crm-control" required placeholder="Officer name" />
			</div>
			<div class="col-md-4">
				<label for="officer_telephone" class="admin-crm-label">Officer Telephone</label>
				<input type="text" name="officer_telephone" class="form-control admin-crm-control" required placeholder="Officer telephone" />
			</div>
			<div class="col-md-4">
				<label for="officer_email" class="admin-crm-label">Officer Email</label>
				<input type="email" name="officer_email" class="form-control admin-crm-control" required placeholder="Officer email" />
			</div>
			</div>
		</div>
		<div class="admin-crm-section">
			<h6 class="admin-crm-section__title"><span class="iconify" data-icon="mdi:account-outline"></span>Applicant</h6>
			<div class="row g-3">
			<div class="col-md-4">
				<label for="applicant_name" class="admin-crm-label">Applicant's Name</label>
				<input type="text" name="applicant_name" class="form-control admin-crm-control" required placeholder="Applicant name" />
			</div>
			<div class="col-md-4">
				<label for="applicant_email" class="admin-crm-label">Applicant's Email</label>
				<input type="email" name="applicant_email" class="form-control admin-crm-control" placeholder="Applicant email" />
			</div>
			<div class="col-md-4">
				<label for="applicant_telephone" class="admin-crm-label">Applicant's Phone</label>
				<input type="tel" name="applicant_telephone" class="form-control admin-crm-control" placeholder="Applicant phone" />
			</div>
			<div class="col-md-4">
				<label for="applicant_dob" class="admin-crm-label">Applicant's D.O.B</label>
				<input type="date" name="applicant_dob" class="form-control admin-crm-control" placeholder="Applicant DOB" />
			</div>
			<div class="col-md-4">
				<label for="applicant_ni_number" class="admin-crm-label">Applicant's NI No</label>
				<input type="text" name="applicant_ni_number" class="form-control admin-crm-control" placeholder="Applicant NI No" />
			</div>
			</div>
		</div>
		<div class="admin-crm-section">
			<h6 class="admin-crm-section__title"><span class="iconify" data-icon="mdi:cash-multiple"></span>Benefits &amp; travel</h6>
			<div class="row g-3">
			<div class="col-md-4">
				<label for="family_benefit_cap" class="admin-crm-label">Benefit Cap?</label>
				<select name="family_benefit_cap" class="form-control admin-crm-control" required>
					<option value="" selected disabled>Select option</option>
					<option value="1">Yes</option>
					<option value="0">No</option>
				</select>
			</div>
			<div class="col-md-4">
				<label for="benefit_status" class="admin-crm-label">Benefit Status</label>
				<input type="text" name="benefit_status" class="form-control admin-crm-control" placeholder="Benefit status" required />
			</div>
			<div class="col-md-4">
				<label for="family_travel" class="admin-crm-label">How will the family travel?</label>
				<input type="text" name="family_travel" class="form-control admin-crm-control" placeholder="How will the family travel?" required />
			</div>
			<div class="col-md-4">
				<label for="removals_required" class="admin-crm-label">Are removals required?</label>
				<select name="removals_required" class="form-control admin-crm-control" required>
					<option value="" selected disabled>Select option</option>
					<option value="1">Yes</option>
					<option value="0">No</option>
				</select>
			</div>
			</div>
		</div>
		<div class="admin-crm-section">
			<h6 class="admin-crm-section__title"><span class="iconify" data-icon="mdi:sofa-outline"></span>Items required</h6>
			<div class="row g-3">
			<div class="col-md-4">
				<label for="beds_required" class="admin-crm-label">Beds Required?</label>
				<input type="text" name="beds_required" class="form-control admin-crm-control" placeholder="Bed's required?" />
			</div>
			<div class="col-md-4">
				<label for="electric_cooker" class="admin-crm-label">Cooker Required?</label>
				<input type="text" name="electric_cooker" class="form-control admin-crm-control" placeholder="Cooker required?" />
			</div>
			<div class="col-md-4">
				<label for="fridge_freezer" class="admin-crm-label">Fridge/freezer Required?</label>
				<input type="text" name="fridge_freezer" class="form-control admin-crm-control" placeholder="Fridge/freezer required?" />
			</div>
			<div class="col-md-4">
				<label for="additional_items" class="admin-crm-label">Additional Items?</label>
				<textarea class="textarea form-control admin-crm-control" name="additional_items"></textarea>
			</div>
			</div>
		</div>
		<div class="admin-crm-section">
			<h6 class="admin-crm-section__title"><span class="iconify" data-icon="mdi:home-search-outline"></span>Property requirements</h6>
			<div class="row g-3">
			<div class="col-md-4">
				<label for="required_property_location" class="admin-crm-label">Required Property Location</label>
				<input type="text" name="required_property_location" class="form-control admin-crm-control" placeholder="Required Property Location" />
			</div>
			<div class="col-md-4">
				<label for="required_bedrooms" class="admin-crm-label">Required Bedrooms</label>
				<select name="required_bedrooms" class="form-control admin-crm-control" required>
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
			<div class="col-md-4">
				<label for="scheduled_move_date" class="admin-crm-label">Scheduled Move Date</label>
				<input type="date" name="scheduled_move_date" class="form-control admin-crm-control" placeholder="Scheduled move date" required />
			</div>
			<div class="col-md-4">
				<label for="property_held_from" class="admin-crm-label">Property Held From</label>
				<input type="date" name="property_held_from" class="form-control admin-crm-control" placeholder="Property held from" required />
			</div>
			</div>
		</div>
		<div class="admin-crm-section">
			<h6 class="admin-crm-section__title"><span class="iconify" data-icon="mdi:file-sign"></span>Tenancy &amp; incentive</h6>
			<div class="row g-3">
			<div class="col-md-4">
				<label for="tenancy_type" class="admin-crm-label">Tenancy Type</label>
				<select name="tenancy_type" class="form-control admin-crm-control" required>
					<option value="" selected disabled>Select option</option>
					<option value="AST/Nightly">AST/Nightly</option>
					<option value="EHL">EHL</option>
				</select>
			</div>
			<div class="col-md-4">
				<label for="incentive_amount" class="admin-crm-label">Incentive Amount</label>
				<input type="text" name="incentive_amount" class="form-control admin-crm-control" placeholder="Incentive amount" />
			</div>
			<div class="col-md-4">
				<label for="other_requirements" class="admin-crm-label">Other Requirements</label>
				<textarea name="other_requirements" class="textarea form-control admin-crm-control"></textarea>
			</div>
			</div>
		</div>
		<div class="admin-crm-section">
			<h6 class="admin-crm-section__title"><span class="iconify" data-icon="mdi:note-edit-outline"></span>Offer notes</h6>
			<label for="notes" class="admin-crm-label">Add notes relevant to this offer</label>
			<textarea class="textarea form-control admin-crm-control" name="notes" id="notes" placeholder="Add notes"></textarea>
		</div>
		<!--<div class="form-group row my-4 mx-2 p-2 bg-light">
			<p class="lead">Who should this form be sent to? Choose one of each from below. <strong><u>You must have added the contact first</u>.</strong></p>
			<div class="col-md-4">
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
			<div class="col-md-4">
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
			<div class="col-md-4">
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
			<div class="col-md-4">
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
		<div class="admin-crm-submit-wrap">
			<div class="btn-group">
				<button id="clearSavedData" class="btn btn-lg btn-danger">Clear Form</button>
				<button type="submit" id="save" class="btn-lg btn btn-crm-secondary">Save For Later</button>
				<button class="btn btn-crm-primary btn-lg" type="submit"><span class="iconify" data-icon="mdi:check-circle-outline"></span>Submit Offer</button>
			</div>
		</div>
			</div>
		</div>
		</form>
		</div>
<?php
include_once("views/footer.php");
?>
<script type="text/javascript" src="assets/js/form.js?ver=<?php echo time(); ?>"></script>
<script type="text/javascript" src="assets/js/property-card-carousel.js?ver=<?php echo time(); ?>"></script>
</body>
</html>
