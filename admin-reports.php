<?php
require('system/config.php');
 
$page = "reports";
$councils = getAllCouncils();

if($_POST){
	$report = generateUserReport($_POST['council'],$_POST['date-from'],$_POST['date-to'],$_POST['date-range-predefined'],$_POST['export_to_csv']);
}

?>
<!DOCTYPE html>
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Reports - Reloc8UK Portal</title>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link type="text/css" rel="stylesheet" href="assets/css/flexslider.css" />                  
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="assets/js/jquery.flexslider.js"></script>
<style type="text/css">
#primary-nav {
  background-color: #545659;
  color: white;
}
#primary-nav a {
	color: white;
}
.flex-direction-nav a {
	height: 45px !important;
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/font/css/open-iconic-bootstrap.min.css" integrity="sha256-BJ/G+e+y7bQdrYkS2RBTyNfBHpA9IuGaPmf9htub5MQ=" crossorigin="anonymous" />
<link rel="stylesheet" type="text/css" href="assets/css/styles.css?ver=<?php echo time(); ?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.1/js/lightbox.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.1/css/lightbox.min.css" />
</head>
<body class="bg-light">
<?php if(empty($_GET['share']) && $_GET['share'] != "true") { ?>
<?php
include_once("views/navbar.php");
?>
<?php } ?>
	<main class="container">
		<form method="post" action="admin-reports.php">
		<div class="my-3 p-3 bg-white rounded shadow-sm">
			<div class="row">
				<div class="col-md-12">
					<h2 class="display-4">Reports</h2>
					<p>Select an option from below to generate a report of users registered within a given timeframe for either an individual council, or all councils combined.</p>
				</div>
				<div class="col-md-4">
					<label for="council">Select council</label>
					<select name="council" id="council" class="form-control">
						<option value="all">All Councils</option>
						<?php
							foreach($councils as $council){
								$group[$council['council_group']][] = $council;
							}
							foreach($group as $key=>$values){
								echo '<optgroup label="'.$key.'">';
								 foreach ($values as $value) 
								 {
									 echo '<option value="'.$value['id'].'"';
									 if($_POST['council'] == $value['id']){ echo 'selected'; }
									 echo '>'.$value['council_name'].'</option>';
								 }
								 echo '</optgroup>';
							}
						?>
					</select>
				</div>
				<div class="col-md-4">
						<label for="date-range">Date Range</label>
					<div class="input-group" id="date-range">
					  <input type="date" aria-label="Date from" name="date-from" value="<?php if(!empty($_POST['date-from'])){ echo $_POST['date-from']; } ?>" class="form-control">
					  <input type="date" aria-label="Date to" name="date-to" class="form-control" value="<?php if(!empty($_POST['date-to'])){ echo $_POST['date-to']; } ?>">
					</div>
				</div>
				<div class="col-md-4">
						<label for="date-range-predefined">Date Range (predefined)</label>
						<select name="date-range-predefined" id="date-range-predefined" class="form-control">
							<option value="" selected>-- Select an option --</option>
							<option value="1" <?php if($_POST['date-range-predefined'] == 1){ echo 'selected'; } ?>>Current month</option>
							<option value="2" <?php if($_POST['date-range-predefined'] == 2){ echo 'selected'; } ?>>Last Month</option>
							<option value="3" <?php if($_POST['date-range-predefined'] == 3){ echo 'selected'; } ?>>This Year</option>
						</select>
				</div>
				<div class="col-md-12 text-center">
					<label for="export_to_csv">Export to CSV
					<input type="checkbox" name="export_to_csv" id="export_to_csv" value="1" <?php isset($_POST['export_to_csv']) ? 'checked' : ''; ?>></label><br/>
					<button type="submit" class="mt-4 btn btn-primary">Generate Report</button>
				</div>

			</div>
			<div class="col-md-12">
		<div class="table-responsive mt-4">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Email Address</th>
						<th>Contact Number</th>
						<th>Council</th>
						<th>Signed Up</th>
						<th>Reason For Move</th>
						<th>Preferred Area</th>
						<th>NI Number</th>
						<th>D.O.B</th>
						<th>Other Information</th>
						<th>Housing Officer</th>
					</tr>
				</thead>
				<tbody>
		<?php 
		if($report){
			foreach($report as $user){
		?>
			<tr>
				<td><?= $user['id']; ?></td>
				<td><?= $user['name']; ?></td>
				<td><?= $user['email_address']; ?></td>
				<td><?= $user['contact_number']; ?></td>
				<td><?= $user['council_name']; ?></td>
				<td><?= date('d/m/Y', strtotime($user['created'])); ?></td>
				<td><?= $user['reason_for_move']; ?></td>
				<td><?= $user['preferred_area']; ?></td>
				<td><?= strtoupper($user['ni_number']); ?></td>
				<td><?= date('d/m/Y', strtotime($user['date_of_birth'])); ?></td>
				<td><?= $user['other_information']; ?></td>
				<td><?= $user['housing_officer']; ?></td>
			</tr>
		<?php
			}
		}
		?>
			</tbody>
		</table>
	</div>
		</div>
	</form>
	
	</main>
	<footer>
		<div class="copyright text-center py-2">
			<small>&copy; Copyright <?php echo date("Y"); ?> Midland Relocations Limited.</small>
		</div>
	</footer>
<script type="text/javascript">
$(document).ready(function(){
	$(function () {
	  $('[data-toggle="tooltip"]').tooltip()
	})
});
</script>
<script>
$(window).load(function() {
  $('#slider').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: true,
    slideshow: false,
    sync: "#carousel"
  });
  $('#carousel').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: true,
    slideshow: false,
    itemWidth: 90,
    itemMargin: 5,
    asNavFor: '#slider'
  });
});
</script>
<script type="text/javascript" src="https://milankyncl.github.io/jquery-copy-to-clipboard/jquery.copy-to-clipboard.js"></script>
<script type="text/javascript">
$(document).ready(function() {
  $('.copyToClipboard').CopyToClipboard();
});
</script>
</body>
</html>