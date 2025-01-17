<?php
	// Initialize variables
	$id = null;
	$fila = "";
	$category_code = null;
	$datacenter_id = null;
	$ssl_id = null;
	$db_id = null;
	$commitment_id = null;
	$request_id = null;
	$isDomainIncluded = 0;
	$domain = "";
	$isEmailIncluded = 0;
	$isDBIncluded = 0;
	$dbms_id = null;
	$memory_id = null;
	$persistency_id = null;
	$module_id = null;
	$cdn_id = null;
	$encontrado = true;
	$encontrado = false;

	// Include token update configuration
	include_once('api/config/actualizar_token.php');

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
		$stmt = $dbb->prepare("SELECT category_code, state, datacenter_id, ssl_id,
								commitment_id, storageSpace, bandwidthAllocation, maxConcurrentUsers, 
								maxWebsites, isDomainIncluded, d.name, FK_modules, FK_CDN, isEmailIncluded,
								rq.request_id, FK_DBMS, FK_memory, FK_persistency FROM saas_web_hosting swh 
								JOIN request rq ON swh.request_id = rq.request_id 
								LEFT JOIN wh_domain d ON swh.id = d.FK_webhosting
								LEFT JOIN wh_web_hosting_x_cdn whc ON whc.FK_webhosting = swh.id
								LEFT JOIN wh_web_hosting_x_modules whm ON whm.FK_webhosting = swh.id
								LEFT JOIN wh_db db ON db.wh_id = swh.id
								WHERE swh.id = ?");
		$stmt->bind_param('s', $id);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows > 0) {
			// Fetch the single result
			$fila = $result->fetch_assoc();
			$category_code = $fila['category_code'];
			$state = $fila['state'];
			$datacenter_id = $fila['datacenter_id'];
			$ssl_id = $fila['ssl_id'];
			$commitment_id = $fila['commitment_id'];
			$storageSpace = $fila['storageSpace'];
			$bandwidthAllocation = $fila['bandwidthAllocation'];
			$maxConcurrentUsers = $fila['maxConcurrentUsers'];
			$maxWebsites = $fila['maxWebsites'];
			$isDomainIncluded = $fila['isDomainIncluded'];
			$domain = $fila['name'];
			$module_id = $fila['FK_modules'];
			$cdn_id = $fila['FK_CDN'];
			$isEmailIncluded = $fila['isEmailIncluded'];
			$request_id = $fila['request_id'];
			$isDBIncluded = $fila['FK_DBMS'] != null ? 1 : 0;
			$dbms_id = $fila['FK_DBMS'];
			$memory_id = $fila['FK_memory'];
			$persistency_id = $fila['FK_persistency'];
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
					$stmt_category = $dbb->prepare('SELECT code, name, description, price FROM category WHERE name LIKE \'Web%\' ORDER BY price');
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
								Please, select a Category.
							</div>
						</div>
					</div>

					<!-- Datacenter Selection -->
					<?php 
					$stmt_datacenter = $dbb->prepare('SELECT id, name, location FROM wh_datacenter');
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
						$stmt_ssl = $dbb->prepare('SELECT id, provider, validationLevel, price FROM wh_ssl');
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
								   value="<?php echo htmlspecialchars($storageSpace ?? ''); ?>" min="1" max="100" required>
						</div>
						<div class="col-3">
							<label for="bandwidthAllocation" class="form-label">Bandwidth (GB/month)</label>
							<input type="number" class="form-control" id="bandwidthAllocation" name="bandwidthAllocation" 
								   value="<?php echo htmlspecialchars($bandwidthAllocation ?? ''); ?>" min="10" max="10000" required>
						</div>
						<div class="col-3">
							<label for="maxConcurrentUsers" class="form-label">Max Concurrent Users</label>
							<input type="number" class="form-control" id="maxConcurrentUsers" name="maxConcurrentUsers" 
								   value="<?php echo htmlspecialchars($maxConcurrentUsers ?? ''); ?>" min="1" max="100000" required>
						</div>
						<div class="col-3">
							<label for="maxWebsites" class="form-label">Max Websites</label>
							<input type="number" class="form-control" id="maxWebsites" name="maxWebsites" 
								   value="<?php echo htmlspecialchars($maxWebsites ?? ''); ?>" min="1" max="10" required>
						</div>
					</div>

					<!-- Additional Options -->
					<div class="row mb-3">
						<div class="col-4">
							<label class="form-label" for="isDomainIncluded">Include a domain</label>
							<div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
								<input class="custom-control-input" type="checkbox" id="isDomainIncluded" name="isDomainIncluded" 
									   <?php echo ($isDomainIncluded ? 'checked' : ''); ?> value="1">
								<label class="custom-control-label" for="isDomainIncluded">Yes</label>
							</div>
							<label class="form-label mb-0" for="isDomainIncluded">Yes</label>
						</div>
						<div class="col-4">
							<label class="form-label" for="isEmailIncluded">Include Email Services</label>
							<div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
								<input class="custom-control-input" type="checkbox" id="isEmailIncluded" name="isEmailIncluded" 
									   <?php echo ($isEmailIncluded ? 'checked' : ''); ?> value="1">
								<label class="custom-control-label" for="isEmailIncluded">Yes</label>
							</div>
							<label class="form-label mb-0" for="isEmailIncluded">Yes</label>
						</div>
						<div class="col-4">
							<label class="form-label" for="isDBIncluded">Include Database</label>
							<div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
								<input class="custom-control-input" type="checkbox" id="isDBIncluded" name="isDBIncluded" 
									   <?php echo ($isDBIncluded ? 'checked' : ''); ?> value="1">
								<label class="custom-control-label" for="isDBIncluded">Yes</label>
							</div>
							<label class="form-label mb-0" for="isDBIncluded">Yes</label>
						</div>
					</div>

					<!-- Domain -->
					<div class="row mb-3">
						<div id="domain-container" class="col-12">
							<label for="domain" class="form-label">Wanted domain</label>
							<input type="text" class="form-control" value="<?php echo htmlspecialchars($domain ?? ''); ?>" id="domain" name="domain">
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
					</div>

					<!-- Optional Database Selection -->
					<?php 
					$stmt_dbms = $dbb->prepare('SELECT id, name, version FROM wh_db_dbms');
					$stmt_dbms->execute();
					$result_dbms = $stmt_dbms->get_result();

					$stmt_memory = $dbb->prepare('SELECT id, capacity, type, speed FROM wh_db_memory');
					$stmt_memory->execute();
					$result_memory = $stmt_memory->get_result();

					$stmt_persistency = $dbb->prepare('SELECT id, type FROM wh_db_persistency');
					$stmt_persistency->execute();
					$result_persistency = $stmt_persistency->get_result();
					?>
					<div class="row mb-3">
						<div id="dbms-container" class="col-4">
							<label for="dbms" class="form-label">Wanted DBMS</label>
							<select class="form-control custom-select" id="dbms" name="dbms">
								<option value="" selected>Select a DBMS</option>
								<?php while ($dbms = $result_dbms->fetch_assoc()): ?>
									<option <?php if ($dbms_id == $dbms['id']){ ?>selected<?php }; ?> 
										value="<?php echo htmlspecialchars($dbms['id']); ?>">
										<?php 
										echo htmlspecialchars($dbms['name']); 
										echo " - ";
										echo htmlspecialchars($dbms['version']); 
										?>
									</option>
								<?php endwhile; ?>
							</select>
						</div>
						<div id="memory-container" class="col-4">
							<label for="memory" class="form-label">Wanted storage</label>
							<select class="form-control custom-select" id="memory" name="memory">
								<option value="" selected>Select a storage option</option>
								<?php while ($memory = $result_memory->fetch_assoc()): ?>
									<option <?php if ($memory_id == $memory['id']){ ?>selected<?php }; ?> 
										value="<?php echo htmlspecialchars($memory['id']); ?>">
										<?php 
										echo htmlspecialchars($memory['capacity']); 
										echo " - ";
										echo htmlspecialchars($memory['type']);
										echo " (";
										echo htmlspecialchars($memory['speed']); 
										echo "MB/s)";
										?>
									</option>
								<?php endwhile; ?>
							</select>
						</div>
						<div id="persistency-container" class="col-4">
							<label for="persistency" class="form-label">Wanted persistency level</label>
							<select class="form-control custom-select" id="persistency" name="persistency">
								<option value="" selected>Select a persistency option</option>
								<?php while ($persistency = $result_persistency->fetch_assoc()): ?>
									<option <?php if ($persistency_id == $persistency['id']){ ?>selected<?php }; ?> 
										value="<?php echo htmlspecialchars($persistency['id']); ?>">
										<?php 
										echo htmlspecialchars($persistency['type']); 
										?>
									</option>
								<?php endwhile; ?>
							</select>
						</div>
						<script>
							// Selection of elements
							const checkboxDB = document.getElementById('isDBIncluded');
							const dbms = document.getElementById('dbms');
							const dbmsContainer = document.getElementById('dbms-container');

							const memory = document.getElementById('memory');
							const memoryContainer = document.getElementById('memory-container');

							const persistency = document.getElementById('persistency');
							const persistencyContainer = document.getElementById('persistency-container');

							// Update required
							function toggleRequiredDB() {
								if (checkboxDB.checked) {
									dbmsContainer.style.display = 'block';
									dbms.setAttribute('required', 'required'); // Make required

									memoryContainer.style.display = 'block';
									memory.setAttribute('required', 'required'); // Make required

									persistencyContainer.style.display = 'block';
									persistency.setAttribute('required', 'required'); // Make required
								} else {
									dbmsContainer.style.display = 'none';
									dbms.removeAttribute('required'); // Remove required

									memoryContainer.style.display = 'none';
									memory.removeAttribute('required'); // Remove required

									persistencyContainer.style.display = 'none';
									persistency.removeAttribute('required'); // Remove required
								}
							}

							// Add event listener
							checkboxDB.addEventListener('change', toggleRequiredDB);

							toggleRequiredDB();
						</script>
					</div>

					<!-- Modules Selection -->
					<?php 
					$stmt_modules = $dbb->prepare('SELECT id, name, version, description FROM wh_modules');
					$stmt_modules->execute();
					$result_modules = $stmt_modules->get_result();
					?>
					<div class="row mb-3">
						<div class="col-6">
							<label for="module" class="form-label">Modules (optional)</label>
							<select class="form-control custom-select" id="module" name="module">
								<option value="" disabled selected>Select a module (optional)</option>
								<?php while ($module = $result_modules->fetch_assoc()): ?>
									<option <?php if ($module_id == $module['id']){ ?>selected<?php }; ?> 
										value="<?php echo htmlspecialchars($module['id']); ?>">
										<?php 
										echo htmlspecialchars($module['name']); 
										echo " ";
										echo htmlspecialchars($module['version']);
										echo " - ";
										echo htmlspecialchars($module['description']);
										?>
									</option>
								<?php endwhile; ?>
							</select>
							<div class="invalid-feedback">
								Please, select a Module.
							</div>
						</div>

						<!-- CDN Selection -->
						<?php 
						$stmt_cdn = $dbb->prepare('SELECT id, name, endpoint FROM wh_cdn');
						$stmt_cdn->execute();
						$result_cdn = $stmt_cdn->get_result();
						?>           
						<div class="col-6">
							<label for="cdn" class="form-label">Content Delivery Network (optional)</label>
							<select class="form-control custom-select" id="cdn" name="cdn">
								<option value="" disabled selected>Select a CDN (optional)</option>
								<?php while ($cdn = $result_cdn->fetch_assoc()): ?>
									<option <?php if ($cdn_id == $cdn['id']){ ?>selected<?php }; ?> 
										value="<?php echo htmlspecialchars($cdn['id']); ?>">
										<?php 
										echo htmlspecialchars($cdn['name']); 
										echo " - ";
										echo htmlspecialchars($cdn['endpoint']);
										?>
									</option>
								<?php endwhile; ?>
							</select>
							<div class="invalid-feedback">
								Please, select an CDN.
							</div>
						</div> 
					</div>

					<!-- Commitment Period Selection -->
					<?php 
					$stmt_commitment = $dbb->prepare('SELECT id, description, discount FROM commitment_period');
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

					<?php if ($es_admin==1 && $encontrado==true){      ?>
						<input type="hidden" name="request_id" value="<?php echo $request_id ?>">
						<div class="card border-left-3 border-left-primary">
							<div class="card-body">


								<div class="col-6">
									<label for="state" class="form-label">State</label>
									<select id="custom-select" class="form-control custom-select" id="state" name="state" required>
										<option value="" disabled selected>Select a State</option>
										<option <?php if ($state=="0"){?>selected<?php };?> value="0">OPEN</option>
										<option <?php if ($state=="1"){?>selected<?php };?> value="1">ON PROGRESS</option>
										<option <?php if ($state=="2"){?>selected<?php };?> value="2">CLOSED</option>
							
									</select>
									<div class="invalid-feedback">
										Please, select a state.
									</div>
								</div>

							</div>
						</div>
					<?php } ?>
					
					
					<!-- Form submission button -->
					<?php if ($encontrado) { ?>
						<button type="submit" class="btn btn-primary">Modify</button>
					<?php } else { ?>
						<button type="submit" class="btn btn-primary">Register</button>
					<?php } ?>
				</form>
			</div>
		</div>

		<script>
            function pulsar_delete(id) {
                Swal.fire({
                title: "Are you sure you want to delete the record?",
                text: "Is not possible to recover it",
                icon: "warning",
                role: "alertdialog",
                showCancelButton: true,
                confirmButtonText: "Yes, Delete!",
                cancelButtonText: "No, Cancel!",
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                }).then((result) => {
                    if (result.isConfirmed) {
                        /*// Prepare data to send
                        const formData = new FormData();
                        formData.append('id', id);

                        // Send the fetch request
                        fetch('api/actions/app_delete_saas_dbms.php', {
                            method: 'POST',
                            body: formData
                        });
                        location.reload(); // Reload the page*/
                        // Prepare data to send
                        var parametros = { "id": id };
                        $.ajax({
                            data: parametros,
                            type: "POST",
                            url: "api/actions/app_delete_saas_requests.php",
                            success: function(data) {
                                
                                data = data.trim();
                                if (data === 'OK') {
                                    Swal.fire({
                                        title: "Deleted",
                                        text: "The record has been successfully deleted!",
                                        icon: "success",
                                        confirmButtonText: "OK",
                                        confirmButtonColor: "#3085d6"
                                    }).then(() => {
                                        location.reload(); // Reload the page
                                    });
                                } else {
                                    Swal.fire("Error", "The record has NOT been successfully deleted.", "error");
                                }
                            }
                        });
                    }
                });
            }

            function pulsar_modificar(id) {
                window.location.href = `index.php?opcion=saas_requests&id=${id}&modificar=1`;
            }
        </script>

		<!-- Requests Table -->
		<div class="card table-responsive" data-toggle="lists" 
			data-lists-values='[
				"js-lists-values-date", 
				"js-lists-values-description",
				"js-lists-values-status",
				"js-lists-values-user"
			]' 
			data-lists-sort-by="js-lists-values-date"
			data-lists-sort-asc="true">
			<table class="table mb-0">
				<thead class="thead-light">
					<tr>							
						<th><a href="javascript:void(0)" class="sort" data-sort="js-lists-values-date">Date</a></th>
						<th><a href="javascript:void(0)" class="sort" data-sort="js-lists-values-description">Description</a></th>
						<th><a href="javascript:void(0)" class="sort" data-sort="js-lists-values-status">Status</a></th>
						<th><a href="javascript:void(0)" class="sort" data-sort="js-lists-values-user">User</a></th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody class="list">
					<?php while ($row = $result->fetch_assoc()) { ?>
						<tr>
							<td>
								<div class="d-flex align-items-center">
									<a href="#" class="text-body small">
										#<span class="js-lists-values-date"><?php echo htmlspecialchars($row["date"]); ?></span>
									</a>
								</div>
							</td>
							<td>
								<div class="d-flex align-items-center">
									<span class="js-lists-values-description"><?php echo htmlspecialchars($row["name"]); ?></span>
								</div>
							</td>
							<td class="text-center">
								<div class="d-flex align-items-center">
									<?php if ($row["state"]=='0'){ 
										$est="OPEN";?>
									<i class="material-icons text-danger md-18 mr-2">lens</i>
									<?php }?> 
									<?php if ($row["state"]=='1'){ 
										$est="ON PROGRESS";?>
									<i class="material-icons text-primary md-18 mr-2">lens</i>
									<?php }?>    
									<?php if ($row["state"]=='2'){
										$est="CLOSED";?>
									<i class="material-icons text-success md-18 mr-2">lens</i>
									<?php }?>                                                                                                   
									<small class="text-uppercase js-lists-values-status"><?php echo $est; ?></small>
								</div>
							</td>
							<td>
								<div class="d-flex align-items-center">
									<span class="js-lists-values-user"><?php echo htmlspecialchars($row["email"]); ?></span>
								</div>
							</td>
							<td class="text-right">
								<div class="dropdown">
									<div class="btn-group dropleft ">
										<button id="dropdown1" onclick="pulsar_delete('<?php echo $row['request_id']; ?>');" type="button btn-primary"  data-toggle="tooltip" data-placement="left" class="btn btn-flush " title="Delete">
											<i class="material-icons">delete</i>
										</button>
										<button id="dropdown1" onclick="pulsar_modificar('<?php echo $row['saasid']; ?>');" type="button btn-primary"  data-toggle="tooltip" data-placement="left" class="btn btn-flush " title="Modify">
											<i class="material-icons">edit</i>
										</button>
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