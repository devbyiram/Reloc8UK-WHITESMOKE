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
$page = "admin";
$page_title = "Properties - Reloc8UK Admin";
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
									        echo "DELETED";
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
	</div>
<?php
include_once("views/footer.php");
?>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/plug-ins/preview/searchPane/dataTables.searchPane.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	if ($('#propertyResults').length) {
		$('#propertyResults').DataTable({
			responsive: true,
			searchPane: true,
			ordering: false,
			columnDefs: [
				{
					targets: [2, 3],
					render: function(data, type) {
						if (type !== 'display') {
							return $('<div>').html(data).text().trim();
						}
						return data;
					}
				}
			]
		});
	}
});
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
