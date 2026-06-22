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

if(isset($_POST['delete-property']) && $_POST['delete-property'] == "1"){
	
	$deleteProperty = deleteProperty($_POST['pid']);
	
	if($deleteProperty){
		//$deletePropertyBids = deletePropertyBids($_POST['pid']);
		
		//if($deletePropertyBids){
			header('Location: admin.php?property_deleted=true');
		//}
	}
}

$property = getProperty($_GET['id']);
$propertyImages = explode(",",$property['images']);
$page = "admin";
?>
<!DOCTYPE html>
<head>
<title>Delete Property - Reloc8UK Portal</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<style type="text/css">
.card { border-radius: 0; }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/font/css/open-iconic-bootstrap.min.css" integrity="sha256-BJ/G+e+y7bQdrYkS2RBTyNfBHpA9IuGaPmf9htub5MQ=" crossorigin="anonymous" />
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="assets/css/styles.css?ver=<?php echo time(); ?>">
</head>
<body class="bg-light">
<?php
include_once("views/navbar.php");
?>
	<main class="container">
		<div class="my-3 p-3 bg-white rounded shadow-sm">
			<h6 class="border-bottom border-gray pb-2 mb-0 d-block">Delete Property</h6>
		</div>
		<div class="my-3 p-3 bg-white rounded shadow-sm">
			<p>Please confirm that you would like to delete the following property. This action is irreversible and all associated data relating to this property will be permanently deleted from the database.</p>
		</div>
		<div class="my-3 p-3 bg-white rounded shadow-sm border border-secondary">
			<div class="row">
				<div class="col-md-3 col-sm-12 justify-content-center">
					<img src="assets/property_images/<?php echo $propertyImages[0]; ?>" width="128" class="img-fluid mx-auto d-block">
				</div>
				<div class="col-md-9 col-sm-12">
					<p class="lead"><a href="view-property.php?id=<?php echo $property['id']; ?>"><?php echo $property['address1'] . ", " . $property['county']; ?></a></p>
					<p>Rent PCM: &pound;<?php echo $property['rent_pcm']; ?> | Bedrooms: <?php echo $property['bedrooms']; ?></p>
				</div>
			</div>
		</div>
		<div class="my-3 p-3 bg-white rounded shadow-sm">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input type="hidden" name="delete-property" value="1">
			<input type="hidden" name="pid" value="<?php echo $property['id']; ?>">
			<div class="form-group row my-2 d-flex justify-content-center">
				<button class="btn btn-secondary btn-lg d-block" type="submit">Delete Property</button>
			</div>
			</form>
		</div>
	</main>
	<footer>
		<div class="copyright text-center py-2">
			<small>&copy; Copyright <?php echo date("Y"); ?> Midland Relocations Limited.</small>
		</div>
	</footer>
</body>
</html>