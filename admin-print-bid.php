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
$bid = $_GET['id'];
$bid = getBid($bid);
$council = getCouncilInfo($bid['council_id']);

?>
<!DOCTYPE html>
<head>
<title>Print Bid</title>
<style type="text/css">

</style>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-sm-4 text-right">
			<img src="assets/img/Reloc8Logo.jpg" width="100" class="mr-2">
		</div>
		<div class="col-sm-8 text-left">
			<h4 class="display-4 pt-2">Booking Form</h4>
			<h5>(Out of London Moves)</h5>
		</div>
	</div>
</div>
<div class="container mt-4">
	<div class="row">
		<div class="col-sm-10 mx-auto">
			<div class="row">
				<div class="col-sm-5 mx-auto">
					<p class="lead">Officer & Council Details</p>
					<div class="table-responsive">
						<table class="table table-borderless" cols="4">
							<tr>
								<th colspan="1" style="width:45%;">Council</th>
								<td colspan="3"><?php echo $council['council_name']; ?></td>
							</tr>
							<tr>
								<th colspan="1" style="width:45%;">Officers Name</th>
								<td colspan="3"><?php echo $bid['officer_name']; ?></td>
							</tr>
							<tr>
								<th colspan="1" style="width:45%;">Officers Telephone</th>
								<td colspan="3"><?php echo $bid['officer_telephone']; ?></td>
							</tr>
							<tr>
								<th colspan="1" style="width:45%;">Officers Email</th>
								<td colspan="3"><?php echo $bid['officer_email']; ?></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="col-sm-5 mx-auto">
					<p class="lead">Main Applicant Details</p>
					<div class="table-responsive">
						<table class="table table-borderless" cols="4">
							<tr>
								<th colspan="1" style="width:45%;">Name</th>
								<td colspan="3"><?php echo $bid['applicant_name']; ?></td>
							</tr>
							<tr>
								<th colspan="1" style="width:45%;">Email</th>
								<td colspan="3"><?php echo $bid['applicant_email']; ?></td>
							</tr>
							<tr>
								<th colspan="1" style="width:45%;">Telephone</th>
								<td colspan="3"><?php echo $bid['applicant_telephone']; ?></td>
							</tr>
							<tr>
								<th colspan="1" style="width:45%;">Date of Birth</th>
								<td colspan="3"><?php echo date("d/m/Y",strtotime($bid['applicant_dob'])); ?></td>
							</tr>
							<tr>
								<th colspan="1" style="width:45%;">NI Number</th>
								<td colspan="3"><?php echo $bid['applicant_ni_number']; ?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-10 mx-auto">
			<div class="row">
				<div class="col-sm-5 mx-auto">
					<p class="lead">Benefit &amp; Travel Details</p>
					<div class="table-responsive">
						<table class="table table-borderless" cols="4">
							<tr>
								<th style="width: 45%;">Is the family benefit capped?</th>
								<td><?php switch ($bid['family_benefit_cap']){
									case 0:
										echo "No";
										break;
									case 1:
										echo "Yes";
										break;
									default:
										echo "No";
										break;
								} ?></td>
							</tr>
							<tr>
								<th style="width: 45%;">Projected benefit status</th>
								<td><?php echo $bid['benefit_status']; ?></td>
							</tr>
							<tr>
								<th style="width: 45%;">How will the family travel?</th>
								<td><?php echo $bid['family_travel']; ?></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="col-sm-5 mx-auto">
					<p class="lead">Furniture Required</p>
					<div class="table-responsive">
						<table class="table table-borderless" cols="4">
							<tr>
								<th style="width: 45%;">Beds? If so, how many?</th>
								<td><?php echo $bid['beds_required']; ?></td>
							</tr>
							<tr>
								<th style="width: 45%;">Electric cooker</th>
								<td><?php echo $bid['electric_cooker']; ?></td>
							</tr>
							<tr>
								<th style="width: 45%;">Fridge/freezer</th>
								<td><?php echo $bid['fridge_freezer']; ?></td>
							</tr>
							<tr>
								<th style="width: 45%;">Additional items</th>
								<td><?php echo $bid['additional_items']; ?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-10 mx-auto">
			<div class="row">
				<div class="col-sm-5 mx-auto">
					<p class="lead">Required Property Details</p>
					<div class="table-responsive">
						<table class="table table-borderless" cols="4">
							<tr>
								<th style="width: 45%;">Required property location</th>
								<td><?php echo $bid['required_property_location']; ?></td>
							</tr>
							<tr>
								<th style="width: 45%;">Required bedrooms</th>
								<td><?php echo $bid['required_bedrooms']; ?></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="col-sm-5 mx-auto">
					<p class="lead">New Property Details</p>
					<div class="table-responsive">
						<table class="table table-borderless" cols="4">
							<tr>
								<th style="width: 45%;">Property address of move</th>
								<td><?php echo $bid['address1'] . ', ' . $bid['address2'] . ', ' . $bid['town'] . ', ' . $bid['county'] . ', ' . $bid['postcode']; ?></td>
							</tr>
							<tr>
								<th style="width: 45%;">Scheduled move in date</th>
								<td><?php echo date('d/m/Y',strtotime($bid['scheduled_move_date'])); ?></td>
							</tr>
							<tr>
								<th style="width: 45%;">Property held from</th>
								<td><?php echo date('d/m/Y',strtotime($bid['property_held_from'])); ?></td>
							</tr>
							<tr>
								<th style="width: 45%;">Incentive amount</th>
								<td><?php echo $bid['incentive_amount']; ?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row py-4 text-center">
		<div class="col-12">
			<p class="d-block text-center">Unit 11 Bingswood Avenue, Whaley Bridge, High Peak, SK23 7LY</p>
		</div>
		<div class="col-12">
			<small class="d-block mx-auto text-center">&copy; <?php echo date('Y'); ?>. Midland Relocations Limited. Printed at <?php echo date('H:i d/m/Y'); ?>.</small>
		</div>
	</div>
</body>
</html>