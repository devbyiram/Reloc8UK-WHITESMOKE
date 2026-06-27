<?php

function dashboardCountProperties($status = null){
	global $pdo;

	$sql = "SELECT COUNT(*) FROM properties WHERE soft_deleted = '0000-00-00 00:00:00'";

	if($status !== null){
		$sql .= " AND status = :status";
	}

	$stmt = $pdo->prepare($sql);

	if($status !== null){
		$stmt->bindValue(':status', $status, PDO::PARAM_INT);
	}

	$stmt->execute();

	return (int) $stmt->fetchColumn();
}

function dashboardCountBids($status = null){
	global $pdo;

	$sql = "SELECT COUNT(*) FROM property_council";

	if($status !== null){
		$sql .= " WHERE status = :status";
	}

	$stmt = $pdo->prepare($sql);

	if($status !== null){
		$stmt->bindValue(':status', $status, PDO::PARAM_STR);
	}

	$stmt->execute();

	return (int) $stmt->fetchColumn();
}

function dashboardCountUsers($userType = null){
	global $pdo;

	$sql = "SELECT COUNT(*) FROM users";

	if($userType !== null){
		$sql .= " WHERE user_type = :user_type";
	}

	$stmt = $pdo->prepare($sql);

	if($userType !== null){
		$stmt->bindValue(':user_type', $userType, PDO::PARAM_STR);
	}

	$stmt->execute();

	return (int) $stmt->fetchColumn();
}

function dashboardCountPropertiesListedSince($days = 30){
	global $pdo;

	$sql = "SELECT COUNT(*) FROM properties WHERE soft_deleted = '0000-00-00 00:00:00' AND listed >= DATE_SUB(NOW(), INTERVAL :days DAY)";

	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':days', (int) $days, PDO::PARAM_INT);
	$stmt->execute();

	return (int) $stmt->fetchColumn();
}

function getAdminDashboardSummary(){
	$totalProperties = dashboardCountProperties();
	$availableProperties = dashboardCountProperties(0);
	$reservedProperties = dashboardCountProperties(1);
	$heldProperties = dashboardCountProperties(2);
	$underOfferProperties = dashboardCountProperties(3);
	$letProperties = dashboardCountProperties(4);

	$pendingBids = dashboardCountBids('1');
	$pendingHoldBids = dashboardCountBids('2');
	$furtherInfoBids = dashboardCountBids('3');

	$totalUsers = dashboardCountUsers();
	$councilUsers = dashboardCountUsers('1');
	$tenantUsers = dashboardCountUsers('3');

	$newProperties = dashboardCountPropertiesListedSince(30);
	$actionableBids = $pendingBids + $pendingHoldBids + $furtherInfoBids;

	return array(
		'cards' => array(
			array(
				'label' => 'Total Properties',
				'value' => (string) $totalProperties,
				'icon' => 'bx:bx-building-house',
				'tone' => 'sky',
				'route' => 'admin-properties.php',
				'subtitle' => $availableProperties.' available · '.$reservedProperties.' reserved',
			),
			array(
				'label' => 'Actionable Bids',
				'value' => (string) $actionableBids,
				'icon' => 'bx:bx-list-check',
				'tone' => 'amber',
				'route' => 'admin-bids.php',
				'subtitle' => $pendingBids.' pending · '.$pendingHoldBids.' hold review',
			),
			array(
				'label' => 'Portal Users',
				'value' => (string) $totalUsers,
				'icon' => 'bx:bx-group',
				'tone' => 'violet',
				'route' => 'admin-users.php',
				'subtitle' => $councilUsers.' councils · '.$tenantUsers.' tenants',
			),
			array(
				'label' => 'Held Properties',
				'value' => (string) $heldProperties,
				'icon' => 'bx:bx-lock',
				'tone' => 'orange',
				'route' => 'properties.php?status=held',
			),
			array(
				'label' => 'Under Offer',
				'value' => (string) $underOfferProperties,
				'icon' => 'bx:bx-transfer',
				'tone' => 'indigo',
				'route' => 'properties.php?status=underoffer',
			),
			array(
				'label' => 'Let Properties',
				'value' => (string) $letProperties,
				'icon' => 'bx:bx-key',
				'tone' => 'slate',
				'route' => 'properties.php?status=let',
			),
			array(
				'label' => 'New Properties (30 days)',
				'value' => (string) $newProperties,
				'icon' => 'bx:bx-trending-up',
				'tone' => 'rose',
				'route' => 'admin-properties.php',
			),
		),
	);
}

function getCouncilDashboardSummary($userId){
	$availableProperties = dashboardCountProperties(0);

	$bids = getUserBids($userId);
	$totalBids = count($bids);
	$pendingBids = 0;

	foreach($bids as $bid){
		if($bid['status'] == '1'){
			$pendingBids++;
		}
	}

	$totalContacts = count(getAllContacts($userId));

	return array(
		'cards' => array(
			array(
				'label' => 'Available Properties',
				'value' => (string) $availableProperties,
				'icon' => 'bx:bx-building-house',
				'tone' => 'sky',
				'route' => 'properties.php',
				'subtitle' => 'Available to reserve',
			),
			array(
				'label' => 'My Bids',
				'value' => (string) $totalBids,
				'icon' => 'bx:bx-list-check',
				'tone' => 'violet',
				'route' => 'my-bids.php',
				'subtitle' => $pendingBids.' pending review',
			),
			array(
				'label' => 'My Contacts',
				'value' => (string) $totalContacts,
				'icon' => 'bx:bx-book-content',
				'tone' => 'slate',
				'route' => 'my-contacts.php',
			),
		),
	);
}
