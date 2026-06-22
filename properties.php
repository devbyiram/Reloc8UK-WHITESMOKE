<?php
require('system/config.php');
/**
 * Check if the user is logged in.
 */
if(!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])){
    //User not logged in. Redirect them back to the login.php page.
    header('Location: login.php?redirect=properties.php');
    exit;
}
$page = "properties";
$page_title = "Properties - Reloc8UK Portal";
$portal_extra_head = '<link rel="stylesheet" type="text/css" href="assets/css/styles.css?ver='.time().'">
<link rel="stylesheet" type="text/css" href="assets/css/property-listing.css?ver='.time().'">
<link rel="stylesheet" type="text/css" href="assets/css/property-card.css?ver='.time().'">
<link rel="stylesheet" type="text/css" href="assets/css/property-card-rightmove.css?ver='.time().'">';
include_once("views/header.php");
?>
		<div class="card property-listings-panel property-filter-card mb-4">
			<div class="card-body">
				<div class="property-listings-section-header property-filter-card__header">
					<span class="property-listings-section-header__icon">
						<span class="iconify" data-icon="mdi:filter-variant"></span>
					</span>
					<div class="property-listings-section-header__text">
						<h6 class="mb-0">Filter properties</h6>
						<p class="section-subtitle mb-0">Refine your search</p>
					</div>
				</div>
				<form class="property-filter-card__form" method="get" action="properties.php">
					<div class="row property-filter-card__fields g-3">
						<div class="col-lg-4 col-md-12">
							<label class="property-filter-label">Number of beds</label>
							<div class="property-filter-pills" role="group" aria-label="Number of beds">
								<label class="property-filter-pill">
									<input class="property-filter-pill__input" type="checkbox" name="min_bedrooms[]" id="bedCheckbox1" value="1" <?php if(in_array("1", $_GET['min_bedrooms'] ?? [])){ echo "checked"; } ?>>
									<span class="property-filter-pill__label">1</span>
								</label>
								<label class="property-filter-pill">
									<input class="property-filter-pill__input" type="checkbox" name="min_bedrooms[]" id="bedCheckbox2" value="2" <?php if(in_array("2", $_GET['min_bedrooms'] ?? [])){ echo "checked"; } ?>>
									<span class="property-filter-pill__label">2</span>
								</label>
								<label class="property-filter-pill">
									<input class="property-filter-pill__input" type="checkbox" name="min_bedrooms[]" id="bedCheckbox3" value="3" <?php if(in_array("3", $_GET['min_bedrooms'] ?? [])){ echo "checked"; } ?>>
									<span class="property-filter-pill__label">3</span>
								</label>
								<label class="property-filter-pill">
									<input class="property-filter-pill__input" type="checkbox" name="min_bedrooms[]" id="bedCheckbox4" value="4" <?php if(in_array("4", $_GET['min_bedrooms'] ?? [])){ echo "checked"; } ?>>
									<span class="property-filter-pill__label">4</span>
								</label>
								<label class="property-filter-pill">
									<input class="property-filter-pill__input" type="checkbox" name="min_bedrooms[]" id="bedCheckbox5" value="5" <?php if(in_array("5", $_GET['min_bedrooms'] ?? [])){ echo "checked"; } ?>>
									<span class="property-filter-pill__label">5</span>
								</label>
							</div>
						</div>
						<div class="col-lg-2 col-md-6">
							<label class="property-filter-label" for="max_rent">Max rent</label>
							<div class="property-filter-input-wrap">
								<span class="property-filter-input-prefix">&pound;</span>
								<input type="text" class="form-control property-filter-control" name="max_rent" id="max_rent" placeholder="Max rent (PCM)" <?php if(!empty($_GET['max_rent'])){ echo 'value="'.$_GET['max_rent'].'"'; } ?>>
							</div>
						</div>
						<div class="col-lg-2 col-md-6">
							<label class="property-filter-label" for="furniture_included">Furniture included</label>
							<select name="furniture_included" class="form-select property-filter-control" id="furniture_included">
								<option value="" <?php if(!isset($_GET['furniture_included']) || $_GET['furniture_included'] === ''){ echo 'selected'; } ?>>Any</option>
								<option value="1" <?php if(isset($_GET['furniture_included']) && $_GET['furniture_included'] === '1'){ echo 'selected'; } ?>>Yes</option>
								<option value="0" <?php if(isset($_GET['furniture_included']) && $_GET['furniture_included'] === '0'){ echo 'selected'; } ?>>No</option>
							</select>
						</div>
						<div class="col-lg-2 col-md-6">
							<label class="property-filter-label" for="same_day_move">Same day move</label>
							<select name="same_day_move" class="form-select property-filter-control" id="same_day_move">
								<option value="" <?php if(!isset($_GET['same_day_move']) || $_GET['same_day_move'] === ''){ echo 'selected'; } ?>>Any</option>
								<option value="1" <?php if(isset($_GET['same_day_move']) && $_GET['same_day_move'] === '1'){ echo 'selected'; } ?>>Yes</option>
								<option value="0" <?php if(isset($_GET['same_day_move']) && $_GET['same_day_move'] === '0'){ echo 'selected'; } ?>>No</option>
							</select>
						</div>
						<div class="col-lg-2 col-md-6">
							<label class="property-filter-label" for="status">Status</label>
							<select name="status" class="form-select property-filter-control" id="status">
								<option value="" <?php if(empty($_GET['status'])){ echo 'selected'; } ?>>Any</option>
								<option value="available" <?php if(!empty($_GET['status']) && $_GET['status'] == 'available'){ echo 'selected'; } ?>>Available</option>
								<option value="reserved" <?php if(!empty($_GET['status']) && $_GET['status'] == 'reserved'){ echo 'selected'; } ?>>Reserved</option>
								<option value="held" <?php if(!empty($_GET['status']) && $_GET['status'] == 'held'){ echo 'selected'; } ?>>Held</option>
								<option value="underoffer" <?php if(!empty($_GET['status']) && $_GET['status'] == 'underoffer'){ echo 'selected'; } ?>>Under Offer</option>
								<option value="let" <?php if(!empty($_GET['status']) && $_GET['status'] == 'let'){ echo 'selected'; } ?>>Let</option>
							</select>
						</div>
					</div>
					<div class="property-filter-card__actions">
						<button class="btn property-filter-submit" type="submit"><span class="iconify" data-icon="mdi:magnify"></span>Apply filters</button>
						<a href="properties.php" class="property-filter-clear">Clear all</a>
					</div>
				</form>
			</div>
		</div>
		<div class="card property-listings-panel mb-4">
			<div class="card-body">
			<div class="row">
					<?php

					if(!empty($_GET['max_rent']) && $_GET['max_rent'] != ""){
						$max_rent = $_GET['max_rent'];
					}else{
						$max_rent = null;
					}
					if(!empty($_GET['min_bedrooms']) && $_GET['min_bedrooms'] != ""){
						$min_bedrooms = $_GET['min_bedrooms'];
					}else{
						$min_bedrooms = null;
					}
					if(!empty($_GET['furniture_included']) && $_GET['furniture_included'] != ""){
						$furniture_included = $_GET['furniture_included'];
					}else{
						$furniture_included = null;
					}
					if(!empty($_GET['same_day_move']) && $_GET['same_day_move'] != ""){
						$same_day_move = $_GET['same_day_move'];
					}else{
						$same_day_move = null;
					}
					if(!empty($_GET['status']) && $_GET['status'] != ""){
						$status = $_GET['status'];
					}else{
						$status = null;
					}
					if(!empty($_GET['page']) && $_GET['page'] != ""){
					    $page = $_GET['page'];
					}else{
					    $page = 1;
					}
					$properties = getAllProperties($status,$max_rent,$min_bedrooms,$furniture_included,$same_day_move,$page);
					
					$total_count = $properties["totalCount"];
					
					$total_pages = ceil($total_count / 20);
					
					$i = 0;
					if(count($properties["properties"]) == 0){
					?>
					<div class="col-md-12 justify-content-center">
						<i>Sorry, we have no properties matching that search. Please try again.</i>
					</div>
					<?php
					}else{
					?>
					
					<div class="col-12 property-results-section">
						<div class="property-listings-section-header property-listings-section-header--compact">
							<span class="property-listings-section-header__icon">
								<span class="iconify" data-icon="mdi:home-search-outline"></span>
							</span>
							<div class="property-listings-section-header__text">
								<h6 class="mb-0">Property Results</h6>
								<p class="section-subtitle mb-0"><?php echo $total_count; ?> properties found.</p>
							</div>
						</div>
						<nav class="property-view-switcher nav nav-tabs border-0" id="nav-tab" role="tablist">
							<a class="nav-link property-view-switcher__btn active" id="nav-list-tab" data-bs-toggle="tab" href="#nav-list" role="tab" aria-controls="nav-list" aria-selected="true"><span class="iconify" data-icon="mdi:view-grid-outline"></span>List view</a>
							<a class="nav-link property-view-switcher__btn" id="nav-map-tab" data-bs-toggle="tab" href="#nav-map" role="tab" aria-controls="nav-map" aria-selected="false"><span class="iconify" data-icon="mdi:map-outline"></span>Map view</a>
						</nav>
						<div class="tab-content property-results-tab-content" id="nav-tabContent">
							<div class="tab-pane fade show active row property-listings-grid property-listings-grid-page" id="nav-list" role="tabpanel" aria-labelledby="nav-list-tab" style="display: flex !important;">
                    <?php
					foreach($properties["properties"] as $property){
						$databasedate = strtotime( $property['listed'] );
						$mysqldate = date( 'd/m/Y', $databasedate );
						$images = explode(",",$property["images"]);
						?>
					<div class="col-12 property-listings-grid__item d-flex">
						<article class="property-card property-card--rightmove card w-100 h-100">
							<div class="property-card__media">
								<div class="property-card-carousel">
									<div class="property-card-carousel__viewport">
										<div class="property-card-carousel__track">
										<?php if($property["images"]){ ?>
											<?php foreach($images as $imageFile){ ?>
											<div class="property-card-carousel__slide">
												<div class="property-card-image" style="background-image: url('./system/thumbs.php?src=assets/property_images/<?php echo $imageFile; ?>&w=400&h=260');"></div>
											</div>
											<?php } ?>
										<?php }else{ ?>
											<div class="property-card-carousel__slide">
												<div class="property-card-image" style="background-image: url('./system/thumbs.php?src=assets/property_images/property.png&w=400&h=260');"></div>
											</div>
										<?php } ?>
										</div>
									</div>
									<button type="button" class="property-card-carousel__nav property-card-carousel__nav--prev" aria-label="Previous image"><span class="iconify" data-icon="mdi:chevron-left"></span></button>
									<button type="button" class="property-card-carousel__nav property-card-carousel__nav--next" aria-label="Next image"><span class="iconify" data-icon="mdi:chevron-right"></span></button>
									<div class="property-card-carousel__badge">
										<span class="iconify property-card-carousel__badge-icon" data-icon="mdi:floor-plan"></span>
										<span class="property-card-carousel__badge-sep" aria-hidden="true"></span>
										<span class="iconify property-card-carousel__badge-icon" data-icon="mdi:camera-outline"></span>
										<span class="property-card-carousel__count">1/<?php if($property["images"]){ echo count($images); }else{ echo "1"; } ?></span>
									</div>
								</div>
							</div>
							<div class="card-body property-card__content d-flex flex-column">
								<div class="property-card__summary">
									<div class="property-card-price-row">
										<div class="property-card-price"><?php if($property['rent_pcm'] == "0.00") { echo "TBA"; }else{ echo "&pound;" . $property['rent_pcm'] . "pcm"; } ?></div>
										<div class="property-card-tags">
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
									</div>
									<div class="property-card-address property-card-address--rightmove">
										<div class="property-card-address-line"><?php echo $property['address1']; ?></div>
										<div class="property-card-address-postcode"><?php echo $property['postcode']; ?></div>
									</div>
									<div class="property-card-keyinfo">
										<span class="property-card-stat"><span class="iconify" data-icon="mdi:bed-king-outline"></span><?php echo $property['bedrooms']; ?></span>
										<span class="property-card-keyinfo__sep" aria-hidden="true"></span>
										<span class="property-card-stat"><span class="iconify" data-icon="mdi:bathtub-outline"></span><?php echo $property['bathrooms']; ?></span>
									</div>
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
							<div class="tab-pane fade" id="nav-map" role="tabpanel" aria-labelledby="nav-map-tab"></div>
						</div>
					</div>
					<?php
					}
					?>
			</div>
			<?php
			if(!empty($_GET['page']) && $_GET['page'] != ""){
					    $page = $_GET['page'];
					}else{
					    $page = 1;
					}
			?>
			<nav class="property-listings-pagination" aria-label="Property list pagination">
			<ul class="pagination justify-content-center mb-0">
                <li class="page-item<?php if($page <= 1){ echo ' disabled'; } ?>">
                    <a href="<?php if($page <= 1){ echo '#'; } else { echo "?page=".($page - 1); } ?>" class="page-link"<?php if($page <= 1){ echo ' tabindex="-1" aria-disabled="true"'; } ?>><span class="iconify" data-icon="mdi:chevron-left"></span> Previous</a>
                </li>
                <li class="page-item<?php if($page >= $total_pages){ echo ' disabled'; } ?>">
                    <a href="<?php if($page >= $total_pages){ echo '#'; } else { echo "?page=".($page + 1); } ?>" class="page-link"<?php if($page >= $total_pages){ echo ' tabindex="-1" aria-disabled="true"'; } ?>>Next <span class="iconify" data-icon="mdi:chevron-right"></span></a>
                </li>
            </ul>
			</nav>
			</div>
		</div>
<?php
$portal_extra_scripts = '<script type="text/javascript" src="assets/js/property-card-carousel.js?ver='.time().'"></script>';
include_once("views/footer.php");
?>
</body>
</html>
