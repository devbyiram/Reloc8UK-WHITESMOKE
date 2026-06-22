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
?>
<!DOCTYPE html>
<head>
<title>Reloc8UK Admin - Add Property</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
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
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js"></script>
<script src="https://cdn.getaddress.io/scripts/getaddress-autocomplete-1.1.3.min.js">
</script>
</head>
<body class="bg-light">
<?php
include_once("views/navbar.php");
?>
	<main role="main" class="container">
		<div class="my-3 p-3 bg-white rounded shadow-sm">
			<h4 class="border-bottom border-gray pb-2 mb-0">Add Property</h4>
			<p class="lead">Add a new property to the Reloc8UK property portal. Fill in all required fields as a minimum.</p>
		</div>
		<div class="my-3 p-3 bg-white rounded shadow-sm">
			<h6 class="border-bottom border-gray pb-2 mb-0">Property Details</h6>
				<div class="form-group row my-2">
					<div class="col-sm-12">
						<form method="post" action="admin-add-property.php">
						    <div class="form-group row my-2">
						        <div class="col-md-4 col-sm-6">
        						    <label>First Address Line</label>
                                    <input id="formatted_address_0" name="line_1" type="text" class="form-control">
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <label>Second Address Line</label>
                                    <input id="formatted_address_1" name="line_2" type="text" class="form-control">
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <label>Third Address Line</label>
                                    <input id="formatted_address_2" name="line_3" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row my-2">
                                <div class="col-md-4 col-sm-6">
                                    <label>Town</label>
                                    <input id="town_or_city" name="town_or_city" type="text" class="form-control">
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <label>County</label>
                                    <input id="county" name="county" type="text" class="form-control">
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <label>Postcode</label>
                                    <input id="postcode" name="postcode" type="text" class="form-control">
                                </div>
                            </div>
						<script>
                            getAddress.autocomplete('formatted_address_0','lQT30nDJI0edlwoTuJUevA37690');
                        </script>
					</div>
				</div>
			
				<div class="bg-light my-2 p-2">
					<h6>Tenancy Information</h6>
					<div class="form-group row my-2">
						<div class="col-md-2 col-sm-12">
							<label for="property_type">Property Type</label>
							<select name="property_type" class="form-control" required>
								<?php
									global $propertyTypes;
									
									foreach($propertyTypes as $key=>$value){
										echo '<option value="'.$key.'">'.$value.'</option>';
									}
								?>
							</select>
						</div>
						<div class="col-md-2 col-sm-12">
							<label for="tenancy_type">Tenancy Type</label>
							<select name="tenancy_type" class="form-control" required>
								<option value="" selected disabled>-- Select type --</option>
								<option value="6 Month AST">6 Month AST</option>
								<option value="12 Month AST">12 Month AST</option>
								<option value="18 Month AST">18 Month AST</option>
								<option value="24 Month AST">24 Month AST</option>
							</select>
						</div>
						<div class="col-md-2 col-sm-12">
							<label for="same_day_move">Same Day Move</label>
							<select name="same_day_move" class="form-control" required>
								<option value="" selected disabled>-- Select --</option>
								<option value="0">No</option>
								<option value="1">Yes</option>
							</select>
						</div>
						<div class="col-md-2 col-sm-12">
							<label for="date_available">Date Available</label>
							<input type="date" class="form-control" placeholder="Date Available" name="date_available">
							<label for="available_soon"><input type="checkbox" name="available_soon" value="1"> Available Soon</label>
						</div>
						<div class="col-md-2 col-sm-12">
							<label for="reference">Reference</label>
							<input type="text" class="form-control" placeholder="Reference" name="reference">
						</div>
						<div class="col-md-2 col-sm-12">
							<label for="rent_pcm">Rent PCM</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text" id="pound-sign-addon">&pound;</span>
								</div>
								<input type="number" class="form-control" placeholder="Rent PCM" name="rent_pcm" aria-label="Rent PCM" aria-describedby="pound-sign-addon" required>
							</div>
						</div>
					</div>
				</div>
				<div class="bg-light my-2 p-2">
					<h6>Property Attributes</h6>
					<div class="form-group row my-2">
						
						<div class="col-md-2 col-sm-6">
							<label for="bedrooms">Bedrooms</label>
							<select class="form-control" name="bedrooms" required>
								<option value="" disabled selected>-- Bedrooms --</option>
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
						<div class="col-md-2 col-sm-6">
							<label for="bathrooms">Bathrooms</label>
							<select class="form-control" name="bathrooms" required>
								<option value="" disabled selected>-- Bathrooms --</option>
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
						<div class="col-md-2 col-sm-6">
							<label for="furniture_included">Furniture Included</label>
							<select class="form-control" name="furniture_included" required>
								<option value="" disabled selected>-- Furniture --</option>
								<option value="0">No</option>
								<option value="1">Yes</option>
							</select>
						</div>
						<div class="col-md-2 col-sm-6">
							<label for="front_garden">Front Garden</label>
							<select name="front_garden" class="form-control" required>
								<option value="" disabled selected>-- Front Garden --</option>
								<option value="0">No</option>
								<option value="1">Yes</option>
							</select>
						</div>
						<div class="col-md-2 col-sm-6">
							<label for="rear_garden">Rear Garden</label>
							<select name="rear_garden" class="form-control" required>
								<option value="" disabled selected>-- Rear Garden --</option>
								<option value="0">No</option>
								<option value="1">Yes</option>
							</select>
						</div>
						<div class="col-md-2 col-sm-6">
							<label for="central_heating">Central Heating</label>
							<select name="central_heating" class="form-control" required>
								<option value="" disabled selected>-- Central Heating --</option>
								<option value="0">No</option>
								<option value="1">Yes</option>
							</select>
						</div>
					</div>
				</div>
				<div class="bg-light my-2 p-2">
					<div class="form-group row my-2">
						<div class="col-md-12">
							<label for="notes">Additional Information</label>
							<textarea name="notes" class="form-control"></textarea>
						</div>
					</div>
				</div>
				<hr>
				<h6 class="border-bottom border-gray pb-2 mb-0">Property Images</h6>
				<div class="form-group row my-2">
					<div class="col-md-12">
						<i>Please save the property before adding images</i>
					</div>
				</div>
				<!--<div class="form-group row my-2">
					<div class="col-md-12">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="customFile" multiple disabled>
							<label class="custom-file-label" for="customFile">Choose images</label>
						</div>
					</div>
				</div>-->
				<hr>
				<h6 class="border-bottom border-gray pb-2 mb-0">Property EPC</h6>
				<div class="form-group row my-2">
					<div class="col-md-12">
						<i>Please save the property before adding an EPC</i>
					</div>
				</div>
				<!--<div class="form-group row my-2">
					<div class="col-md-12">
						<div class="custom-file">
							<input type="file" class="custom-file-input" name="property_epc" id="property_epc" disabled>
							<label class="custom-file-label" for="property_epc">Choose File</label>
						</div>
					</div>
				</div>-->
				<div class="form-group row my-2 d-flex justify-content-center">
					<input type="hidden" name="add_property" value="1">
					<button type="submit" class="btn btn-lg btn-dark d-block">Add Property</button>
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
    $('#propertyResults').DataTable({
		responsive:true
	});
	$('#userResults').DataTable({
		responsive:true
	});
	$("#select_address").on('change',function(e){
		var line1, line2, line3, town, county, postcode, fulladdress;
		
		line1 = $(this).find(':selected').attr('data-address1')
		line2 = $(this).find(':selected').attr('data-address2')
		line3 = $(this).find(':selected').attr('data-address3')
		town = $(this).find(':selected').attr('data-town')
		county = $(this).find(':selected').attr('data-county')
		postcode = $(this).find(':selected').attr('data-postcode')
		fulladdress = $(this).find(':selected').attr('data-fulladdress')
		
		
		$("#address1").val(line1);
		$("#address2").val(line2);
		$("#address3").val(line3);
		$("#town").val(town);
		$("#county").val(county);
		$("#postcode").val(postcode);
		
		$(".hiddenUntilSelected").removeClass("d-none").show("slow");
		$(".selectedAddress .text").html("Address selected: " + fulladdress + ".<br/> Need to manually edit? <a href='#' class='manualEditAddress'>Click here</a>");
		
	});
} );
$(document).on("click", '.manualEditAddress', function(event) { 
    event.preventDefault();
		
	$(".hiddenUnlessManual").removeClass("d-none").show("slow");
});
</script>
</body>
</html>