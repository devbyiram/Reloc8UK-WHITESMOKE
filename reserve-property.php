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
						<h1 class="admin-crm-page-header__title">Reserve a property</h1>
						<p class="admin-crm-page-header__subtitle">Confirm your 24 hour reservation request for this property.</p>
						<span class="admin-crm-page-header__meta"><span class="iconify" data-icon="mdi:calendar-clock"></span>24 hour reservation</span>
					</div>
					<a href="view-property.php?id=<?php echo $property['id']; ?>" class="btn btn-crm-secondary"><span class="iconify" data-icon="mdi:eye-outline"></span>View Property</a>
				</div>
			</div>
		</div>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input type="hidden" name="pid" value="<?php echo $property['id']; ?>">
		<input type="hidden" name="uid" value="<?php echo $_SESSION['user_id']; ?>">
		<input type="hidden" name="status" value="1">
		<div class="card admin-crm-panel mb-4">
			<div class="card-header">
				<span class="iconify" data-icon="mdi:information-outline"></span>
				<h5>Reservation terms</h5>
			</div>
			<div class="card-body">
				<p class="mb-0 text-muted">Please confirm that you would like to reserve the property listed below for 24 hours. After 24 hours the reservation will automatically expire and the property will become available again. You can hold no more than 3 reservations for a single property within a 1 month window.</p>
			</div>
		</div>
		<div class="card admin-crm-panel mb-4">
			<div class="card-body">
				<div class="d-flex align-items-center gap-2">
					<span class="iconify text-success" data-icon="mdi:history" style="font-size:1.5rem;"></span>
					<p class="mb-0">You have reserved this property <b><?php echo count($hasReservedBefore); ?></b> time(s) before.</p>
				</div>
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
				<span class="iconify" data-icon="mdi:note-edit-outline"></span>
				<h5>Reservation notes</h5>
			</div>
			<div class="card-body">
				<label for="notes" class="admin-crm-label">Add notes relevant to this reservation</label>
				<textarea class="textarea form-control admin-crm-control" name="notes" id="notes" placeholder="Add notes"></textarea>
				<div class="admin-crm-submit-wrap">
					<button class="btn btn-crm-primary btn-lg" type="submit"><span class="iconify" data-icon="mdi:bookmark-check-outline"></span>Reserve Property</button>
				</div>
			</div>
		</div>
		</form>
		</div>
<?php
include_once("views/footer.php");
?>
<script type="text/javascript" src="assets/js/property-card-carousel.js?ver=<?php echo time(); ?>"></script>
</body>
</html>
