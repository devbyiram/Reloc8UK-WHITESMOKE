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
$page = "admin";
?>
<!DOCTYPE html>
<head>
<title>Reloc8UK Admin</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<style type="text/css">
#primary-nav {
  background-color: #545659;
  color: white;
}
#primary-nav a {
	color: white;
}

</style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.css">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/preview/searchPane/dataTables.searchPane.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/font/css/open-iconic-bootstrap.min.css" integrity="sha256-BJ/G+e+y7bQdrYkS2RBTyNfBHpA9IuGaPmf9htub5MQ=" crossorigin="anonymous" />
<link rel="stylesheet" type="text/css" href="assets/css/styles.css?ver=<?php echo time(); ?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/plug-ins/preview/searchPane/dataTables.searchPane.min.js"></script>
</head>
<body class="bg-light">
<?php
include_once("views/navbar.php");
?>
	<main role="main" class="container">
		<div class="my-3 p-3 bg-white rounded shadow-sm">
			<h6 class="border-bottom border-gray pb-2 mb-0">Admin</h6>
				<div class="btn-group py-2">
					<a class="btn btn-outline btn-outline-primary" href="admin-add-property.php">Add Property</a>
					<a class="btn btn-outline btn-outline-danger" href="admin-add-user.php">Add User</a>
				</div>
		</div>
		
		<div class="my-3 p-3 bg-white rounded shadow-sm">
			<h6 class="border-bottom border-gray pb-2 mb-2">Users</h6>
			<div class="userResultsAdmin">
				<table id="userResults" class="table display" width="100%">
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
	</main>
	
	<footer>
		<div class="copyright text-center py-2">
			<small>&copy; Copyright <?php echo date("Y"); ?> Midland Relocations Limited.</small>
		</div>
	</footer>
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