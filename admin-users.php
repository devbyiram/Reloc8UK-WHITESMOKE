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

$users = getUsers();
$page = "admin-users";
$page_title = "Users - Reloc8UK Admin";
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
					<span class="iconify" data-icon="mdi:account-group-outline"></span>
					<div>
						<h5 class="mb-0">Users</h5>
						<p class="admin-table-panel__subtitle mb-0">Manage council, tenant, and administrator accounts.</p>
					</div>
				</div>
				<div class="admin-table-panel__actions">
					<a class="btn admin-table-btn admin-table-btn--primary" href="admin-add-property.php">Add Property</a>
					<a class="btn admin-table-btn admin-table-btn--outline" href="admin-add-user.php">Add User</a>
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
	</div>
<?php
include_once("views/footer.php");
?>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/plug-ins/preview/searchPane/dataTables.searchPane.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('#userResults').DataTable({
		responsive:true,
		searchPane: true,
		ordering: false
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
