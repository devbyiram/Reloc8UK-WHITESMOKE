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

$bids = getAllBids();
$page = "admin-bids";
$page_title = "Bids - Reloc8UK Admin";
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
					<span class="iconify" data-icon="mdi:clipboard-list-outline"></span>
					<div>
						<h5 class="mb-0">Bids</h5>
						<p class="admin-table-panel__subtitle mb-0">Review and manage reservations, holds, and offers.</p>
					</div>
				</div>
				<div class="admin-table-panel__actions">
					<a class="btn admin-table-btn admin-table-btn--primary" href="admin-add-property.php">Add Property</a>
					<a class="btn admin-table-btn admin-table-btn--outline" href="admin-add-user.php">Add User</a>
				</div>
			</div>
			<div class="card-body">
				<div class="admin-table-wrap bidResultsAdmin table-responsive">
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
									echo '<span>Date: ' . date('d/m/Y', strtotime($bid['finalised'])) . '</span>';

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
