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
$portal_extra_head = '<link rel="stylesheet" type="text/css" href="assets/css/styles.css?ver='.time().'">
<link rel="stylesheet" type="text/css" href="assets/css/property-listing.css?ver='.time().'">
<link rel="stylesheet" type="text/css" href="assets/css/property-card.css?ver='.time().'">
<link rel="stylesheet" type="text/css" href="assets/css/property-card-rightmove.css?ver='.time().'">'.(($_SESSION['user_type'] == "2") ? '
<link rel="stylesheet" type="text/css" href="assets/css/admin-dashboard.css?ver='.time().'">' : '');
include_once("views/header.php");
?>
		<?php if($_SESSION['user_type'] == "2") { ?>
		<div class="dashboard-welcome dashboard-welcome--portal mb-4">
			<div class="dashboard-welcome__inner">
				<div class="dashboard-welcome__icon" aria-hidden="true">
					<span class="iconify" data-icon="mdi:home-city-outline"></span>
				</div>
				<div class="dashboard-welcome__content">
					<h4 class="dashboard-welcome__title">Welcome to the Reloc8UK Property Portal, <span class="dashboard-welcome__name"><?php echo $_SESSION['user_name']; ?></span></h4>
					<p class="dashboard-welcome__text">Welcome to the Reloc8UK property portal. This portal has been set up to allow councils, and tenants, to view the current properties available to let and, where applicable, reserve or place a hold on a property.</p>
					<p class="dashboard-welcome__text">Reloc8UK aim to list all currently available properties, and upcoming properties, on the portal as quickly as possible.</p>
				</div>
			</div>
		</div>
		<?php } else { ?>
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
		<?php } ?>
		<?php if($_SESSION['user_type'] == "2") {
			$adminDashboard = getAdminDashboardSummary();
			include_once('views/partials/admin-dashboard.php');
		} ?>
		<div class="row property-listings-section">
			<div class="col-12">
				<div class="card property-listings-panel mb-4">
					<div class="card-body">
						<div class="property-listings-section-header property-listings-section-header--compact">
							<span class="property-listings-section-header__icon">
								<span class="iconify" data-icon="mdi:home-city-outline"></span>
							</span>
							<div class="property-listings-section-header__text">
								<h6 class="mb-0">Latest Properties</h6>
								<p class="section-subtitle mb-0">Recently added homes available to view</p>
							</div>
						</div>
						<div class="row property-listings-grid property-listings-grid-page">
			<?php
			$property = getFeaturedProperty();
			
			if($property && !is_null($property["id"])){
				$propertyImages = explode(",",$property['images']);
			?>
							<div class="col-12 property-listings-grid__item d-flex">
								<article class="property-card property-card--rightmove card w-100 h-100">
									<div class="property-card__media">
										<div class="property-card-carousel">
											<div class="property-card-carousel__sources" aria-hidden="true" hidden>
												<?php if($property["images"]){ ?>
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
												<span class="property-card-carousel__count">1/<?php if($property["images"]){ echo count($propertyImages); }else{ echo "1"; } ?></span>
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
											<div class="property-card-price"><?php if($property['rent_pcm'] == "0.00") { echo "TBA"; }else{ echo "&pound;" . $property['rent_pcm'] . "pcm"; } ?></div>
										</div>
									</div>
									<div class="card-body property-card__content d-flex flex-column">
										<div class="property-card__summary">
											<div class="property-card-tags property-card-tags--top">
												<span class="property-card-tag property-card-tag--status property-card-tag--status-available">Featured</span>
												<?php if($property['same_day_move'] == "1"){ ?>
												<span class="property-card-tag property-card-tag--sameday"><span class="iconify" data-icon="mdi:truck-fast-outline"></span> Same day</span>
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
												<?php if($_SESSION['user_type'] == "2") { ?>
												<a href="admin-edit-property.php?id=<?php echo $property['id']; ?>" class="btn btn-sm property-card-btn property-card-btn--edit"><span class="iconify" data-icon="mdi:pencil-outline"></span>Edit</a>
												<?php } ?>
											</div>
										</div>
									</div>
								</article>
							</div>
			<?php 
			}
			$latestCount = ($property && !is_null($property["id"])) ? 3 : 4;
			$featuredId = ($property && !is_null($property["id"])) ? $property['id'] : null;
			$properties = getLatestProperties($featuredId ? 8 : 4);
			$shown = 0;
			foreach($properties as $property){
				if($featuredId && $property['id'] == $featuredId){
					continue;
				}
				if($shown >= $latestCount){
					break;
				}
				$shown++;
				$databasedate = strtotime( $property['listed'] );
				$mysqldate = date( 'd/m/Y', $databasedate );
				$images = explode(",",$property["images"]);
				?>
							<div class="col-12 property-listings-grid__item d-flex">
								<article class="property-card property-card--rightmove card w-100 h-100">
									<div class="property-card__media">
										<div class="property-card-carousel">
											<div class="property-card-carousel__sources" aria-hidden="true" hidden>
												<?php if($property["images"]){ ?>
													<?php foreach($images as $imageFile){ ?>
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
												<span class="property-card-carousel__count">1/<?php if($property["images"]){ echo count($images); }else{ echo "1"; } ?></span>
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
											<div class="property-card-price">&pound;<?php echo $property['rent_pcm']; ?></div>
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
												<?php if($_SESSION['user_type'] == "2") { ?>
												<a href="admin-edit-property.php?id=<?php echo $property['id']; ?>" class="btn btn-sm property-card-btn property-card-btn--edit"><span class="iconify" data-icon="mdi:pencil-outline"></span>Edit</a>
												<?php } ?>
											</div>
										</div>
									</div>
								</article>
							</div>
				<?php
			}
			?>
						</div>
						<div class="property-listings-view-all-wrap">
							<a href="properties.php" class="btn btn-lg property-listings-view-all">View All Properties<span class="iconify" data-icon="mdi:arrow-right"></span></a>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
$portal_extra_scripts = '<script type="text/javascript" src="assets/js/property-card-carousel.js?ver='.time().'"></script>';
include_once("views/footer.php");
?>
