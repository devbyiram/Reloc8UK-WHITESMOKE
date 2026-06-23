<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
	<div class="app-brand demo">
		<a href="index.php" class="app-brand-link">
			<span class="app-brand-wordmark" aria-label="Reloc8UK">
				<span class="app-brand-wordmark__reloc">Reloc8</span><span class="app-brand-wordmark__uk">UK</span>
			</span>
		</a>
		<a href="javascript:void(0);"
			class="layout-menu-toggle menu-link text-large d-block d-xl-none ms-auto">
			<i class="iconify iconify-lg" data-icon="bx:bx-menu"></i>
		</a>
	</div>

	<div class="menu-inner-shadow"></div>

	<ul class="menu-inner py-1">
		<li class="menu-item <?php if($page == "home"){ echo "active"; } ?>">
			<a href="index.php" class="menu-link">
				<i class="menu-icon tf-icon iconify iconify-lg" data-icon="bx:bx-home-circle"></i>
				<div data-i18n="Home">Home</div>
			</a>
		</li>
		<li class="menu-item <?php if($page == "properties"){ echo "active"; } ?>">
			<a href="properties.php" class="menu-link">
				<i class="menu-icon tf-icon iconify iconify-lg" data-icon="bx:bx-building-house"></i>
				<div data-i18n="Properties">Properties</div>
			</a>
		</li>
		<?php if($_SESSION['user_type'] == "2") { ?>
		<li class="menu-item <?php if($page == "mybids"){ echo "active"; } ?>">
			<a href="admin-my-bids.php" class="menu-link">
				<i class="menu-icon tf-icon iconify iconify-lg" data-icon="bx:bx-list-check"></i>
				<div data-i18n="My Bids (Admin)">My Bids (Admin)</div>
			</a>
		</li>
		<?php }else{ ?>
		<li class="menu-item <?php if($page == "mybids"){ echo "active"; } ?>">
			<a href="my-bids.php" class="menu-link">
				<i class="menu-icon tf-icon iconify iconify-lg" data-icon="bx:bx-list-check"></i>
				<div data-i18n="My Bids">My Bids</div>
			</a>
		</li>
		<?php } ?>
		<li class="menu-item <?php if($page == "mycontacts"){ echo "active"; } ?>">
			<a href="my-contacts.php" class="menu-link">
				<i class="menu-icon tf-icon iconify iconify-lg" data-icon="bx:bx-book-content"></i>
				<div data-i18n="My Contacts">My Contacts</div>
			</a>
		</li>
		<?php if($_SESSION['user_type'] == "2") { ?>
		<li class="menu-item <?php if($page == "admin" || $page == "admin-users" || $page == "admin-bids" || $page == "reports"){ echo "active open"; } ?>">
			<a href="javascript:void(0);" class="menu-link menu-toggle">
				<i class="menu-icon tf-icon iconify iconify-lg" data-icon="bx:bx-cog"></i>
				<div data-i18n="Admin">Admin</div>
			</a>
			<ul class="menu-sub">
				<li class="menu-item <?php if($page == "admin"){ echo "active"; } ?>">
					<a href="admin-properties.php" class="menu-link">
						<div data-i18n="Properties">Properties</div>
					</a>
				</li>
				<li class="menu-item <?php if($page == "admin-users"){ echo "active"; } ?>">
					<a href="admin-users.php" class="menu-link">
						<div data-i18n="Users">Users</div>
					</a>
				</li>
				<li class="menu-item <?php if($page == "admin-bids"){ echo "active"; } ?>">
					<a href="admin-bids.php" class="menu-link">
						<div data-i18n="Bids">Bids</div>
					</a>
				</li>
				<li class="menu-item <?php if($page == "reports"){ echo "active"; } ?>">
					<a href="admin-reports.php" class="menu-link">
						<div data-i18n="Reports">Reports</div>
					</a>
				</li>
			</ul>
		</li>
		<?php } ?>
	</ul>
</aside>
