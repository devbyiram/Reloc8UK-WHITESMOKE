<nav class="layout-navbar portal-top-header navbar navbar-expand-xl align-items-center" id="layout-navbar">
	<div class="container-xxl portal-top-header__container">
		<a href="index.php" class="app-brand-link portal-top-header__brand">
			<span class="app-brand-wordmark" aria-label="Reloc8UK">
				<span class="app-brand-wordmark__reloc">Reloc8</span><span class="app-brand-wordmark__uk">UK</span>
			</span>
		</a>

		<button class="navbar-toggler portal-top-header__toggler" type="button" data-bs-toggle="collapse" data-bs-target="#portal-top-nav" aria-controls="portal-top-nav" aria-expanded="false" aria-label="Toggle navigation">
			<i class="iconify iconify-lg" data-icon="bx:bx-menu"></i>
		</button>

		<div class="collapse navbar-collapse portal-top-header__collapse" id="portal-top-nav">
			<ul class="navbar-nav portal-top-nav align-items-xl-center flex-grow-1">
				<li class="nav-item">
					<a href="index.php" class="nav-link portal-top-nav__link <?php if($page == "home"){ echo "active"; } ?>">
						<i class="menu-icon tf-icon iconify iconify-lg" data-icon="bx:bx-home-circle"></i>
						<span data-i18n="Home">Home</span>
					</a>
				</li>
				<li class="nav-item">
					<a href="properties.php" class="nav-link portal-top-nav__link <?php if($page == "properties"){ echo "active"; } ?>">
						<i class="menu-icon tf-icon iconify iconify-lg" data-icon="bx:bx-building-house"></i>
						<span data-i18n="Properties">Properties</span>
					</a>
				</li>
				<?php if($_SESSION['user_type'] == "2") { ?>
				<li class="nav-item">
					<a href="admin-my-bids.php" class="nav-link portal-top-nav__link <?php if($page == "mybids"){ echo "active"; } ?>">
						<i class="menu-icon tf-icon iconify iconify-lg" data-icon="bx:bx-list-check"></i>
						<span data-i18n="My Bids (Admin)">My Bids</span>
					</a>
				</li>
				<?php }else{ ?>
				<li class="nav-item">
					<a href="my-bids.php" class="nav-link portal-top-nav__link <?php if($page == "mybids"){ echo "active"; } ?>">
						<i class="menu-icon tf-icon iconify iconify-lg" data-icon="bx:bx-list-check"></i>
						<span data-i18n="My Bids">My Bids</span>
					</a>
				</li>
				<?php } ?>
				<li class="nav-item">
					<a href="my-contacts.php" class="nav-link portal-top-nav__link <?php if($page == "mycontacts"){ echo "active"; } ?>">
						<i class="menu-icon tf-icon iconify iconify-lg" data-icon="bx:bx-book-content"></i>
						<span data-i18n="My Contacts">My Contacts</span>
					</a>
				</li>
				<?php if($_SESSION['user_type'] == "2") { ?>
				<li class="nav-item dropdown portal-top-nav__dropdown">
					<a href="javascript:void(0);" class="nav-link portal-top-nav__link dropdown-toggle <?php if($page == "admin" || $page == "admin-users" || $page == "admin-bids" || $page == "reports"){ echo "active"; } ?>" data-bs-toggle="dropdown" aria-expanded="false">
						<i class="menu-icon tf-icon iconify iconify-lg" data-icon="bx:bx-cog"></i>
						<span data-i18n="Admin">Admin</span>
					</a>
					<ul class="dropdown-menu portal-top-nav__dropdown-menu">
						<li>
							<a href="admin-properties.php" class="dropdown-item <?php if($page == "admin"){ echo "active"; } ?>">
								<span data-i18n="Properties">Properties</span>
							</a>
						</li>
						<li>
							<a href="admin-users.php" class="dropdown-item <?php if($page == "admin-users"){ echo "active"; } ?>">
								<span data-i18n="Users">Users</span>
							</a>
						</li>
						<li>
							<a href="admin-bids.php" class="dropdown-item <?php if($page == "admin-bids"){ echo "active"; } ?>">
								<span data-i18n="Bids">Bids</span>
							</a>
						</li>
						<li>
							<a href="admin-reports.php" class="dropdown-item <?php if($page == "reports"){ echo "active"; } ?>">
								<span data-i18n="Reports">Reports</span>
							</a>
						</li>
					</ul>
				</li>
				<?php } ?>
			</ul>
			<ul class="navbar-nav portal-top-nav portal-top-nav--end align-items-xl-center">
				<?php if(isset($_SESSION['user_id'])) { ?>
				<li class="nav-item">
					<a class="nav-link portal-top-nav__link portal-top-nav__link--logout" href="logout.php">
						<i class="iconify iconify-lg me-1" data-icon="bx:bx-log-out"></i>
						<span class="align-middle">Logout</span>
					</a>
				</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</nav>
