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
include_once("views/header.php");
?>
	<main class="portal-main container">
		<div class="portal-card mb-4">
			<div class="portal-card-header">
				<h6>My Bids</h6>
			</div>
		</div>
		<div class="portal-card mb-4">
			<div class="portal-card-body">
				<?php
				
				// Get User Bids in variable
				$myBids = getUserBids($_SESSION['user_id']);

				if(count($myBids) == 0){
				?>
					<i class="text-center">You have no previous bids. Browse our <a href="properties.php">available properties</a>.</i>
				<?php
				}else{
				?>
			<div class="table-responsive">
				<table class="table table-striped">
					<tr>
						<th>Property ID</th>
						<th>Address</th>
						<th>Rent PCM</th>
						<th>Bid Type</th>
						<th>Notes</th>
						<th>Status</th>
						<?php
						if($_SESSION['user_type'] == 2){ 
						?>
						<th>Actions</th>
						<?php
						}
						?>
					</tr>
				<?php
					foreach($myBids as $bid){
				?>
					<tr>
						<td><a href="view-property.php?id=<?php echo $bid['property_id']; ?>"><?php echo $bid['property_id']; ?></a></td>
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
									echo "Complete";
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
							echo '<p>Date: ' . $bid['finalised'] . '</p>';

						} 
						?>
						</td>
						<?php
						if($_SESSION['user_type'] == 2){
						?>
						<td>
							<div class="btn-group">
								<?php if($bid['status'] == 5){ ?>
								<a href="admin-manage-bids.php?action=revoke&bid=<?php echo $bid['id']; ?>" class="btn btn-sm btn-primary revokeBid" data-id="<?php echo $bid['id']; ?>">Revoke</a>
								<?php }else{ ?>
								<a href="admin-manage-bids.php?action=accept&bid=<?php echo $bid['id']; ?>" class="btn btn-sm btn-primary approveBid" data-id="<?php echo $bid['id']; ?>">Approve</a>
								<?php } ?>
								<a href="admin-manage-bids.php?action=reject&bid=<?php echo $bid['id']; ?>" class="btn btn-sm btn-danger rejectBid" data-id="<?php echo $bid['id']; ?>">Reject</a>
							</div>
						</td>
						<?php
						}
						?>
					</tr>
					<?php
					}
				?>
					</table>
				</div>
				<?php
				}
				?>
			</div>
		</div>
	</main>
<?php
include_once("views/footer.php");
?>
</body>
</html>
