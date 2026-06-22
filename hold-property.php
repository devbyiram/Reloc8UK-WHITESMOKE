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

if(isset($_POST['hold-property']) && $_POST['hold-property'] == 1){
	$pid = $_POST['pid'];
	$uid = $_POST['uid'];
	$status = $_POST['status'];
	$notes = $_POST['notes'];
	
	if($bid = updatePropertyStatus($pid,$uid,$status,$notes)){
		$user = getUser($uid);
		$prop = getProperty($pid);
		notifyAdmin("held",$user,$prop,$notes);
		notifyUserHoldApplication($bid);
		header("Location: my-bids.php");
	}
}

$hasHeldBefore = hasHeldBefore($_SESSION['user_id'],$_GET['id']);

$property = getProperty($_GET['id']);
$propertyImages = explode(",",$property['images']);
$page = "properties";
$page_title = "Reloc8UK Portal - Hold Property";
include_once("views/header.php");
?>
<main class="portal-main container">
	<div class="portal-card mb-4">
		<div class="portal-card-header">
			<h4>Hold a property</h4>
		</div>
		<div class="portal-card-body">
		<?php if(!empty($_GET['status']) && $_GET['status'] == "held"){ ?>
			<div class="alert alert-success mb-2">This property has now been held. A team member will be in touch shortly.</div>
		<?php }elseif(!empty($_GET['status']) && $_GET['status'] == "fail"){ ?>
			<div class="alert alert-danger mb-2">There was an error holding this property, please try again.</div>
		<?php } ?>
		<p class="lead mb-0">Please confirm that you would like to hold the property listed below. To finalise the holding process, a holding fee is applicable. The holding fee is equivalent to the LHA rate of the property and is charged daily.</p>
		</div>
	</div>
	<div class="portal-card mb-4">
		<div class="portal-card-body">
			<p class="lead mb-0">You have held this property <b><?php echo count($hasHeldBefore); ?></b> time(s) before.</p>
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
				<p>Rent PCM: &pound;<?php echo $property['rent_pcm']; ?> | Bedrooms: <?php echo $property['bedrooms']; ?></p>
			</div>
		</div>
		</div>
	</div>
	<div class="portal-card mb-4">
		<div class="portal-card-body">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<input type="hidden" name="hold-property" value="1">
		<input type="hidden" name="pid" value="<?php echo $property['id']; ?>">
		<input type="hidden" name="uid" value="<?php echo $_SESSION['user_id']; ?>">
		<input type="hidden" name="status" value="1">
		<div class="form-group row my-2 d-flex justify-content-center">
			<div class="col-md-12">
				<p class="lead">Add notes relevant to this hold request.</p>
				<textarea class="textarea form-control" name="notes" placeholder="Add notes"></textarea>
			</div>
		</div>
		<div class="form-group row my-2 d-flex justify-content-center">
			<button class="btn btn-secondary btn-lg d-block" type="submit">Hold Property</button>
		</div>
		</form>
		</div>
	</div>
</main>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH3GpYxpco8vJKf5p/KPvMdYzmzaVn3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C703n4wqXm0Q8n1eIz12MU+oOf23neXwbyN/Zn5JIHu" crossorigin="anonymous"></script>
</body>
</html>
