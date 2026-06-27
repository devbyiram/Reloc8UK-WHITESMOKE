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
		<?php if(isset($_GET['bid_action'])){ ?>
		<div class="alert alert-dismissible fade show d-flex align-items-center" role="alert" style="background-color:#10b981; color:#ffffff; border:none;">
			<span class="iconify me-2" data-icon="mdi:check-circle-outline"></span>
			<span><?php echo ($_GET['bid_action'] == 'accept') ? 'Bid approved successfully.' : 'Bid rejected successfully.'; ?></span>
			<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
		<?php } ?>
		<div class="card admin-table-panel mb-4">
			<div class="card-header admin-table-panel__header">
				<div class="admin-table-panel__heading">
					<span class="iconify" data-icon="mdi:clipboard-list-outline"></span>
					<div>
						<h5 class="mb-0">Bids</h5>
						<p class="admin-table-panel__subtitle mb-0">Review and manage reservations, holds, and offers.</p>
					</div>
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
										<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#bidActionModal" data-action="accept" data-bid="<?php echo $bid['id']; ?>" data-pid="<?php echo $bid['property_id']; ?>" data-property="<?php echo htmlspecialchars($bid['address1'] . ", " . $bid['town'], ENT_QUOTES); ?>" data-user="<?php echo htmlspecialchars($bid['name'] . " (" . $bid['username'] . ")", ENT_QUOTES); ?>">Approve</button>
										<button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#bidActionModal" data-action="reject" data-bid="<?php echo $bid['id']; ?>" data-pid="<?php echo $bid['property_id']; ?>" data-property="<?php echo htmlspecialchars($bid['address1'] . ", " . $bid['town'], ENT_QUOTES); ?>" data-user="<?php echo htmlspecialchars($bid['name'] . " (" . $bid['username'] . ")", ENT_QUOTES); ?>">Reject</button>
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

	<div class="modal fade" id="bidActionModal" tabindex="-1" aria-labelledby="bidActionModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="bidActionModalLabel">Confirm</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p id="bidActionMessage">Please confirm this action.</p>
					<div class="d-flex align-items-center gap-3 p-3 border rounded">
						<span class="iconify" data-icon="mdi:clipboard-text-outline" style="font-size:40px; color:#94a3b8;"></span>
						<div>
							<p class="mb-1 fw-semibold" id="bidActionProperty"></p>
							<p class="mb-0 text-muted" id="bidActionUser"></p>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<a href="#" id="bidActionConfirm" class="btn btn-primary">Confirm</a>
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
(function(){
	var bidModal = document.getElementById('bidActionModal');
	if(bidModal){
		bidModal.addEventListener('show.bs.modal', function(event){
			var btn = event.relatedTarget;
			if(!btn){ return; }
			var action = btn.getAttribute('data-action') || '';
			var bid = btn.getAttribute('data-bid') || '';
			var pid = btn.getAttribute('data-pid') || '';
			var isAccept = (action === 'accept');
			document.getElementById('bidActionModalLabel').textContent = isAccept ? 'Approve Bid' : 'Reject Bid';
			document.getElementById('bidActionMessage').textContent = isAccept
				? 'Please confirm that you would like to approve this bid.'
				: 'Please confirm that you would like to reject this bid.';
			document.getElementById('bidActionProperty').textContent = btn.getAttribute('data-property') || '';
			document.getElementById('bidActionUser').textContent = btn.getAttribute('data-user') || '';
			var confirmBtn = document.getElementById('bidActionConfirm');
			confirmBtn.textContent = isAccept ? 'Approve' : 'Reject';
			confirmBtn.className = 'btn ' + (isAccept ? 'btn-primary' : 'btn-danger');
			confirmBtn.setAttribute('href', 'admin-manage-bids.php?action=' + encodeURIComponent(action) + '&bid=' + encodeURIComponent(bid) + '&pid=' + encodeURIComponent(pid));
		});
	}
})();
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
