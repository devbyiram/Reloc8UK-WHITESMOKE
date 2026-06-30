<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo isset($page_title) ? $page_title : 'Reloc8UK Portal'; ?></title>
	<link rel="icon" type="image/x-icon" href="assets/favicon.ico">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link
		href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap"
		rel="stylesheet">
	<link rel="stylesheet" href="assets/vendor/sneat/vendor/fonts/iconify-icons.css" />
	<link rel="stylesheet" href="assets/vendor/sneat/vendor/css/core.css" />
	<link rel="stylesheet" href="assets/vendor/sneat/css/demo.css" />
	<link rel="stylesheet" href="assets/vendor/perfect-scrollbar/perfect-scrollbar.css" />
	<link rel="stylesheet" href="assets/css/admin/reloc8uk-theme.css" />

	<script src="assets/vendor/sneat/vendor/js/helpers.js"></script>
	<script src="assets/vendor/sneat/js/config.js"></script>

	<?php if(!empty($portal_extra_head)) { echo $portal_extra_head; } ?>
</head>

<body>
	<div class="layout-wrapper layout-content-navbar layout-without-menu">
		<div class="layout-container">

			<div class="layout-page">
				<?php
				if(empty($portal_hide_navbar)) {
				include_once("views/navbar.php");
				}
				?>

				<div class="content-wrapper">
					<div class="container-xxl flex-grow-1 container-p-y">
