<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
	id="layout-navbar">
	<div class="layout-menu-toggle navbar-nav align-items-xl-center me-xl-0 d-xl-none me-3">
		<a class="nav-item nav-link me-xl-4 px-0" href="javascript:void(0)">
			<i class="iconify iconify-lg" data-icon="bx:bx-menu"></i>
		</a>
	</div>

	<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
		<ul class="navbar-nav align-items-center ms-auto flex-row">
			<?php if(isset($_SESSION['user_id'])) { ?>
			<li class="nav-item">
				<a class="nav-link" href="logout.php">
					<i class="iconify iconify-lg me-1" data-icon="bx:bx-log-out"></i>
					<span class="align-middle d-none d-sm-inline">Logout</span>
				</a>
			</li>
			<?php } ?>
		</ul>
	</div>
</nav>
