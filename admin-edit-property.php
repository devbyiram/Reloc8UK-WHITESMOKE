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

if(isset($_POST['update-property']) && $_POST['update-property'] == 1){
	
	global $pdo;
	
	$sql = "UPDATE properties SET status=? WHERE id=?";
	
	$pdo->prepare($sql)->execute([$_POST['status'],$_POST['pid']]);

	if($_POST['status'] == 4){
		notifyCouncilPropertyStatus($_POST['pid'],'let');
	}
	if($_POST['status'] == 6){
		$cUser = $_POST['council_user_id'];
		$pId = $_POST['pid'];
		$holdDate = $_POST['hold-date'];
		$notes = $_POST['notes'];

		$sql = "INSERT INTO property_council (user_id, property_id, bid_notes, type, status, finalised) VALUES(?,?,?,2,5,?)";

		$pdo->prepare($sql)->execute([$cUser,$pId,$notes,$holdDate]);
	}
}

if(isset($_POST['relet']) && $_POST['relet'] == 1){
    global $pdo;
    
    $id = $_POST['id'];
    $date_added = date('Y-m-d');
    
    $sql = "UPDATE properties SET `date_added`=?, `listed`=NOW(), `date_available`=?,`status`='0' WHERE `id`=?";
    
    $pdo->prepare($sql)->execute([$date_added,$date_added,$id]);
}

if(isset($_POST['edit_property']) && $_POST['edit_property'] == 1){
	
	global $pdo;
	
	$id = $_POST['id'];
	$address1 = $_POST['address1'];
	$address2 = $_POST['address2'];
	$address3 = $_POST['address3'];
	$town = $_POST['town'];
	$county = $_POST['county'];
	$postcode = $_POST['postcode'];
	$council = $_POST['council_id'];
	$rent_pcm = $_POST['rent_pcm'];
	$date_available = $_POST['date_available'];
	$reference = $_POST['reference'];
	$bedrooms = $_POST['bedrooms'];
	$bathrooms = $_POST['bathrooms'];
	$furniture_included = $_POST['furniture_included'];
	$garden_front = $_POST['front_garden'];
	$garden_rear = $_POST['rear_garden'];
	$central_heating = $_POST['central_heating'];
	$notes = $_POST['notes'];
	$is_featured = $_POST['is_featured'];
	$tenancy_type = $_POST['tenancy_type'];
	$same_day_move = $_POST['same_day_move'];
	$date_added = $_POST['date_added'];
	
	// Set query in to $sql variable
	$sql = "UPDATE properties SET `address1`=?,`address2` =?, `address3`=?, `town`=?,`county`=?,`postcode`=?,`council_id`=?,`rent_pcm`=?,`date_available`=?,`reference`=?,`bedrooms`=?,`bathrooms`=?,`furniture_included`=?,`garden_front`=?,`garden_rear`=?,`central_heating`=?,`notes`=?, `is_featured`=?, `tenancy_type`=?, `same_day_move`=? WHERE `id` = ?";
	// Prepare & execute query using $pdo global
	$pdo->prepare($sql)->execute([$address1, $address2, $address3, $town, $county, $postcode, $council, $rent_pcm, $date_available, $reference, $bedrooms, $bathrooms, $furniture_included, $garden_front, $garden_rear, $central_heating, $notes, $is_featured, $tenancy_type, $same_day_move, $id]);
	
	
}

$property = getProperty($_GET['id']);
$councilUsers = getCouncilUsers();
$page = "admin";
$page_title = 'Reloc8UK Admin - Edit Property ' . $property['id'];
$portal_extra_head = '<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.css">
<link rel="stylesheet" type="text/css" href="assets/css/admin-crm-forms.css?ver='.time().'">';
include_once("views/header.php");
?>
		<div class="admin-crm-page">
		<div class="card admin-crm-page-header mb-4">
			<div class="card-body">
				<div class="admin-crm-page-header__row">
					<div>
						<h1 class="admin-crm-page-header__title">Edit Property</h1>
						<p class="admin-crm-page-header__subtitle">You are currently editing property ID: <?php echo $property['id']; ?></p>
						<span class="admin-crm-page-header__meta"><span class="iconify" data-icon="mdi:identifier"></span>Property #<?php echo $property['id']; ?></span>
					</div>
					<a href="view-property.php?id=<?php echo $property['id']; ?>" class="btn btn-crm-secondary" target="_blank"><span class="iconify" data-icon="mdi:eye-outline"></span>View Property</a>
				</div>
			</div>
		</div>
		<div class="card admin-crm-panel mb-4">
			<div class="card-header">
				<span class="iconify" data-icon="mdi:toggle-switch-outline"></span>
				<h5>Property status</h5>
			</div>
			<div class="card-body">
			<form action="admin-edit-property.php?id=<?php echo $property['id']; ?>" method="POST" class="mb-0">
				<input type="hidden" name="update-property" value="1">
				<input type="hidden" name="pid" value="<?php echo $property['id']; ?>">
				<div class="admin-crm-toolbar">
					<div class="admin-crm-toolbar__field">
						<label for="propertyStatus" class="admin-crm-label">Property Status</label>
						<select name="status" id="propertyStatus" class="form-select admin-crm-control">
						<option value="0" <?php if($property['status'] == 0){ echo "selected"; } ?>>Available</option>
						<option value="1" <?php if($property['status'] == 1){ echo "selected"; } ?>>Reserved</option>
						<option value="2" <?php if($property['status'] == 2){ echo "selected"; } ?>>Pending Hold</option>
						<option value="3" <?php if($property['status'] == 3){ echo "selected"; } ?>>Under Offer</option>
						<option value="4" <?php if($property['status'] == 4){ echo "selected"; } ?>>Let</option>
						<option value="5" <?php if($property['status'] == 5){ echo "selected"; } ?>>Available Soon</option>
						<option value="6" <?php if($property['status'] == 6){ echo "selected"; } ?>>Hold Approved</option>
					</select>
					</div>
					<button class="btn btn-crm-secondary" type="submit"><span class="iconify" data-icon="mdi:content-save-outline"></span>Update</button>
				</div>
				<div class="holdPlaceholder d-none admin-crm-subpanel">
					<div class="row g-3">
							<div class="col-md-4">
								<label for="council_user_id" class="admin-crm-label">Council User</label>
								<select name="council_user_id" id="council_user_id" class="form-select admin-crm-control">
									<option value="9999">Non Portal User</option>
									<?php
									foreach($councilUsers as $cUser){
										echo '<option value="'.$cUser['id'].'">'.$cUser['name'].'</option>';
									}
									?>
								</select>
							</div>
							<div class="col-md-4">
								<label for="hold-date" class="admin-crm-label">Hold Date</label>
								<input type="date" name="hold-date" id="hold-date" class="form-control admin-crm-control" placeholder="Hold date" />
							</div>
							<div class="col-md-4">
								<label for="hold-officer" class="admin-crm-label">Hold Officer (Notes)</label>
								<input type="text" name="notes" id="hold-officer" class="form-control admin-crm-control" placeholder="Hold Officer (notes)" />
							</div>
						</div>
				</div>
			</form>
			</div>
		</div>
		<?php
		if($property['status'] != 0 || $property['status'] != 5){
		?>
		<div class="card admin-crm-panel mb-4">
			<div class="card-header">
				<span class="iconify" data-icon="mdi:home-refresh-outline"></span>
				<h5>Re-let property</h5>
			</div>
			<div class="card-body">
		    <form method="post" action="admin-edit-property.php?id=<?php echo $property['id']; ?>">
		        <input type="hidden" name="relet" value="1">
		        <input type="hidden" name="id" value="<?php echo $property['id']; ?>">
		        <button type="submit" class="btn btn-crm-dark"><span class="iconify" data-icon="mdi:check-circle-outline"></span>Mark Property Available</button>
		    </form>
			</div>
		</div>
		<?php 
		}
		?>
		<div class="card admin-crm-panel mb-4">
			<div class="card-header">
				<span class="iconify" data-icon="mdi:home-edit-outline"></span>
				<h5>Property details</h5>
			</div>
			<div class="card-body">
			<form method="post" action="admin-edit-property.php?id=<?php echo $property['id']; ?>">
				<input type="hidden" name="id" value="<?php echo $property['id']; ?>">
				<input type="hidden" name="edit_property" value="1">
				<div class="admin-crm-section">
					<h6 class="admin-crm-section__title"><span class="iconify" data-icon="mdi:map-marker-outline"></span>Address</h6>
					<div class="row g-3">
						<div class="col-md-4 col-sm-6">
							<label for="address1" class="admin-crm-label">Address 1</label>
							<input type="text" class="form-control admin-crm-control" name="address1" value="<?php echo $property['address1']; ?>" id="address1">
						</div>
						<div class="col-md-4 col-sm-6">
							<label for="address2" class="admin-crm-label">Address 2</label>
							<input type="text" class="form-control admin-crm-control" name="address2" value="<?php echo $property['address2']; ?>" id="address2">
						</div>
						<div class="col-md-4 col-sm-6">
							<label for="address3" class="admin-crm-label">Address 3</label>
							<input type="text" class="form-control admin-crm-control" name="address3" value="<?php echo $property['address3']; ?>" id="address3">
						</div>
						<div class="col-md-4 col-sm-6">
							<label for="town" class="admin-crm-label">Town</label>
							<input type="text" class="form-control admin-crm-control" name="town" value="<?php echo $property['town']; ?>" id="town">
						</div>
						<div class="col-md-4 col-sm-6">
							<label for="county" class="admin-crm-label">County</label>
							<input type="text" class="form-control admin-crm-control" name="county" value="<?php echo $property['county']; ?>" id="county">
						</div>
						<div class="col-md-4 col-sm-6">
							<label for="postcode" class="admin-crm-label">Postcode</label>
							<input type="text" class="form-control admin-crm-control" name="postcode" value="<?php echo $property['postcode']; ?>" id="postcode">
						</div>
					</div>
				</div>
				<div class="admin-crm-section">
					<h6 class="admin-crm-section__title"><span class="iconify" data-icon="mdi:file-document-outline"></span>Tenancy information</h6>
					<div class="row g-3">
						<div class="col-md-4 col-sm-12">
							<label for="property_type" class="admin-crm-label">Property Type</label>
							<select name="property_type" class="form-select admin-crm-control">
								<?php
									global $propertyTypes;
									
									foreach($propertyTypes as $key=>$value){
										echo '<option value="'.$key.'">'.$value.'</option>';
									}
								?>
							</select>
						</div>
						<div class="col-md-4 col-sm-12">
							<label for="tenancy_type" class="admin-crm-label">Tenancy Type</label>
							<select name="tenancy_type" class="form-select admin-crm-control">
								<option value="" selected disabled>-- Select type --</option>
								<option value="6 Month AST" <?php if($property['tenancy_type'] == "6 Month AST") { echo "selected"; } ?>>6 Month AST</option>
								<option value="12 Month AST" <?php if($property['tenancy_type'] == "12 Month AST") { echo "selected"; } ?>>12 Month AST</option>
								<option value="18 Month AST" <?php if($property['tenancy_type'] == "18 Month AST") { echo "selected"; } ?>>18 Month AST</option>
								<option value="24 Month AST" <?php if($property['tenancy_type'] == "24 Month AST") { echo "selected"; } ?>>24 Month AST</option>
							</select>
						</div>
						<div class="col-md-4 col-sm-12">
							<label for="same_day_move" class="admin-crm-label">Same Day Move</label>
							<select name="same_day_move" class="form-select admin-crm-control">
								<option value="0" <?php if($property['same_day_move'] == 0){ echo "selected"; } ?>>No</option>
								<option value="1" <?php if($property['same_day_move'] == 1){ echo "selected"; } ?>>Yes</option>
							</select>
						</div>
						<div class="col-md-4 col-sm-12">
							<label for="date_available" class="admin-crm-label">Date Available</label>
							<input type="date" class="form-control admin-crm-control" placeholder="Date Available" name="date_available" value="<?php echo $property['date_available']; ?>">
						</div>
						<div class="col-md-4 col-sm-12">
							<label for="reference" class="admin-crm-label">Reference <small class="text-muted">(If applicable)</small></label>
							<input type="text" class="form-control admin-crm-control" placeholder="Reference" name="reference" value="<?php echo $property['reference']; ?>">
						</div>
						<div class="col-md-4 col-sm-12">
							<label for="rent_pcm" class="admin-crm-label">Rent PCM</label>
							<div class="input-group">
								<span class="input-group-text" id="pound-sign-addon">&pound;</span>
								<input type="number" class="form-control admin-crm-control" placeholder="Rent PCM" name="rent_pcm" aria-label="Rent PCM" aria-describedby="pound-sign-addon" value="<?php echo $property['rent_pcm']; ?>">
							</div>
						</div>
					</div>
				</div>
				<div class="admin-crm-section">
					<h6 class="admin-crm-section__title"><span class="iconify" data-icon="mdi:home-search-outline"></span>Property attributes</h6>
					<div class="row g-3">
						<div class="col-md-4 col-sm-6">
							<label for="bedrooms" class="admin-crm-label">Bedrooms</label>
							<select class="form-select admin-crm-control" name="bedrooms" required>
								<option value="" disabled selected>-- Bedrooms --</option>
								<?php
									for ($x = 0; $x <= 8; $x++) {
										echo '<option value="'.$x.'"';
										if($property['bedrooms'] == $x){
											echo ' selected';
										}
										echo '>'.$x.' room(s)</option>';
									}
								?>
							</select>
						</div>
						<div class="col-md-4 col-sm-6">
							<label for="bathrooms" class="admin-crm-label">Bathrooms</label>
							<select class="form-select admin-crm-control" name="bathrooms" required>
								<option value="" disabled selected>-- Bathrooms --</option>
								<?php
									for ($x = 0; $x <= 8; $x++) {
										echo '<option value="'.$x.'"';
										if($property['bathrooms'] == $x){
											echo ' selected';
										}
										echo '>'.$x.' bathroom(s)</option>';
									}
								?>
							</select>
						</div>
						<div class="col-md-4 col-sm-6">
							<label for="furniture_included" class="admin-crm-label">Furniture Included</label>
							<select class="form-select admin-crm-control" name="furniture_included" required>
								<option value="" disabled selected>-- Furniture --</option>
								<option value="0" <?php if($property['furniture_included'] == 0){ echo " selected"; } ?>>No</option>
								<option value="1" <?php if($property['furniture_included'] == 1){ echo " selected"; } ?>>Yes</option>
							</select>
						</div>
						<div class="col-md-4 col-sm-6">
							<label for="front_garden" class="admin-crm-label">Front Garden</label>
							<select name="front_garden" class="form-select admin-crm-control" required>
								<option value="" disabled selected>-- Front Garden --</option>
								<option value="0" <?php if($property['garden_front'] == 0){ echo " selected"; } ?>>No</option>
								<option value="1" <?php if($property['garden_front'] == 1){ echo " selected"; } ?>>Yes</option>
							</select>
						</div>
						<div class="col-md-4 col-sm-6">
							<label for="rear_garden" class="admin-crm-label">Rear Garden</label>
							<select name="rear_garden" class="form-select admin-crm-control" required>
								<option value="" disabled selected>-- Rear Garden --</option>
								<option value="0" <?php if($property['garden_rear'] == 0){ echo " selected"; } ?>>No</option>
								<option value="1" <?php if($property['garden_rear'] == 1){ echo " selected"; } ?>>Yes</option>
							</select>
						</div>
						<div class="col-md-4 col-sm-6">
							<label for="central_heating" class="admin-crm-label">Central Heating</label>
							<select name="central_heating" class="form-select admin-crm-control" required>
								<option value="" disabled selected>-- Central Heating --</option>
								<option value="0" <?php if($property['central_heating'] == 0){ echo " selected"; } ?>>No</option>
								<option value="1" <?php if($property['central_heating'] == 1){ echo " selected"; } ?>>Yes</option>
							</select>
						</div>
					</div>
				</div>
				<div class="admin-crm-section">
					<h6 class="admin-crm-section__title"><span class="iconify" data-icon="mdi:text-box-outline"></span>Additional details</h6>
					<div class="row g-3">
						<div class="col-md-6">
						    <label for="date_added" class="admin-crm-label">Date added</label>
						    <input type="date" name="date_added" class="form-control admin-crm-control" value="<?php echo date('Y-m-d',strtotime($property['date_added'])); ?>">
						</div>
						<div class="col-md-6">
							<label for="is_featured" class="admin-crm-label">Is this property featured?</label>
							<select name="is_featured" class="form-select admin-crm-control">
								<option value="0" <?php if($property['is_featured'] == 0){ echo " selected"; } ?>>No</option>
								<option value="1" <?php if($property['is_featured'] == 1){ echo " selected"; } ?>>Yes</option>
							</select>
						</div>
						<div class="col-12">
							<label for="notes" class="admin-crm-label">Additional Information</label>
							<textarea name="notes" id="notes" class="form-control admin-crm-control"><?php echo $property['notes']; ?></textarea>
						</div>
					</div>
				</div>
				<div class="admin-crm-submit-wrap">
					<button type="submit" class="btn btn-crm-primary btn-lg"><span class="iconify" data-icon="mdi:content-save-outline"></span>Update Property</button>
				</div>
			</form>
			</div>
		</div>
		<div class="card admin-crm-panel mb-4">
			<div class="card-header">
				<span class="iconify" data-icon="mdi:image-multiple-outline"></span>
				<h5>Property images</h5>
			</div>
			<div class="card-body">
					<div class="admin-crm-table-wrap mb-3">
							<div id="image_table" class="table-responsive">
							
							</div>
					</div>
						<div class="col-md-12 px-0">
							<label for="propertyImages" class="admin-crm-label">Upload images</label>
							<input type="file" class="form-control admin-crm-control" id="propertyImages" name="propertyImages" multiple>
							<span id="error_multiple_files"></span>
						</div>
			</div>
		</div>
		<div class="card admin-crm-panel mb-4">
			<div class="card-header">
				<span class="iconify" data-icon="mdi:video-outline"></span>
				<h5>Property video</h5>
			</div>
			<div class="card-body admin-crm-media-panel">
					<?php 
					if(empty($property['filename'])){
					?>
					<div class="col-md-12 px-0">
						<form action="admin-property-images.php?id=<?php echo $property['id']; ?>" method="post" id="propertyImages" enctype="multipart/form-data">
							<input type="hidden" name="uploadVideo" value="1">
							<input type="hidden" name="pid" value="<?php echo $property['id']; ?>">
							
							<div class="mb-3">
								<label class="admin-crm-label">Choose video</label>
								<input type="file" class="form-control admin-crm-control" name="propertyVideo">
							</div>
							<div class="text-center">
								<button class="btn btn-crm-secondary" type="submit"><span class="iconify" data-icon="mdi:upload-outline"></span>Upload</button>
							</div>
						</form>
					</div>
					<?php 
					}else{
					?>
						<div class="admin-crm-table-wrap">
							<table class="table table-striped table-bordered mb-0">
								<tr>
									<th>ID</th>
									<th>Filename</th>
									<th>Actions</th>
								</tr>
								<tr>
									<th><?php echo $property['vid']; ?></th>
									<th><?php echo $property['filename']; ?></th>
									<th><a href="admin-property-images.php?action=deleteVideo&vid=<?php echo $property['vid']; ?>&pid=<?php echo $property['id']; ?>" class="btn btn-danger btn-sm">Delete</a></th>
							</table>
						</div>
					<?php
					}
					?>
			</div>
		</div>
		<div class="card admin-crm-panel mb-4">
			<div class="card-header">
				<span class="iconify" data-icon="mdi:file-document-multiple-outline"></span>
				<h5>Property files</h5>
			</div>
			<div class="card-body admin-crm-media-panel admin-crm-media-panel--files">
					<form action="admin-manage-files.php" method="post" id="propertyFiles" enctype="multipart/form-data">
						<input type="hidden" name="uploadFile" value="1">
						<input type="hidden" name="pid" value="<?php echo $property['id']; ?>">
						<div class="admin-crm-file-row mb-3">
							<div class="admin-crm-file-input">
								<label class="admin-crm-label">Choose file</label>
								<input type="file" class="form-control admin-crm-control" name="property_files">
							</div>
							<div class="admin-crm-file-type">
								<label for="file_type" class="admin-crm-label">Type of file</label>
								<select name="file_type" class="form-select admin-crm-control">
										<option value="EPC">EPC Certificate</option>
										<option value="Gas Safety">Gas Safety</option>
										<option value="Electric Cert">Electric Certificate</option>
										<option value="Inspection Report">Inspection Report</option>
										<option value="Brochure">Brochure</option>
										<option value="Other">Other</option>
									</select>
							</div>
							<button class="btn btn-crm-secondary" type="submit"><span class="iconify" data-icon="mdi:upload-outline"></span>Upload</button>
						</div>
								<span id="error_multiple_files_files"></span>
					</form>
					<div class="admin-crm-table-wrap pt-2" id="propertyFiles">
						<table class="table table-bordered table-striped table-hover mb-0">	
							<tr>
								<th>ID</th>
								<th>File Name</th>
								<th>File Type</th>
								<th>Actions</th>
							</tr>
							<?php
								echo adminPropertyFiles($property['id']);
							?>
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
<script type="text/javascript">
$(document).ready(function() {
    $('#propertyResults').DataTable({
		responsive:true
	});
	$('#userResults').DataTable({
		responsive:true
	});
} );
</script>
<script type="text/javascript">
$(document).ready(function(){
 load_image_data();
 function load_image_data()
 {
  $.ajax({
   url:"admin-property-images.php?action=fetch&pid=<?php echo $property['id']; ?>",
   method:"POST",
   success:function(data)
   {
    $('#image_table').html(data);
   }
  });
 } 
 $('#propertyImages').change(function(){
  var error_images = '';
  var form_data = new FormData();
  var files = $('#propertyImages')[0].files;
  if(files.length > 10)
  {
   error_images += 'You can not select more than 10 files';
  }
  else
  {
   for(var i=0; i<files.length; i++)
   {
    var name = document.getElementById("propertyImages").files[i].name;
    var ext = name.split('.').pop().toLowerCase();
    if(jQuery.inArray(ext, ['gif','png','jpg','jpeg']) == -1) 
    {
     error_images += '<p>Invalid '+i+' File</p>';
    }
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById("propertyImages").files[i]);
    var f = document.getElementById("propertyImages").files[i];
    var fsize = f.size||f.fileSize;
    if(fsize > 100000000)
    {
     error_images += '<p>' + i + ' File is too large to upload.</p>';
    }
    else
    {
     form_data.append("file[]", document.getElementById('propertyImages').files[i]);
    }
   }
  }
  if(error_images == '')
  {
   $.ajax({
    url:"admin-property-images.php?action=upload&pid=<?php echo $property['id']; ?>",
    method:"POST",
    data: form_data,
    contentType: false,
    cache: false,
    processData: false,
    beforeSend:function(){
     $('#error_multiple_files').html('<br /><label class="text-primary">Uploading...</label>');
    },   
    success:function(data)
    {
     $('#error_multiple_files').html('<br /><label class="text-success">Uploaded</label>');
     load_image_data();
    }
   });
  }
  else
  {
   $('#multiple_files').val('');
   $('#error_multiple_files').html("<span class='text-danger'>"+error_images+"</span>");
   return false;
  }
 });  
 $(document).on('click', '.delete', function(){
  var image_id = $(this).attr("id");
  var image_name = $(this).data("image_name");
  if(confirm("Are you sure you want to remove this image?"))
  {
   $.ajax({
    url:"admin-property-images.php?action=delete&pid=<?php echo $property['id']; ?>",
    method:"POST",
    data:{image_id:image_id, image_name:image_name},
    success:function(data)
    {
     load_image_data();
     alert("Image removed");
    }
   });
  }
 });  
});
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#propertyStatus").on("change",function(e){
			if($(this).val()=="6"){
				$(".holdPlaceholder").toggleClass('d-none');
			}
		});

	});
</script>
</body>
</html>