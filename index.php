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

if($_SESSION['user_type'] == 3){
	header('Location: properties.php');
}
$page = "home";
$portal_extra_head = '<link rel="stylesheet" type="text/css" href="assets/css/slick.css"/>
<link rel="stylesheet" type="text/css" href="assets/css/slick-theme.css"/>
<link rel="stylesheet" type="text/css" href="assets/css/styles.css?ver='.time().'">
<link rel="stylesheet" type="text/css" href="assets/css/property-listing.css?ver='.time().'">
<link rel="stylesheet" type="text/css" href="assets/css/property-card.css?ver='.time().'">';
include_once("views/header.php");
?>
		<div class="row">
			<div class="col-12">
				<div class="card mb-4">
					<div class="card-header">
						<h5 class="mb-0">Welcome to the Reloc8UK Property Portal, <?php echo $_SESSION['user_name']; ?></h5>
					</div>
					<div class="card-body">
						<p class="mb-0">Welcome to the Reloc8UK property portal. This portal has been set up to allow councils, and tenants, to view the current properties available to let and, where applicable, reserve or place a hold on a property.</p>
						<p class="mb-0 mt-2">Reloc8UK aim to list all currently available properties, and upcoming properties, on the portal as quickly as possible.</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row property-listings-section">
			<div class="col-12">
				<div class="card property-listings-panel mb-4">
					<div class="card-body">
						<div class="property-listings-section-header">
							<span class="property-listings-section-header__icon">
								<span class="iconify" data-icon="mdi:home-city-outline"></span>
							</span>
							<div class="property-listings-section-header__text">
								<h6 class="mb-0">Latest Properties</h6>
								<p class="section-subtitle mb-0">Recently added homes available to view</p>
							</div>
							<div class="property-carousel-nav"></div>
						</div>
						<div class="property-carousel-wrap">
						<div class="multiple-items">
			<?php
			$property = getFeaturedProperty();
			
			if($property && !is_null($property["id"])){
				$propertyImages = explode(",",$property['images']);
			?>
				<div>
					<div class="property-card property-card--featured card w-100 h-100">
						<div class="property-card-image" style="background-image: url('assets/property_images/<?php if($property["images"]){ echo $propertyImages[0]; }else{ echo "property.png"; } ?>');">
							<div class="property-card-badges">
							<span class="property-card-badge property-card-badge--featured">Featured</span>
							<?php if($property['same_day_move'] == "1"){ ?>
							<span class="property-card-badge property-card-badge--sameday"><span class="iconify" data-icon="mdi:truck-fast-outline"></span> Same day</span>
							<?php } ?>
							</div>
							<span class="property-card-badge property-card-badge--rent"><?php if($property['rent_pcm'] == "0.00") { echo "TBA"; }else{ echo "&pound;" . $property['rent_pcm'] . "pcm"; } ?></span>
						</div>
						<div class="card-body d-flex flex-column">
							<div class="property-card-address">
								<span class="iconify property-card-address__icon" data-icon="mdi:map-marker-outline"></span>
								<div class="property-card-address__text">
									<div class="property-card-address-line"><?php echo $property['address1']; ?></div>
									<div class="property-card-address-postcode"><?php echo $property['postcode']; ?></div>
								</div>
							</div>
							<hr class="property-card-divider">
							<div class="property-card-stats">
								<span class="property-card-stat"><span class="iconify" data-icon="mdi:bed-king-outline"></span><?php echo $property['bedrooms']; ?> beds</span>
								<span class="property-card-stat"><span class="iconify" data-icon="mdi:bathtub-outline"></span><?php echo $property['bathrooms']; ?> baths</span>
							</div>
							<hr class="property-card-divider">
							<div class="property-card-meta"><span class="iconify" data-icon="mdi:clock-outline"></span>Added <?php echo time_elapsed_string($property['listed']); ?></div>
							<div class="property-actions d-flex">
								<a href="view-property.php?id=<?php echo $property['id']; ?>" class="btn btn-sm property-card-btn property-card-btn--view flex-fill"><span class="iconify" data-icon="mdi:eye-outline"></span>View</a>
								<?php if($_SESSION['user_type'] == "2") { ?>
								<a href="admin-edit-property.php?id=<?php echo $property['id']; ?>" class="btn btn-sm property-card-btn property-card-btn--edit flex-fill"><span class="iconify" data-icon="mdi:pencil-outline"></span>Edit</a>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			<?php 
			}
			$properties = getLatestProperties();
			foreach($properties as $property){
				$databasedate = strtotime( $property['listed'] );
				$mysqldate = date( 'd/m/Y', $databasedate );
				$images = explode(",",$property["images"]);
				?>
				<div>
					<div class="property-card card w-100 h-100">
						<div class="property-card-image" style="background-image: url('./system/thumbs.php?src=assets/property_images/<?php if($property["images"]){ echo $images[0]; }else{ echo "property.png"; } ?>&w=250&h=250'">
							<div class="property-card-badges">
							<?php if($property['same_day_move'] == "1"){ ?>
							<span class="property-card-badge property-card-badge--sameday"><span class="iconify" data-icon="mdi:truck-fast-outline"></span> Same day</span>
							<?php } ?>
							<?php if($property['status'] == 0){ ?>
								<span class="property-card-badge property-card-badge--status property-card-badge--status-available">Available</span>
							<?php }elseif($property['status'] == 1){ ?>
								<span class="property-card-badge property-card-badge--status property-card-badge--status-reserved">Reserved</span>
							<?php }elseif($property['status'] == 2){ ?>
								<span class="property-card-badge property-card-badge--status property-card-badge--status-held">Held</span>
							<?php }elseif($property['status'] == 3){ ?>
								<span class="property-card-badge property-card-badge--status property-card-badge--status-offer">Under Offer</span>
							<?php }elseif($property['status'] == 4){ ?>
								<span class="property-card-badge property-card-badge--status property-card-badge--status-let">Let</span>
							<?php } ?>
							</div>
							<span class="property-card-badge property-card-badge--rent">&pound;<?php echo $property['rent_pcm']; ?></span>
						</div>
						<div class="card-body d-flex flex-column">
							<div class="property-card-address">
								<span class="iconify property-card-address__icon" data-icon="mdi:map-marker-outline"></span>
								<div class="property-card-address__text">
									<div class="property-card-address-line"><?php echo $property['address1']; ?></div>
									<div class="property-card-address-postcode"><?php echo $property['postcode']; ?></div>
								</div>
							</div>
							<hr class="property-card-divider">
							<div class="property-card-stats">
								<span class="property-card-stat"><span class="iconify" data-icon="mdi:bed-king-outline"></span><?php echo $property['bedrooms']; ?> beds</span>
								<span class="property-card-stat"><span class="iconify" data-icon="mdi:bathtub-outline"></span><?php echo $property['bathrooms']; ?> baths</span>
							</div>
							<hr class="property-card-divider">
							<div class="property-card-meta"><span class="iconify" data-icon="mdi:clock-outline"></span>Added <?php echo time_elapsed_string($property['listed']); ?></div>
							<div class="property-actions d-flex">
								<a href="view-property.php?id=<?php echo $property['id']; ?>" class="btn btn-sm property-card-btn property-card-btn--view flex-fill"><span class="iconify" data-icon="mdi:eye-outline"></span>View</a>
								<?php if($_SESSION['user_type'] == "2") { ?>
								<a href="admin-edit-property.php?id=<?php echo $property['id']; ?>" class="btn btn-sm property-card-btn property-card-btn--edit flex-fill"><span class="iconify" data-icon="mdi:pencil-outline"></span>Edit</a>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
			?>
						</div>
						</div>
						<div class="property-listings-view-all-wrap">
							<a href="properties.php" class="btn btn-lg property-listings-view-all">View All Properties<span class="iconify" data-icon="mdi:arrow-right"></span></a>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
include_once("views/footer.php");
?>
<script type="text/javascript" src="assets/js/slick.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	var $propertyCarousel = $('.multiple-items');
	var $carouselWrap = $('.property-carousel-wrap');
	var $carouselNav = $('.property-carousel-nav');

	$propertyCarousel.slick({
	  dots: true,
	  appendDots: $carouselWrap,
	  appendArrows: $carouselNav,
	  infinite: false,
	  speed: 300,
	  slidesToShow: 4,
	  slidesToScroll: 4,
	  arrows: true,
	  prevArrow: '<button type="button" class="slick-prev property-carousel-arrow" aria-label="Previous properties"><span class="iconify" data-icon="mdi:chevron-left"></span></button>',
	  nextArrow: '<button type="button" class="slick-next property-carousel-arrow" aria-label="Next properties"><span class="iconify" data-icon="mdi:chevron-right"></span></button>',
	  responsive: [
		{
		  breakpoint: 1024,
		  settings: {
			slidesToShow: 3,
			slidesToScroll: 3,
			infinite: true,
			dots: true,
			arrows: true
		  }
		},
		{
		  breakpoint: 600,
		  settings: {
			slidesToShow: 2,
			slidesToScroll: 2,
			arrows: true
		  }
		},
		{
		  breakpoint: 480,
		  settings: {
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: true
		  }
		}
	  ]
	});

	$propertyCarousel.on('init reInit afterChange', function() {
		if (window.Iconify && typeof Iconify.scan === 'function') {
			Iconify.scan($carouselWrap[0]);
			Iconify.scan($carouselNav[0]);
		}
	});
});
</script>
</body>
</html>
