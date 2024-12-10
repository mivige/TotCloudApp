<?php
	// Initialize variables
	$id = "";
	$fila = "";
	$category_code = "";
	$datacenter_id = "";
	$ssl_id = "";
	$db_id = "";
	$commitment_id = "";
	$request_id = "";
	$isDomainIncluded = false;
	$isEmailIncluded = false;
	$encontrado = true;
	$encontrado = false;

	// Include token update configuration
	include_once('../../config/actualizar_token.php');

	// Check and sanitize ID from GET parameter
	if (!empty($_GET["id"]) || isset($_GET["id"])) {
		$id = $_GET["id"];
	} 
	$id = StringInputCleaner($id);

	// Check and handle modification parameter
	if (!empty($_GET["modificar"]) || isset($_GET["modificar"])) {
		$modificar = $_GET["modificar"];
	} 

	// If modification and ID are present, fetch web hosting details
	if (!empty($modificar) and !empty($id)) {
		$stmt = $dbb->prepare("SELECT * FROM saas_web_hosting swh 
								JOIN request rq ON swh.request_id = rq.request_id 
								WHERE swh.id = ?");
		$stmt->bind_param('s', $id);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows > 0) {
			// Fetch the single result
			$fila = $result->fetch_assoc();
			$category_code = $fila['category_code'];
			$datacenter_id = $fila['datacenter_id'];
			$ssl_id = $fila['ssl_id'];
			$db_id = $fila['db_id'];
			$commitment_id = $fila['commitment_id'];
			$storageSpace = $fila['storageSpace'];
			$bandwidthAllocation = $fila['bandwidthAllocation'];
			$maxConcurrentUsers = $fila['maxConcurrentUsers'];
			$maxWebsites = $fila['maxWebsites'];
			$isSSLIncluded = $fila['isSSLIncluded'];
			$isDomainIncluded = $fila['isDomainIncluded'];
			$isEmailIncluded = $fila['isEmailIncluded'];
			$request_id = $fila['request_id'];
			$encontrado = true;
		}
	} 

	// Fetch requests based on user role
	if ($es_admin == 1) {
		// Admin sees all requests
		$stmt = $dbb->prepare('
			SELECT 
				pr.request_id, 
				pr.date, 
				pr.state, 
				pr.user_id, 
				us.email, 
				swh.id as saasid, 
				cat.name 
			FROM request pr 
			INNER JOIN saas_web_hosting swh ON pr.request_id = swh.request_id 
			INNER JOIN category cat ON cat.code = swh.category_code 
			INNER JOIN user us ON us.id = pr.user_id
		');
		$dbb->set_charset("utf8");
		$stmt->execute();
		$result = $stmt->get_result();
	} else {
		// Regular user sees only their requests
		$stmt = $dbb->prepare('
			SELECT 
				pr.request_id, 
				pr.date, 
				pr.state, 
				pr.user_id, 
				us.email, 
				swh.id as saasid, 
				cat.name 
			FROM request pr 
			INNER JOIN saas_web_hosting swh ON pr.request_id = swh.request_id 
			INNER JOIN category cat ON cat.code = swh.category_code 
			INNER JOIN user us ON us.id = pr.user_id AND us.id = ?
		');
		
		$stmt->bind_param('s', $id_user);
		$dbb->set_charset("utf8");
		$stmt->execute();
		$result = $stmt->get_result();
	}
?>

<div class="row m-0">
	<div class="col-lg container-fluid page__container">
		<!-- Breadcrumb navigation -->
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="index.php">Home</a></li>
			<li class="breadcrumb-item active">SaaS Requests</li>
		</ol>

		<h1 class="h2">Web Hosting Requests List</h1>
		<ol class="breadcrumb">
			<li class="breadcrumb-item active"><a href="index.php?opcion=saas">SaaS</a></li>
		</ol>

		<!-- Error message handling -->
		<?php if (!empty($_GET["error"])) { ?>
			<?php if ($_GET["error"] == 1) { ?>
				<div class="alert alert-dismissible bg-danger text-white border-0 fade show" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>ATTENTION - </strong> The action has not been completed successfully.
				<?php } ?> 
				
				<?php if ($_GET["error"] == 2) { ?>
					<div class="alert alert-dismissible bg-primary text-white border-0 fade show" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<strong>ATTENTION - </strong> The action has been completed successfully.
					<?php } ?>
				</div>
		<?php } ?>
			
		<div class="card border-left-3 border-left-danger card-2by1">
			<div class="card-body">
				<h3 class="h3">
					WEB HOSTING 
					<span class="badge badge-pill badge-primary">Starting from 1 €/month!</span>  
				</h3>       
				
				<div class="card border-left-3 border-left-primary">
					<div class="card-body">
						High-performance web hosting solutions for your business!<br>
						Scalable, secure, and reliable hosting with multiple configuration options to meet your unique needs.
					</div>
				</div>
				
				<!-- Request Form -->
				<form id="hostingForm" action="api/actions/app_update_saas_requests.php" method="post" class="needs-validation">
					<input type="hidden" name="id" value="<?php echo $id ?>">
					<input type="hidden" name="id_user" value="<?php echo $id_user ?>">
					
					<!-- Modification flag -->
					<?php if ($encontrado) { ?>
						<input type="hidden" name="modificar" value="1">
					<?php } else { ?>
						<input type="hidden" name="modificar" value="0">
					<?php } ?>
					

					<!-- Category Selection -->
					<?php 
					$stmt_category = $dbb->prepare('SELECT * FROM category WHERE name LIKE \'Web%\' ORDER BY price');
					$stmt_category->execute();
					$result_category = $stmt_category->get_result();
					?>
					<div class="row mb-3">
						<div class="col-12">
							<label for="category" class="form-label">Category</label>
							<select class="form-control custom-select" id="category" name="category" required>
								<option value="" disabled selected>Select a Category</option>
								<?php while ($category = $result_category->fetch_assoc()): ?>
									<option <?php if ($category_code == $category['code']){ ?>selected<?php }; ?> 
										value="<?php echo htmlspecialchars($category['code']); ?>">
										<?php 
										echo htmlspecialchars($category['name']); 
										echo " - ";
										echo htmlspecialchars($category['description']);
										echo " - ";
										echo htmlspecialchars($category['price']);
										echo " €/year";
										?>
									</option>
								<?php endwhile; ?>
							</select>
							<div class="invalid-feedback">
								Please, select a Data Center.
							</div>
						</div>
					</div>

					<!-- Datacenter Selection -->
					<?php 
					$stmt_datacenter = $dbb->prepare('SELECT * FROM wh_datacenter');
					$stmt_datacenter->execute();
					$result_datacenter = $stmt_datacenter->get_result();
					?>
					<div class="row mb-3">
						<div class="col-6">
							<label for="datacenter" class="form-label">Data Center</label>
							<select class="form-control custom-select" id="datacenter" name="datacenter" required>
								<option value="" disabled selected>Select a Data Center</option>
								<?php while ($datacenter = $result_datacenter->fetch_assoc()): ?>
									<option <?php if ($datacenter_id == $datacenter['id']){ ?>selected<?php }; ?> 
										value="<?php echo htmlspecialchars($datacenter['id']); ?>">
										<?php 
										echo htmlspecialchars($datacenter['name']); 
										echo " - ";
										echo htmlspecialchars($datacenter['location']);
										?>
									</option>
								<?php endwhile; ?>
							</select>
							<div class="invalid-feedback">
								Please, select a Data Center.
							</div>
						</div>

						<!-- SSL Selection -->
						<?php 
						$stmt_ssl = $dbb->prepare('SELECT * FROM wh_ssl');
						$stmt_ssl->execute();
						$result_ssl = $stmt_ssl->get_result();
						?>           
						<div class="col-6">
							<label for="ssl" class="form-label">SSL Certificate</label>
							<select class="form-control custom-select" id="ssl" name="ssl">
								<option value="" disabled selected>Select SSL Certificate</option>
								<?php while ($ssl = $result_ssl->fetch_assoc()): ?>
									<option <?php if ($ssl_id == $ssl['id']){ ?>selected<?php }; ?> 
										value="<?php echo htmlspecialchars($ssl['id']); ?>">
										<?php 
										echo htmlspecialchars($ssl['provider']); 
										echo " - ";
										echo htmlspecialchars($ssl['validationLevel']);
										echo " - ";
										echo htmlspecialchars($ssl['price']);
										echo " €/year";
										?>
									</option>
								<?php endwhile; ?>
							</select>
							<div class="invalid-feedback">
								Please, select an SSL Certificate.
							</div>
						</div> 
					</div>

					<!-- Additional Web Hosting Details -->
					<div class="row mb-3">
						<div class="col-3">
							<label for="storageSpace" class="form-label">Storage Space (GB)</label>
							<input type="number" class="form-control" id="storageSpace" name="storageSpace" 
								   value="<?php echo htmlspecialchars($storageSpace ?? ''); ?>" required>
						</div>
						<div class="col-3">
							<label for="bandwidthAllocation" class="form-label">Bandwidth (GB/month)</label>
							<input type="number" class="form-control" id="bandwidthAllocation" name="bandwidthAllocation" 
								   value="<?php echo htmlspecialchars($bandwidthAllocation ?? ''); ?>" required>
						</div>
						<div class="col-3">
							<label for="maxConcurrentUsers" class="form-label">Max Concurrent Users</label>
							<input type="number" class="form-control" id="maxConcurrentUsers" name="maxConcurrentUsers" 
								   value="<?php echo htmlspecialchars($maxConcurrentUsers ?? ''); ?>" required>
						</div>
						<div class="col-3">
							<label for="maxWebsites" class="form-label">Max Websites</label>
							<input type="number" class="form-control" id="maxWebsites" name="maxWebsites" 
								   value="<?php echo htmlspecialchars($maxWebsites ?? ''); ?>" required>
						</div>
					</div>

					<!-- Additional Options -->
					<div class="row mb-3">
						<div class="col-4">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="isDomainIncluded" name="isDomainIncluded" 
									   <?php echo ($isDomainIncluded ? 'checked' : ''); ?>>
								<label class="form-check-label" for="isDomainIncluded">
									Include a domain
								</label>
							</div>
						</div>
						<div id="domain-container" class="col-6">
							<label for="domain" class="form-label">Wanted domain</label>
							<input type="text" class="form-control" id="domain" name="domain">
						</div>
						<script>
								// Selection of elements
								const checkbox = document.getElementById('isDomainIncluded');
								const textInput = document.getElementById('domain');
								const textInputContainer = document.getElementById('domain-container');

								// Update required
								function toggleRequired() {
									if (checkbox.checked) {
										textInputContainer.style.display = 'block';
										textInput.setAttribute('required', 'required'); // Make required
									} else {
										textInputContainer.style.display = 'none';
										textInput.removeAttribute('required'); // Remove required
									}
								}

								// Add event listener
								checkbox.addEventListener('change', toggleRequired);

								toggleRequired();
							</script>
						<div class="col-4">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="isEmailIncluded" name="isEmailIncluded" 
									   <?php echo ($isEmailIncluded ? 'checked' : ''); ?>>
								<label class="form-check-label" for="isEmailIncluded">
									Include Email Services
								</label>
							</div>
						</div>
					</div>

					<!-- Optional Database Selection -->
					<?php 
					$stmt_db = $dbb->prepare('SELECT * FROM wh_db');
					$stmt_db->execute();
					$result_db = $stmt_db->get_result();
					?>
					<div class="row mb-3">
						<div class="col-12">
							<label for="database" class="form-label">Optional Database</label>
							<select class="form-control custom-select" id="database" name="database">
								<option value="" selected>Select a Database (Optional)</option>
								<?php while ($db = $result_db->fetch_assoc()): ?>
									<option <?php if ($db_id == $db['id']){ ?>selected<?php }; ?> 
										value="<?php echo htmlspecialchars($db['id']); ?>">
										<?php 
										echo htmlspecialchars($db['name']); 
										echo " - ";
										echo htmlspecialchars($db['capacity']); 
										echo " GB";
										?>
									</option>
								<?php endwhile; ?>
							</select>
						</div>
					</div>

					<!-- Commitment Period Selection -->
					<?php 
					$stmt_commitment = $dbb->prepare('SELECT * FROM commitment_period');
					$stmt_commitment->execute();
					$result_commitment = $stmt_commitment->get_result();
					?>
					<div class="row mb-3">
						<div class="col-12">
							<label for="commitment" class="form-label">Commitment Period</label>
							<select class="form-control custom-select" id="commitment" name="commitment" required>
								<option value="" disabled selected>Select Commitment Period</option>
								<?php while ($commitment = $result_commitment->fetch_assoc()): ?>
									<option <?php if ($commitment_id == $commitment['id']){ ?>selected<?php }; ?> 
										value="<?php echo htmlspecialchars($commitment['id']); ?>">
										<?php 
										echo htmlspecialchars($commitment['description']); 
										echo " - ";
										echo htmlspecialchars($commitment['discount']); 
										echo "% Discount";
										?>
									</option>
								<?php endwhile; ?>
							</select>
							<div class="invalid-feedback">
								Please, select a Commitment Period.
							</div>
						</div>
					</div>
					
					<!-- Form submission button -->
					<?php if ($encontrado) { ?>
						<button type="submit" class="btn btn-primary">Modify</button>
					<?php } else { ?>
						<button type="submit" class="btn btn-primary">Register</button>
					<?php } ?>
				</form>
			</div>
		</div>

		<!-- Requests Table -->
		<div class="card table-responsive" data-toggle="lists" 
			data-lists-values='[
				"js-lists-values-document", 
				"js-lists-values-amount",
				"js-lists-values-status",
				"js-lists-values-date"
			]' 
			data-lists-sort-by="js-lists-values-document"
			data-lists-sort-asc="true">
			<table class="table mb-0">
				<thead class="thead-light">
					<tr>
						<th colspan="5">
							<a href="javascript:void(0)" class="sort" data-sort="js-lists-values-document">Date</a>
							<a href="javascript:void(0)" class="sort" data-sort="js-lists-values-amount">Description</a>
							<a href="javascript:void(0)" class="sort" data-sort="js-lists-values-status">Status</a>
							<a href="javascript:void(0)" class="sort" data-sort="js-lists-values-date">User</a>
						</th>
					</tr>
				</thead>
				<tbody class="list">
					<?php while ($row = $result->fetch_assoc()) { ?>
						<tr>
							<td>
								<div class="d-flex align-items-center">
									<small class="text-uppercase text-muted mr-2">DATE</small>
									<a href="#" class="text-body small">
										#<span class="js-lists-values-document"><?php echo htmlspecialchars($row["date"]); ?></span>
									</a>
								</div>
							</td>
							<td>
								<div class="d-flex align-items-center">
									<small class="text-uppercase text-muted mr-2">DESCRIPTION</small>
									<span class="js-lists-values-amount"><?php echo htmlspecialchars($row["name"]); ?></span>
								</div>
							</td>
							<td>
								<div class="d-flex align-items-center">
									<small class="text-uppercase text-muted mr-2">STATUS</small>
									<span class="js-lists-values-status badge 
										<?php echo ($row["state"] == 1 ? 'badge-success' : 'badge-danger'); ?>">
										<?php echo ($row["state"] == 1 ? 'Active' : 'Inactive'); ?>
									</span>
								</div>
							</td>
							<td>
								<div class="d-flex align-items-center">
									<small class="text-uppercase text-muted mr-2">USER</small>
									<span class="js-lists-values-date"><?php echo htmlspecialchars($row["email"]); ?></span>
								</div>
							</td>
							<td class="text-right">
								<div class="dropdown">
									<a href="#" class="dropdown-toggle text-muted" data-caret="false" data-toggle="dropdown">
										<i class="material-icons">more_vert</i>
									</a>
									<div class="dropdown-menu dropdown-menu-right">
										<a href="index.php?opcion=saas&id=<?php echo $row['saasid']; ?>&modificar=1" class="dropdown-item">
											<i class="material-icons">edit</i> Edit
										</a>
										<div class="dropdown-divider"></div>
										<a href="api/actions/app_delete_saas_request.php?id=<?php echo $row['saasid']; ?>" 
											class="dropdown-item text-danger" 
											onclick="return confirm('Are you sure you want to delete this request?');">
											<i class="material-icons">delete</i> Delete
										</a>
									</div>
								</div>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
// Optional: Add client-side form validation
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('hostingForm');
    
    form.addEventListener('submit', function(event) {
        // Basic validation
        var requiredFields = form.querySelectorAll('[required]');
        var isValid = true;
        
        requiredFields.forEach(function(field) {
            if (!field.value) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            event.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
});
</script>