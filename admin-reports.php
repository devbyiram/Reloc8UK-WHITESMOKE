<?php
require('system/config.php');
 
$page = "reports";
$councils = getAllCouncils();

if($_POST){
	$report = generateUserReport($_POST['council'],$_POST['date-from'],$_POST['date-to'],$_POST['date-range-predefined'],$_POST['export_to_csv']);
}

if(!empty($_GET['share']) && $_GET['share'] == "true"){
	$portal_hide_navbar = true;
}

$page_title = "Reports - Reloc8UK Portal";
$portal_extra_head = '<link rel="stylesheet" type="text/css" href="assets/css/admin-crm-forms.css?ver='.time().'">';
include_once("views/header.php");
?>
		<div class="admin-crm-page">
		<div class="card admin-crm-page-header mb-4">
			<div class="card-body">
				<div class="admin-crm-page-header__row">
					<div>
						<h1 class="admin-crm-page-header__title">Reports</h1>
						<p class="admin-crm-page-header__subtitle">Generate a report of users registered within a given timeframe for an individual council, or all councils combined.</p>
						<?php if(!empty($report)){ ?>
						<span class="admin-crm-page-header__meta">
							<span class="iconify" data-icon="mdi:account-multiple-outline"></span>
							<?= count($report); ?> user<?= count($report) === 1 ? '' : 's'; ?> found
						</span>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>

		<form method="post" action="admin-reports.php">
		<div class="card admin-crm-panel mb-4">
			<div class="card-header">
				<span class="iconify" data-icon="mdi:filter-variant"></span>
				<h5>Report filters</h5>
			</div>
			<div class="card-body">
				<div class="row g-3">
					<div class="col-md-4">
						<label class="admin-crm-label" for="council">Select council</label>
						<select name="council" id="council" class="form-select admin-crm-control">
							<option value="all">All Councils</option>
							<?php
								foreach($councils as $council){
									$group[$council['council_group']][] = $council;
								}
								foreach($group as $key=>$values){
									echo '<optgroup label="'.$key.'">';
									 foreach ($values as $value) 
									 {
										 echo '<option value="'.$value['id'].'"';
										 if($_POST['council'] == $value['id']){ echo 'selected'; }
										 echo '>'.$value['council_name'].'</option>';
									 }
									 echo '</optgroup>';
								}
							?>
						</select>
					</div>
					<div class="col-md-4">
						<label class="admin-crm-label" for="date-range">Date range</label>
						<div class="input-group admin-crm-date-range" id="date-range">
							<input type="date" aria-label="Date from" name="date-from" value="<?php if(!empty($_POST['date-from'])){ echo $_POST['date-from']; } ?>" class="form-control admin-crm-control">
							<span class="input-group-text admin-crm-date-range__divider">to</span>
							<input type="date" aria-label="Date to" name="date-to" class="form-control admin-crm-control" value="<?php if(!empty($_POST['date-to'])){ echo $_POST['date-to']; } ?>">
						</div>
					</div>
					<div class="col-md-4">
						<label class="admin-crm-label" for="date-range-predefined">Date range (predefined)</label>
						<select name="date-range-predefined" id="date-range-predefined" class="form-select admin-crm-control">
							<option value="" selected>-- Select an option --</option>
							<option value="1" <?php if($_POST['date-range-predefined'] == 1){ echo 'selected'; } ?>>Current month</option>
							<option value="2" <?php if($_POST['date-range-predefined'] == 2){ echo 'selected'; } ?>>Last Month</option>
							<option value="3" <?php if($_POST['date-range-predefined'] == 3){ echo 'selected'; } ?>>This Year</option>
						</select>
					</div>
					<div class="col-12">
						<div class="admin-crm-export-row">
							<label class="admin-crm-export-label" for="export_to_csv">
								<input type="checkbox" name="export_to_csv" id="export_to_csv" value="1" <?php isset($_POST['export_to_csv']) ? 'checked' : ''; ?>>
								<span>Export to CSV</span>
							</label>
						</div>
					</div>
				</div>
				<div class="admin-crm-submit-wrap">
					<button type="submit" class="btn btn-crm-primary">
						<span class="iconify" data-icon="mdi:file-chart-outline"></span>
						Generate Report
					</button>
				</div>
			</div>
		</div>

		<div class="card admin-crm-panel mb-4">
			<div class="card-header">
				<span class="iconify" data-icon="mdi:table-large"></span>
				<h5>Report results</h5>
			</div>
			<div class="card-body">
				<div class="admin-crm-table-wrap">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover mb-0">
							<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
									<th>Email Address</th>
									<th>Contact Number</th>
									<th>Council</th>
									<th>Signed Up</th>
									<th>Reason For Move</th>
									<th>Preferred Area</th>
									<th>NI Number</th>
									<th>D.O.B</th>
									<th>Other Information</th>
									<th>Housing Officer</th>
								</tr>
							</thead>
							<tbody>
						<?php 
						if($report){
							foreach($report as $user){
						?>
							<tr>
								<td><?= $user['id']; ?></td>
								<td><?= $user['name']; ?></td>
								<td><?= $user['email_address']; ?></td>
								<td><?= $user['contact_number']; ?></td>
								<td><?= $user['council_name']; ?></td>
								<td><?= date('d/m/Y', strtotime($user['created'])); ?></td>
								<td><?= $user['reason_for_move']; ?></td>
								<td><?= $user['preferred_area']; ?></td>
								<td><?= strtoupper($user['ni_number']); ?></td>
								<td><?= date('d/m/Y', strtotime($user['date_of_birth'])); ?></td>
								<td><?= $user['other_information']; ?></td>
								<td><?= $user['housing_officer']; ?></td>
							</tr>
						<?php
							}
						}
						?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		</form>
		</div>
<?php include_once("views/footer.php"); ?>
