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

if(isset($_POST['postcode_lookup']) && $_POST['postcode_lookup'] == 1){
	
	$postcode = $_POST['postcode'];
	
	$results = lookuppostcode($postcode);

}

if(isset($_POST['add_property']) && $_POST['add_property'] == 1){
	
	$address1 = $_POST['line_1'];
	$address2 = $_POST['line_2'];
	$address3 = $_POST['line_3'];
	$town = $_POST['town_or_city'];
	$county = $_POST['county'];
	$postcode = $_POST['postcode'];
	$council = $_POST['council'];
	$date_available = $_POST['date_available'];
	$reference = $_POST['reference'];
	$bedrooms = $_POST['bedrooms'];
	$bathrooms = $_POST['bathrooms'];
	$furniture_included = $_POST['furniture_included'];
	$garden_front = $_POST['front_garden'];
	$garden_rear = $_POST['rear_garden'];
	$central_heating = $_POST['central_heating'];
	$notes = $_POST['notes'];
	$rent_pcm = $_POST['rent_pcm'];
	$tenancy_type = $_POST['tenancy_type'];
	$property_type = $_POST['property_type'];
	$same_day_move = $_POST['same_day_move'];
	$available_soon = $_POST['available_soon'];
	
	if(isset($available_soon)){
	    $status = 5;
	}else{
	    $status = 1;
	}
	
	// Set query in to $sql variable
	$sql = "INSERT INTO properties (`status`,`address1`,`address2`,`address3`,`town`,`county`,`postcode`,`council_id`,`date_available`,`reference`,`bedrooms`,`bathrooms`,`furniture_included`,`garden_front`,`garden_rear`,`central_heating`,`notes`,`rent_pcm`, `tenancy_type`, `property_type`, `same_day_move`,`available_soon`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
	// Prepare & execute query using $pdo global
	$pdo->prepare($sql)->execute([$status,$address1, $address2, $address3, $town, $county, $postcode, $council, $date_available, $reference, $bedrooms, $bathrooms, $furniture_included, $garden_front, $garden_rear, $central_heating, $notes,$rent_pcm, $tenancy_type, $property_type, $same_day_move, $available_soon]);
	
	$pid = $pdo->lastInsertId();
	
	notifyAllCouncils("new-property",$pid);
	
	header('Location: admin-edit-property.php?id='.$pid);
}

$properties = getAllProperties();
$users = getUsers();
$page = "admin";
$page_title = 'Reloc8UK Admin - Add Property';
$portal_extra_head = '<link rel="stylesheet" type="text/css" href="assets/css/admin-crm-forms.css?ver='.time().'">
<script src="https://cdn.getaddress.io/scripts/getaddress-autocomplete-1.1.3.min.js"></script>';
include_once("views/header.php");
?>
		<div class="admin-crm-page">
		<div class="card admin-crm-page-header mb-4">
			<div class="card-body">
				<div class="admin-crm-page-header__row">
					<div>
						<h1 class="admin-crm-page-header__title">Add Property</h1>
						<p class="admin-crm-page-header__subtitle">Add a new property to the Reloc8UK property portal. Fill in all required fields as a minimum.</p>
					</div>
				</div>
			</div>
		</div>
		<div class="card admin-crm-panel mb-4">
			<div class="card-header">
				<span class="iconify" data-icon="mdi:home-plus-outline"></span>
				<h5>Property details</h5>
			</div>
			<div class="card-body">
			<form method="post" action="admin-add-property.php">
				<div class="admin-crm-section">
					<h6 class="admin-crm-section__title"><span class="iconify" data-icon="mdi:map-marker-outline"></span>Address</h6>
					<div class="row g-3">
						<div class="col-md-4 col-sm-6">
							<label for="formatted_address_0" class="admin-crm-label">First Address Line</label>
							<input id="formatted_address_0" name="line_1" type="text" class="form-control admin-crm-control">
						</div>
						<div class="col-md-4 col-sm-6">
							<label for="formatted_address_1" class="admin-crm-label">Second Address Line</label>
							<input id="formatted_address_1" name="line_2" type="text" class="form-control admin-crm-control">
						</div>
						<div class="col-md-4 col-sm-6">
							<label for="formatted_address_2" class="admin-crm-label">Third Address Line</label>
							<input id="formatted_address_2" name="line_3" type="text" class="form-control admin-crm-control">
						</div>
						<div class="col-md-4 col-sm-6">
							<label for="town_or_city" class="admin-crm-label">Town</label>
							<input id="town_or_city" name="town_or_city" type="text" class="form-control admin-crm-control">
						</div>
						<div class="col-md-4 col-sm-6">
							<label for="county" class="admin-crm-label">County</label>
							<input id="county" name="county" type="text" class="form-control admin-crm-control">
						</div>
						<div class="col-md-4 col-sm-6">
							<label for="postcode" class="admin-crm-label">Postcode</label>
							<input id="postcode" name="postcode" type="text" class="form-control admin-crm-control">
						</div>
					</div>
				</div>
				<div class="admin-crm-section">
					<h6 class="admin-crm-section__title"><span class="iconify" data-icon="mdi:file-document-outline"></span>Tenancy information</h6>
					<div class="row g-3">
						<div class="col-md-4 col-sm-12">
							<label for="property_type" class="admin-crm-label">Property Type</label>
							<select name="property_type" id="property_type" class="form-select admin-crm-control" required>
								<?php
									global $propertyTypes;
									foreach($propertyTypes as $key=>$value){
										echo '<option value="'.$key.'">'.$value.'</option>';
									}
								?>
							</select>
						</div>
						<div class="col-md-4 col-sm-12">
							<label for="tenancy_type" class="admin-crm-label">Tenancy Type</label>
							<select name="tenancy_type" id="tenancy_type" class="form-select admin-crm-control" required>
								<option value="" selected disabled>Select type</option>
								<option value="6 Month AST">6 Month AST</option>
								<option value="12 Month AST">12 Month AST</option>
								<option value="18 Month AST">18 Month AST</option>
								<option value="24 Month AST">24 Month AST</option>
							</select>
						</div>
						<div class="col-md-4 col-sm-12">
							<label for="same_day_move" class="admin-crm-label">Same Day Move</label>
							<select name="same_day_move" id="same_day_move" class="form-select admin-crm-control" required>
								<option value="" selected disabled>Select</option>
								<option value="0">No</option>
								<option value="1">Yes</option>
							</select>
						</div>
						<div class="col-md-4 col-sm-12">
							<label for="date_available" class="admin-crm-label">Date Available</label>
							<input type="date" class="form-control admin-crm-control" placeholder="Date Available" name="date_available" id="date_available">
							<label class="admin-crm-export-label mt-2" for="available_soon">
								<input type="checkbox" name="available_soon" id="available_soon" value="1">
								<span>Available Soon</span>
							</label>
						</div>
						<div class="col-md-4 col-sm-12">
							<label for="reference" class="admin-crm-label">Reference <small class="text-muted">(If applicable)</small></label>
							<input type="text" class="form-control admin-crm-control" placeholder="Reference" name="reference" id="reference">
						</div>
						<div class="col-md-4 col-sm-12">
							<label for="rent_pcm" class="admin-crm-label">Rent PCM</label>
							<div class="input-group">
								<span class="input-group-text" id="pound-sign-addon">&pound;</span>
								<input type="number" class="form-control admin-crm-control" placeholder="Rent PCM" name="rent_pcm" id="rent_pcm" aria-label="Rent PCM" aria-describedby="pound-sign-addon" required>
							</div>
						</div>
					</div>
				</div>
				<div class="admin-crm-section">
					<h6 class="admin-crm-section__title"><span class="iconify" data-icon="mdi:home-search-outline"></span>Property attributes</h6>
					<div class="row g-3">
						<div class="col-md-4 col-sm-6">
							<label for="bedrooms" class="admin-crm-label">Bedrooms</label>
							<select class="form-select admin-crm-control" name="bedrooms" id="bedrooms" required>
								<option value="" disabled selected>Bedrooms</option>
								<?php
									for ($x = 1; $x <= 8; $x++) {
										echo '<option value="'.$x.'">'.$x.'</option>';
									}
								?>
							</select>
						</div>
						<div class="col-md-4 col-sm-6">
							<label for="bathrooms" class="admin-crm-label">Bathrooms</label>
							<select class="form-select admin-crm-control" name="bathrooms" id="bathrooms" required>
								<option value="" disabled selected>Bathrooms</option>
								<?php
									for ($x = 1; $x <= 8; $x++) {
										echo '<option value="'.$x.'">'.$x.'</option>';
									}
								?>
							</select>
						</div>
						<div class="col-md-4 col-sm-6">
							<label for="furniture_included" class="admin-crm-label">Furniture Included</label>
							<select class="form-select admin-crm-control" name="furniture_included" id="furniture_included" required>
								<option value="" disabled selected>Furniture</option>
								<option value="0">No</option>
								<option value="1">Yes</option>
							</select>
						</div>
						<div class="col-md-4 col-sm-6">
							<label for="front_garden" class="admin-crm-label">Front Garden</label>
							<select name="front_garden" id="front_garden" class="form-select admin-crm-control" required>
								<option value="" disabled selected>Front Garden</option>
								<option value="0">No</option>
								<option value="1">Yes</option>
							</select>
						</div>
						<div class="col-md-4 col-sm-6">
							<label for="rear_garden" class="admin-crm-label">Rear Garden</label>
							<select name="rear_garden" id="rear_garden" class="form-select admin-crm-control" required>
								<option value="" disabled selected>Rear Garden</option>
								<option value="0">No</option>
								<option value="1">Yes</option>
							</select>
						</div>
						<div class="col-md-4 col-sm-6">
							<label for="central_heating" class="admin-crm-label">Central Heating</label>
							<select name="central_heating" id="central_heating" class="form-select admin-crm-control" required>
								<option value="" disabled selected>Central Heating</option>
								<option value="0">No</option>
								<option value="1">Yes</option>
							</select>
						</div>
					</div>
				</div>
				<div class="admin-crm-section">
					<h6 class="admin-crm-section__title"><span class="iconify" data-icon="mdi:text-box-outline"></span>Additional details</h6>
					<div class="row g-3">
						<div class="col-12">
							<label for="notes" class="admin-crm-label">Additional Information</label>
							<textarea name="notes" id="notes" class="form-control admin-crm-control"></textarea>
						</div>
					</div>
				</div>
				<div class="admin-crm-submit-wrap admin-crm-submit-wrap--end">
					<input type="hidden" name="add_property" value="1">
					<button type="submit" class="btn btn-crm-primary btn-lg"><span class="iconify" data-icon="mdi:plus-circle-outline"></span>Add Property</button>
				</div>
			</form>
			</div>
		</div>
		<div class="card admin-crm-panel mb-4">
			<div class="card-header">
				<span class="iconify" data-icon="mdi:leaf-circle-outline"></span>
				<h5>Energy Performance Certificate</h5>
			</div>
			<div class="card-body">
				<label class="admin-crm-label">Energy Performance Certificate</label>
				<p class="admin-crm-field-hint mb-0">Please save the property before adding an energy performance certificate.</p>
			</div>
		</div>
		<div class="card admin-crm-panel mb-4">
			<div class="card-header">
				<span class="iconify" data-icon="mdi:image-multiple-outline"></span>
				<h5>Property images</h5>
			</div>
			<div class="card-body">
				<p class="admin-crm-field-hint mb-0">Please save the property before adding images.</p>
			</div>
		</div>
		</div>
<?php include_once("views/footer.php"); ?>
<script type="text/javascript">
$(document).ready(function() {
	if (typeof getAddress !== 'undefined') {
		getAddress.autocomplete('formatted_address_0','lQT30nDJI0edlwoTuJUevA37690');
	}

	$("#select_address").on('change',function(e){
		var line1, line2, line3, town, county, postcode, fulladdress;

		line1 = $(this).find(':selected').attr('data-address1');
		line2 = $(this).find(':selected').attr('data-address2');
		line3 = $(this).find(':selected').attr('data-address3');
		town = $(this).find(':selected').attr('data-town');
		county = $(this).find(':selected').attr('data-county');
		postcode = $(this).find(':selected').attr('data-postcode');
		fulladdress = $(this).find(':selected').attr('data-fulladdress');

		$("#address1").val(line1);
		$("#address2").val(line2);
		$("#address3").val(line3);
		$("#town").val(town);
		$("#county").val(county);
		$("#postcode").val(postcode);

		$(".hiddenUntilSelected").removeClass("d-none").show("slow");
		$(".selectedAddress .text").html("Address selected: " + fulladdress + ".<br/> Need to manually edit? <a href='#' class='manualEditAddress'>Click here</a>");
	});
});
$(document).on("click", '.manualEditAddress', function(event) {
	event.preventDefault();
	$(".hiddenUnlessManual").removeClass("d-none").show("slow");
});
</script>