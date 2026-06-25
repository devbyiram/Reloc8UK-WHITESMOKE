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

if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 2){
	// User isn't an administrator, redirect back to home page.
	header('Location: index.php');
	exit;
}

$properties = getAllPropertiesAdmin();
$users = getUsers();
$bids = getAllBids();
$page = "admin";
$page_title = "Reloc8UK Admin";
$portal_extra_head = '<link rel="stylesheet" type="text/css" href="assets/css/admin-tables.css?ver='.time().'">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.css">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/preview/searchPane/dataTables.searchPane.min.css">';
include_once("views/header.php");
?>
	<div class="admin-table-page">
		<div class="card admin-table-panel mb-4">
			<div class="card-header admin-table-panel__header">
				<div class="admin-table-panel__heading">
					<span class="iconify" data-icon="mdi:home-city-outline"></span>
					<div>
						<h5 class="mb-0">Properties</h5>
						<p class="admin-table-panel__subtitle mb-0">Manage listings, status, and property records.</p>
					</div>
				</div>
				<div class="admin-table-panel__actions">
					<a class="btn admin-table-btn admin-table-btn--primary" href="admin-add-property.php">Add Property</a>
					<a class="btn admin-table-btn admin-table-btn--outline" href="admin-add-user.php">Add User</a>
				</div>
			</div>
			<div class="card-body">
				<div class="admin-table-wrap propertyResultsAdmin">
					<table id="propertyResults" class="table display admin-table" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>Address</th>
								<th>Status</th>
								<th>Added</th>
								<th>Reference</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($properties["properties"] as $property) { ?>
								<tr
								<?php if($property['soft_deleted'] != '0000-00-00 00:00:00'){ echo 'class="bg-warning"'; } ?>>
									<td><?php echo $property['id']; ?></td>
									<td><?php echo $property['address1'] . ", " . $property['county'] . ", " . $property['postcode']; ?></td>
									<td><?php
									    if($property['soft_deleted'] == '0000-00-00 00:00:00'){
    									switch($property['status']){
    										case 0:
    											echo "Available";
    											break;
    										case 1:
    											echo "Reserved";
    											break;
    										case 2:
    											echo "Held";
    											break;
    										case 3:
    											echo "Under Offer";
    											break;
    										case 4:
    											echo "Let";
    											break;
    										case 5:
    										    echo "Available Soon";
    										    break;
    											
    									}
								    }else{
								        echo "<i>DELETED</i>";
								    }
								?></td>
									<td><span title="<?php echo date("h:i:s d/m/Y",strtotime($property['listed'])); ?>"><?php echo time_elapsed_string($property['listed']); ?></span></td>
									<td><?php echo $property['reference']; ?></td>
									<td><div class="btn-group"><a href="view-property.php?id=<?php echo $property['id']; ?>" target="_blank" class="btn btn-sm btn-warning">View</a><a href="admin-edit-property.php?id=<?php echo $property['id']; ?>" class="btn btn-sm btn-primary">Edit</a><a href="admin-delete-property.php?id=<?php echo $property['id']; ?>" class="btn btn-sm btn-danger">Delete</a></div></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="card admin-table-panel mb-4">
			<div class="card-header admin-table-panel__header">
				<div class="admin-table-panel__heading">
					<span class="iconify" data-icon="mdi:account-group-outline"></span>
					<div>
						<h5 class="mb-0">Users</h5>
						<p class="admin-table-panel__subtitle mb-0">Registered councils, admins, and tenants.</p>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="admin-table-wrap userResultsAdmin">
					<table id="userResults" class="table display admin-table" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>Username</th>
								<th>Name</th>
								<th>Email</th>
								<th>User Type</th>
								<th>Last Login</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($users as $user){ ?>
								<tr>
									<td><?php echo $user['id']; ?></td>
									<td><?php echo $user['username']; ?></td>
									<td><?php echo $user['name']; ?></td>
									<td><?php echo $user['email_address']; ?></td>
									<td><?php
										switch($user['user_type']){
											case 1:
												echo "Council";
												break;
											case 2:
												echo "Admin";
												break;
											case 3:
												echo "Tenant";
												break;
											default:
												echo "Council";
												break;
										} ?></td>
									<td><?php echo time_elapsed_string($user['last_login']); ?></td>
									<td><div class="btn-group"><a href="admin-edit-user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary">Edit</a><a href="admin-delete-user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger">Delete</a></div></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="card admin-table-panel mb-4">
			<div class="card-header admin-table-panel__header">
				<div class="admin-table-panel__heading">
					<span class="iconify" data-icon="mdi:gavel"></span>
					<div>
						<h5 class="mb-0">Bids</h5>
						<p class="admin-table-panel__subtitle mb-0">Reservations, holds, and offers awaiting action.</p>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="admin-table-wrap bidResultsAdmin">
					<table id="bidResults" class="table display admin-table" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>Property</th>
								<th>User</th>
								<th>Bid Type</th>
								<th>Notes</th>
								<th>Status</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach($bids as $bid){
							?>
							<tr>
								<td><?php echo $bid['id']; ?></td>
								<td><?php echo $bid['address1'] . ", " . $bid['town']; ?></td>
								<td><?php echo $bid['name'] . " (" . $bid['username'] . ")"; ?></td>
								<td><?php
									switch ($bid['type']) {
										case "1":
											echo "Reservation";
											break;
										case "2":
											echo "Hold";
											break;
										case "3":
											echo "Offer";
											break;
										case "4":
											echo "Interested";
											break;
										default:
											echo "Reservation";
											break;
									}
									?></td>
								<td><?php echo $bid['bid_notes']; ?></td>
								<td><?php 
									switch ($bid["status"]) {
										case "1":
											echo "Pending";
											break;
										case "2":
											echo "Pending Hold";
											break;
										case "3":
											echo "Further Information Required";
											break;
										case "4":
											echo "Rejected";
											break;
										case "5":
											echo "Held";
											break;
									}
								?>
								<?php if($bid['status']==5){
									echo '<span>Date: ' . $bid['finalised'] . '</span>';

								} 
								?>
								</td>
								<td><div class="btn-group">
										<?php if($bid['type']==3) { ?>
											<a href="admin-print-bid.php?id=<?php echo $bid['id']; ?>" class="btn btn-sm btn-warning" target="_blank">Print</a>
										<?php } ?>
										<a href="admin-manage-bids.php?action=accept&bid=<?php echo $bid['id']; ?>&pid=<?php echo $bid['property_id']; ?>" class="btn btn-sm btn-primary" data-id="<?php echo $bid['id']; ?>">Approve</a>
										<a href="admin-manage-bids.php?action=reject&bid=<?php echo $bid['id']; ?>&pid=<?php echo $bid['property_id']; ?>" class="btn btn-sm btn-danger" data-id="<?php echo $bid['id']; ?>">Reject</a>
									</div></td>
							</tr>
							<?php
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
<?php
include_once("views/footer.php");
?>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/plug-ins/preview/searchPane/dataTables.searchPane.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#propertyResults').DataTable({
		responsive:true,
		searchPane: true
	});
	$('#userResults').DataTable({
		responsive:true,
		searchPane: true,
		ordering: false
	});
	$('#bidResults').DataTable({
		responsive:true,
		searchPane: true,
		ordering: false,
	});
} );
</script>
<script type="text/javascript">
$(document).ready(function(){
	$('.acceptBid').on('click',function(e){
		e.preventDefault();
		var id = $(this).attr('data-id');
		if (confirm('Are you sure you want to approve this bid?')) {
			$.ajax({
				url:"admin-manage-bids.php?action=accept&bid="+id,
				method:"POST",
				success:function(data){
					
				}
			});
		}
	});
});
</script>
</body>
</html>