<?php
require('system/config.php');
 
/**
 * Check if the user is logged in.
 */
if(!empty($_GET['share']) && $_GET['share'] == "true"){
	
}else{
	if(!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])){
		//User not logged in. Redirect them back to the login.php page.
		header('Location: login.php?redirect=view-property.php');
		exit;
	}
}
$property = getProperty($_GET['id']);
if(isset($property['images'])){
	$property_images = explode(',',$property['images']);
}else{
	$property_images[0] = "property.png";
}
$page = "properties";
$page_title = 'View Property #' . $property['id'] . ' - ' . $property['address1'];
if(!empty($_GET['share']) && $_GET['share'] == "true"){
	$portal_hide_navbar = true;
}
$portal_extra_head = '<link rel="stylesheet" type="text/css" href="assets/css/property-detail.css?ver='.time().'" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.1/css/lightbox.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.1/js/lightbox.min.js"></script>';
include_once("views/header.php");
?>
	<div class="property-detail-page property-detail-page--rightmove">
		<?php
			switch ($property['status']) {
				case 1:
					echo '<div class="alert alert-danger property-detail-status-alert" role="alert"><span class="iconify property-detail-status-alert__icon" data-icon="mdi:lock-outline"></span> This property is reserved</div>';
					break;
				case 2:
					echo '<div class="alert alert-warning property-detail-status-alert" role="alert"><span class="iconify property-detail-status-alert__icon" data-icon="mdi:hand-back-right-outline"></span> This property currently has interest in holding</div>';
					break;
				case 3:
					echo '<div class="alert alert-primary property-detail-status-alert" role="alert"><span class="iconify property-detail-status-alert__icon" data-icon="mdi:file-document-outline"></span> This property is under offer</div>';
					break;
				case 4:
					echo '<div class="alert alert-danger property-detail-status-alert" role="alert"><span class="iconify property-detail-status-alert__icon" data-icon="mdi:home-remove-outline"></span> This property is let</div>';
					break;
				case 5:
				    echo '<div class="alert alert-info property-detail-status-alert" role="alert"><span class="iconify property-detail-status-alert__icon" data-icon="mdi:clock-outline"></span> This property will be available soon</div>';
				    break;
				case 6:
						echo '<div class="alert alert-danger property-detail-status-alert" role="alert"><span class="iconify property-detail-status-alert__icon" data-icon="mdi:office-building-outline"></span> This property has now been assigned to a council</div>';
						break;
			}
		?>
		<?php
			if($_SESSION['user_type'] == 2){
		?>
		<div class="property-detail-admin-bar mb-4">
			<div class="property-detail-admin-bar__icon">
				<span class="iconify" data-icon="mdi:share-variant-outline"></span>
			</div>
			<div class="property-detail-admin-bar__content">
				<div class="property-detail-admin-bar__title">Share property preview</div>
				<p class="property-detail-admin-bar__text">Share a preview of this property to non-members.</p>
			</div>
			<div class="property-detail-admin-bar__actions">
				<a href="#" data-clipboard-text="https://www.reloc8uk.co.uk/portal/view-property.php?id=<?php echo $property['id']; ?>&share=true" class="copyToClipboard property-detail-admin-bar__copy"><span class="iconify" data-icon="mdi:content-copy"></span> Copy preview link</a>
				<a href="admin-edit-property.php?id=<?php echo $property['id']; ?>" class="property-detail-admin-bar__edit"><span class="iconify" data-icon="mdi:pencil-outline"></span> Edit Property</a>
			</div>
		</div>
		<?php
			}
		?>
		<?php
			if(!empty($_GET['share']) && $_GET['share'] == "true"){
		?>
		<div class="property-detail-share-preview">
			You are visiting a preview of this property on the Reloc8UK portal.
		</div>
		<?php
			}
		?>
		<div class="property-detail-gallery<?php if(count($property_images) <= 1){ echo ' property-detail-gallery--single'; } ?>">
			<div class="property-detail-gallery__grid">
				<div class="property-detail-gallery__main">
					<div class="property-detail-gallery__viewport" id="property-gallery-main">
						<?php
						$gallery_index = 0;
						foreach($property_images as $image){
							echo '<div class="property-detail-gallery__slide'.($gallery_index === 0 ? ' is-active' : '').'">';
							echo '<a data-lightbox="gallery" href="assets/property_images/'.$image.'"><img src="assets/property_images/'.$image.'" alt=""></a>';
							echo '</div>';
							$gallery_index++;
						}
						?>
					</div>
					<button type="button" class="property-detail-gallery__arrow property-detail-gallery__arrow--prev" aria-label="Previous photo">
						<span class="iconify" data-icon="mdi:chevron-left"></span>
					</button>
					<button type="button" class="property-detail-gallery__arrow property-detail-gallery__arrow--next" aria-label="Next photo">
						<span class="iconify" data-icon="mdi:chevron-right"></span>
					</button>
					<div class="property-detail-gallery__count">
						<span class="iconify" data-icon="mdi:floor-plan"></span>
						<span class="property-detail-gallery__count-divider" aria-hidden="true"></span>
						<span class="iconify" data-icon="mdi:camera-outline"></span>
						<span><span id="property-gallery-current">1</span><span class="property-detail-gallery__count-sep">/</span><span id="property-gallery-total"><?php echo count($property_images); ?></span></span>
					</div>
				</div>
				<div class="property-detail-gallery__aside">
					<button type="button" class="property-detail-gallery__thumb-arrow property-detail-gallery__thumb-arrow--up" aria-label="Scroll thumbnails up">
						<span class="iconify" data-icon="mdi:chevron-up"></span>
					</button>
					<div class="property-detail-gallery__thumbs" id="property-gallery-thumbs">
						<?php
						$thumb_index = 0;
						foreach($property_images as $image){
							echo '<button type="button" class="property-detail-gallery__thumb'.($thumb_index === 0 ? ' is-active' : '').'">';
							echo '<img src="assets/property_images/'.$image.'" alt="">';
							echo '</button>';
							$thumb_index++;
						}
						?>
					</div>
					<button type="button" class="property-detail-gallery__thumb-arrow property-detail-gallery__thumb-arrow--down" aria-label="Scroll thumbnails down">
						<span class="iconify" data-icon="mdi:chevron-down"></span>
					</button>
				</div>
			</div>
		</div>

		<div class="property-detail-shell">
			<div class="row g-4 property-detail-layout">
				<div class="col-lg-8 property-detail-layout__main">
			<div class="property-detail-summary">
				<div class="property-detail-hero property-detail-hero--rightmove">
					<div class="property-detail-hero__price-block">
						<div class="property-detail-hero__price"><?php if($property['rent_pcm'] == "0.00") { echo "TBA"; }else{ echo "&pound;" . $property['rent_pcm']; } ?></div>
						<div class="property-detail-hero__price-label">Rent PCM</div>
					</div>
					<div class="property-detail-hero__main">
						<h1 class="property-detail-hero__title"><?php echo $property['address1']; ?></h1>
						<p class="property-detail-hero__postcode"><?php echo $property['postcode']; ?></p>
						<p class="property-detail-hero__meta">
							<span class="property-detail-hero__meta-item"><span class="iconify" data-icon="mdi:calendar-outline"></span>Date available: <strong><?php if($property['available_soon'] == null) { echo date("d/m/Y",strtotime($property['date_available']));  } else { echo 'Available Soon'; }  ?></strong></span>
							<?php if($property['same_day_move'] == 1) { ?>
							<span class="property-detail-hero__meta-item property-detail-hero__meta-item--sameday"><span class="iconify" data-icon="mdi:truck-fast-outline"></span>Same day move available</span>
							<?php } ?>
						</p>
					</div>
				</div>
				<div class="property-detail-keyfacts property-detail-keyfacts--rightmove" role="list">
					<div class="property-detail-keyfact" role="listitem">
						<span class="property-detail-keyfact__label">Property type</span>
						<span class="property-detail-keyfact__value">
							<span class="iconify property-detail-keyfact__icon" data-icon="mdi:home-outline" aria-hidden="true"></span>
							<span class="property-detail-keyfact__value-text"><?php echo $propertyTypes[$property['property_type']]; ?></span>
						</span>
					</div>
					<div class="property-detail-keyfact" role="listitem">
						<span class="property-detail-keyfact__label">Bedrooms</span>
						<span class="property-detail-keyfact__value">
							<span class="iconify property-detail-keyfact__icon" data-icon="mdi:bed-king-outline" aria-hidden="true"></span>
							<span class="property-detail-keyfact__value-text"><?php echo $property['bedrooms']; ?></span>
						</span>
					</div>
					<div class="property-detail-keyfact" role="listitem">
						<span class="property-detail-keyfact__label">Bathrooms</span>
						<span class="property-detail-keyfact__value">
							<span class="iconify property-detail-keyfact__icon" data-icon="mdi:bathtub-outline" aria-hidden="true"></span>
							<span class="property-detail-keyfact__value-text"><?php echo $property['bathrooms']; ?></span>
						</span>
					</div>
					<div class="property-detail-keyfact" role="listitem">
						<span class="property-detail-keyfact__label">Furniture</span>
						<span class="property-detail-keyfact__value">
							<span class="iconify property-detail-keyfact__icon" data-icon="mdi:sofa-outline" aria-hidden="true"></span>
							<span class="property-detail-keyfact__value-text"><?php if($property['furniture_included'] == "1"){ echo '<span class="iconify" data-icon="mdi:check"></span>'; }else{ echo '<span class="iconify" data-icon="mdi:close"></span>'; } ?></span>
						</span>
					</div>
					<div class="property-detail-keyfact" role="listitem">
						<span class="property-detail-keyfact__label">Available</span>
						<span class="property-detail-keyfact__value">
							<span class="iconify property-detail-keyfact__icon" data-icon="mdi:calendar-outline" aria-hidden="true"></span>
							<span class="property-detail-keyfact__value-text"><?php if($property['available_soon'] == null) { echo date("d/m/Y",strtotime($property['date_available']));  } else { echo 'Available Soon'; }  ?></span>
						</span>
					</div>
				</div>
			</div>

			<div class="property-detail-content property-detail-content--rightmove">
						<section class="property-detail-section">
							<h2 class="property-detail-section__title">Key features</h2>
							<ul class="property-detail-features">
								<li><?php echo $property['bedrooms']; ?> bedroom <?php echo strtolower($propertyTypes[$property['property_type']]); ?></li>
								<li><?php echo $property['bathrooms']; ?> bathroom<?php if($property['bathrooms'] != 1){ echo 's'; } ?></li>
								<li><?php if($property['furniture_included'] == "1"){ echo 'Furniture included'; }else{ echo 'Furniture not included'; } ?></li>
								<li><?php if($property['garden_front'] == "1"){ echo 'Front garden'; }else{ echo 'No front garden'; } ?></li>
								<li><?php if($property['garden_rear'] == "1"){ echo 'Rear garden'; }else{ echo 'No rear garden'; } ?></li>
								<li><?php if($property['central_heating'] == "1"){ echo 'Central heating'; }else{ echo 'No central heating'; } ?></li>
							</ul>
						</section>

						<section class="property-detail-section property-detail-info">
							<h2 class="property-detail-section__title property-detail-info__title">Description</h2>
							<div class="property-detail-info__body-wrap" id="property-description-wrap">
								<div class="property-detail-info__body property-detail-info__body--clamped"><?php echo nl2br($property['notes']); ?></div>
							</div>
							<button type="button" class="property-detail-info__toggle" id="property-description-toggle" aria-expanded="false">Read full description</button>
						</section>
			<?php 
				if($property['same_day_move'] == 1) {
				?>
				<div class="property-detail-sameday-banner">
					<h4 class="alert-heading">Same Day Move Available!</h4>
					<p>This property is available for our same day move, meaning you could be in this property today!</p>
					<hr>
					<p class="mb-0">Reserve this property today!</p>
				</div>
				<?php
				}
				?>

						<section class="property-detail-section property-detail-map">
							<h2 class="property-detail-section__title">Location</h2>
							<div class="property-detail-map__embed">
								<iframe title="Property location map" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=<?php echo urlencode($property['postcode']); ?>&amp;output=embed"></iframe>
							</div>
							<div class="property-detail-map__links">
								<a href="https://www.google.com/maps/place/<?php echo str_replace(' ','',$property['postcode']); ?>" class="property-detail-map__link" target="_blank" rel="noopener">
									<span class="iconify" data-icon="mdi:google-maps"></span> Open in Google Maps
								</a>
								<a href="https://www.checkmypostcode.uk/<?php echo str_replace(' ','',$property['postcode']); ?>" class="property-detail-map__link" target="_blank" rel="noopener">
									<span class="iconify" data-icon="mdi:map-search-outline"></span> CheckMyPostcode.uk
								</a>
							</div>
						</section>

						<section class="property-detail-section property-detail-bottom-section">
							<div class="row g-4">
								<div class="col-md-6 col-xs-12">
									<div class="property-detail-section-title">Property Video <small class="text-muted">(if applicable)</small></div>
									<div class="property-detail-media-card">
										<div class="property-detail-media-card__body">
										<?php echo getPropertyVideo($property['id']); ?>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-xs-12">
									<div class="property-detail-section-title">Property Files</div>
									<div class="property-detail-media-card">
										<div class="property-detail-media-card__body property-detail-files-grid">
									<?php
										echo getPropertyFiles($property['id']);
									?>
										</div>
									</div>
								</div>
							</div>
						</section>
			</div>
				</div>
				<div class="col-lg-4 property-detail-layout__aside">
					<div class="property-detail-sidebar property-detail-sidebar--sticky property-detail-sidebar--rightmove">
						<div class="property-detail-sidebar__card">
							<div class="property-detail-sidebar__price-label">Rent per month</div>
							<div class="property-detail-sidebar__price"><?php if($property['rent_pcm'] == "0.00") { echo "TBA"; }else{ echo "&pound;" . $property['rent_pcm']; } ?></div>
							<div class="property-detail-sidebar__available">
								<span class="property-detail-sidebar__available-label"><span class="iconify" data-icon="mdi:calendar-check"></span> Date available</span>
								<span class="property-detail-sidebar__available-date"><?php if($property['available_soon'] == null) { echo date("d/m/Y",strtotime($property['date_available']));  } else { echo 'Available Soon'; }  ?></span>
							</div>
							<hr class="property-detail-sidebar__divider">
							<ul class="property-detail-sidebar__facts">
								<li><span>Property type</span><span><?php echo $propertyTypes[$property['property_type']]; ?></span></li>
							</ul>
						</div>
					<?php if(empty($_GET['share']) && $_GET['share'] != "true") { ?>
					<?php
					if($_SESSION['user_type'] != 3){
					?>
						<div class="property-detail-actions property-detail-actions--rightmove">
							<p class="property-detail-actions__title">Property Actions</p>
							<div class="property-detail-actions__stack">
					<?php
						if($property['status'] == 0 || $property['status'] == 2){
					?>
					    <?php
					        if($_SESSION['user_type'] != 4){
					    ?>
								<a href="reserve-property.php?id=<?php echo $property['id']; ?>" class="property-detail-action-btn property-detail-action-btn--reserve" title="Reserve this property for 24 hours" data-bs-toggle="tooltip">Reserve</a>
								<a href="hold-property.php?id=<?php echo $property['id']; ?>" class="property-detail-action-btn property-detail-action-btn--hold" title="Hold this property (holding fee required)" data-bs-toggle="tooltip">Hold</a>
						<?php
					        }
					    ?>
								<a href="offer-property.php?id=<?php echo $property['id']; ?>" class="property-detail-action-btn property-detail-action-btn--offer" title="Offer this property to a tenant" data-bs-toggle="tooltip">Offer</a>
					<?php 
						}
					?>
						<!--<div class="col-6 col-sm-3 mb-1 my-2 my-sm-2">
							<a href="show-interest.php?pid=<?php echo $property['id']; ?>" class="btn btn-lg btn-block btn-dark showInterest" title="Show an interest in this property" data-toggle="tooltip" onclick="return confirm('Are your sure you want to show an interest in this property?')">Interested</a>
						</div>-->
							</div>
						</div>
					<?php
					}
					}
					?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
include_once("views/footer.php");
?>
<script type="text/javascript">
$(document).ready(function(){
	$(function () {
	  $('[data-bs-toggle="tooltip"]').tooltip()
	})

	var $slides = $('.property-detail-gallery__slide');
	var $thumbs = $('.property-detail-gallery__thumb');
	var total = $slides.length;
	var current = 0;
	var $thumbScroll = $('#property-gallery-thumbs');

	function scrollThumbIntoView(index) {
		if (!$thumbScroll.length || !$thumbs.length) {
			return;
		}
		var thumb = $thumbs.eq(index)[0];
		if (thumb) {
			thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
		}
	}

	function goTo(index) {
		if (total < 1) {
			return;
		}
		current = (index + total) % total;
		$slides.removeClass('is-active').eq(current).addClass('is-active');
		$thumbs.removeClass('is-active').eq(current).addClass('is-active');
		$('#property-gallery-current').text(current + 1);
		scrollThumbIntoView(current);
	}

	if (total > 0) {
		goTo(0);
	}

	if (total <= 1) {
		$('.property-detail-gallery__arrow, .property-detail-gallery__aside').hide();
	}

	$('.property-detail-gallery__arrow--prev').on('click', function(e) {
		e.preventDefault();
		goTo(current - 1);
	});

	$('.property-detail-gallery__arrow--next').on('click', function(e) {
		e.preventDefault();
		goTo(current + 1);
	});

	$thumbs.on('click', function(e) {
		e.preventDefault();
		goTo($thumbs.index(this));
	});

	$('.property-detail-gallery__thumb-arrow--up').on('click', function(e) {
		e.preventDefault();
		goTo(current - 1);
	});

	$('.property-detail-gallery__thumb-arrow--down').on('click', function(e) {
		e.preventDefault();
		goTo(current + 1);
	});

	$(document).on('keydown', function(e) {
		if (!$('.property-detail-gallery').length) {
			return;
		}
		if (e.key === 'ArrowLeft') {
			goTo(current - 1);
		}
		if (e.key === 'ArrowRight') {
			goTo(current + 1);
		}
	});

	var $descBody = $('.property-detail-info__body');
	var $descToggle = $('#property-description-toggle');
	if ($descBody.length && $descToggle.length) {
		var fullHeight = $descBody[0].scrollHeight;
		var clampedMax = 168;
		if (fullHeight <= clampedMax + 8) {
			$descToggle.hide();
		} else {
			$descToggle.on('click', function() {
				var expanded = $descBody.toggleClass('property-detail-info__body--expanded').hasClass('property-detail-info__body--expanded');
				$descToggle.attr('aria-expanded', expanded ? 'true' : 'false');
				$descToggle.text(expanded ? 'Read less' : 'Read full description');
			});
		}
	}
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
