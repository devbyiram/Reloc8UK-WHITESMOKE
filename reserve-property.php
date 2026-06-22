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

if(isset($_POST['status']) && $_POST['status'] == 1){
	$pid	=	$_POST['pid'];
	$uid	=	$_SESSION['user_id'];
	$status	=	1;
	$notes	=	$_POST['notes'];
	
	if(updatePropertyStatus($pid,$uid,$status,$notes)){
		$user = getUser($uid);
		$prop = getProperty($pid);
		notifyAdmin("reserved",$user,$prop,$notes);
		header("Location: my-bids.php");
	}
}

$hasReservedBefore = hasReservedBefore($_SESSION['user_id'],$_GET['id']);

$property = getProperty($_GET['id']);
$propertyImages = explode(",",$property['images']);
$page = "mybids";
$page_title = "Reloc8UK Portal Login";
include_once("views/header.php");
?>
<main class="portal-main container">
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<input type="hidden" name="pid" value="<?php echo $property['id']; ?>">
	<input type="hidden" name="uid" value="<?php echo $_SESSION['user_id']; ?>">
	<input type="hidden" name="status" value="1">
	<div class="portal-card mb-4">
		<div class="portal-card-header">
			<h4>Reserve a property</h4>
		</div>
		<div class="portal-card-body">
			<p class="lead mb-0">Please confirm that you would like to reserve the property listed below for 24 hours. After 24 hours the reservation will automatically expire and the property will become available again. You can hold no more than 3 reservations for a single property within a 1 month window.</p>
		</div>
	</div>
	<div class="portal-card mb-4">
		<div class="portal-card-body">
			<p class="lead mb-0">You have reserved this property <b><?php echo count($hasReservedBefore); ?></b> time(s) before.</p>
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
			</div>
		</div>
		</div>
	</div>
	<div class="portal-card mb-4">
		<div class="portal-card-body">
		<div class="form-group row my-2 d-flex justify-content-center">
			<div class="col-md-12">
				<p class="lead">Add notes relevant to this reservation</p>
				<textarea class="textarea form-control" name="notes" placeholder="Add notes"></textarea>
			</div>
		</div>
		<div class="form-group row my-2 d-flex justify-content-center">
			<button class="btn btn-secondary btn-lg d-block">Reserve Property</button>
		</div>
		</div>
	</div>
	</form>
</main>
<?php
include_once("views/footer.php");
?>
</body>
</html>
