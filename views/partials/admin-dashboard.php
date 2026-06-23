<?php
$cards = $adminDashboard['cards'] ?? array();
?>
<?php if(!empty($cards)) { ?>
<div class="row g-3 mb-4 portal-admin-dashboard">
	<?php foreach($cards as $card) {
		$tone = $card['tone'] ?? 'slate';
		$wrapperTag = !empty($card['route']) ? 'a' : 'div';
	?>
	<div class="col-sm-6 col-lg-3">
		<<?php echo $wrapperTag; ?><?php if($wrapperTag === 'a') { ?> href="<?php echo $card['route']; ?>"<?php } ?> class="dashboard-summary-card dashboard-summary-card--<?php echo $tone; ?> h-100 text-decoration-none">
			<div class="dashboard-summary-card__body">
				<div class="dashboard-summary-card__content">
					<span class="dashboard-summary-card__label"><?php echo $card['label'] ?? ''; ?></span>
					<span class="dashboard-summary-card__value"><?php echo $card['value'] ?? '—'; ?></span>
					<?php if(!empty($card['subtitle'])) { ?>
					<span class="dashboard-summary-card__subtitle"><?php echo $card['subtitle']; ?></span>
					<?php } ?>
				</div>
				<?php if(!empty($card['icon'])) { ?>
				<div class="dashboard-summary-card__icon">
					<i class="iconify" data-icon="<?php echo $card['icon']; ?>"></i>
				</div>
				<?php } ?>
			</div>
		</<?php echo $wrapperTag; ?>>
	</div>
	<?php } ?>
</div>
<?php } ?>
