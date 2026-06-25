<?php

require('system/config.php');
 
/**
 * Check if the user is logged in.
 */
if(!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])){
    //User not logged in. Redirect them back to the login.php page.
    header('Location: login.php?redirect=properties.php');
    exit;
}
$page = "mybids";
$page_title = "My Bids - Reloc8UK Portal";
$portal_extra_head = '<link rel="stylesheet" type="text/css" href="assets/css/admin-bids.css?ver='.time().'" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.css">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/preview/searchPane/dataTables.searchPane.min.css">';
include_once("views/header.php");
?>
	<div class="admin-bids-page">
		<div class="card admin-bids-panel mb-4">
			<div class="card-header admin-bids-panel__header">
				<div class="admin-bids-panel__heading">
					<span class="iconify" data-icon="mdi:clipboard-list-outline"></span>
					<div>
						<h5 class="mb-0">My Bids</h5>
						<p class="admin-bids-panel__subtitle mb-0">Track reservations, holds, and offers across properties.</p>
					</div>
				</div>
			</div>
			<div class="card-body">
				<?php
				
				// Get User Bids in variable
				$myBids = getAllBids();

				if(count($myBids) == 0){
				?>
					<div class="admin-bids-empty">
					<i class="text-center">You have no previous bids. Browse our <a href="properties.php">available properties</a>.</i>
					</div>
				<?php
				}else{
				?>
			<div class="admin-bids-table-wrap bidResultsAdmin">
				<table id="bidResults" class="table display admin-bids-table" width="100%">
					<thead>
					<tr>
						<th>Property ID</th>
						<th>User</th>
						<th>Address</th>
						<th>Rent PCM</th>
						<th>Bid Type</th>
						<th>Notes</th>
						<th>Status</th>
						<th>Hold Date</th>
						<?php
						if($_SESSION['user_type'] == 2){ 
						?>
						<th>Actions</th>
						<?php
						}
						?>
					</tr>
				</thead>
				<tbody>
				<?php
					foreach($myBids as $bid){
				?>
					<tr>
						<td><a href="view-property.php?id=<?php echo $bid['property_id']; ?>"><?php echo $bid['property_id']; ?></a></td>
						<td><?php echo $bid['name']; ?></td>
						<td><a href="view-property.php?id=<?php echo $bid['property_id']; ?>"><?php echo $bid['address1'] . ', ' . $bid['postcode']; ?></a></td>
						<td>&pound;<?php echo number_format($bid['rent_pcm'],2); ?></td>
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
							?>
						</td>
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
						</td>
						<td><?php if($bid['status']==5){
							echo '<p>Date: ' . $bid['finalised'] . '</p>';

						} 
						?></td>
						<?php
						if($_SESSION['user_type'] == 2){
						?>
						<td>
							<div class="btn-group">
								<?php if($bid['status'] == 5){ ?>
								<a href="admin-manage-bids.php?action=revoke&bid=<?php echo $bid['id']; ?>&pid=<?php echo $bid['property_id']; ?>" class="btn btn-sm btn-primary revokeBid" data-id="<?php echo $bid['id']; ?>">Revoke</a>
								<?php }else{ ?>
								<a href="admin-manage-bids.php?action=accept&bid=<?php echo $bid['id']; ?>&pid=<?php echo $bid['property_id']; ?>" class="btn btn-sm btn-primary approveBid" data-id="<?php echo $bid['id']; ?>">Approve</a>
								<?php } ?>
								<a href="admin-manage-bids.php?action=reject&bid=<?php echo $bid['id']; ?>&pid=<?php echo $bid['property_id']; ?>" class="btn btn-sm btn-danger rejectBid" data-id="<?php echo $bid['id']; ?>">Reject</a>
							</div>
						</td>
						<?php
						}
						?>
					</tr>
					<?php
					}
				?>
			</tbody>
					</table>
				</div>
				<?php
				}
				?>
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
	if ($('#bidResults').length) {
		$('#bidResults').DataTable({
			responsive: false,
			scrollX: true,
			ordering: false,
			searchPane: {
				columns: [4, 6]
			}
		});
	}
});
</script>
</body>
</html>
