<?php

// Initialize variables for the table attributes
$id = "";
$name = "";
$location = "";
$networkProvider = "";

$found = false;
if ($es_admin == 1) {

    include_once "api/config/actualizar_token.php";

    // Retrieve the ID from the request, if available
    if (!empty($_GET["id"]) || isset($_GET["id"])) {
        $id = $_GET["id"];
    }

    $id = StringInputCleaner($id);

    // Check if modification is requested
    if (!empty($_GET["modificar"]) || isset($_GET["modificar"])) {
        $modify = $_GET["modificar"];
    }

    if (!empty($modify) and !empty($id)) {
        // Fetch the record for modification
        $stmt = $dbb->prepare("SELECT * FROM wh_datacenter WHERE id = ?");
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $name = $row['name'];
            $location = $row['location'];
            $networkProvider = $row['networkProvider'];
            $found = true;
        }
    }

    // Fetch all records to display
    $stmt = $dbb->prepare('SELECT * FROM wh_datacenter');
    $dbb->set_charset("utf8");
    $stmt->execute();
    $result = $stmt->get_result();
?>

<div class="row m-0">
    <div class="col-lg container-fluid page__container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Data Centers Management</li>
        </ol>

        <h1 class="h2">Data Centers Management</h1>
        <ol class="breadcrumb">
			<li class="breadcrumb-item active"><a href="index.php?opcion=saas">SaaS</a></li>
		</ol>

        <?php if (!empty($_GET["error"])) { ?>
            <div class="alert alert-dismissible <?php echo ($_GET["error"] == 2) ? 'bg-primary' : 'bg-danger'; ?> text-white border-0 fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>ATTENTION - </strong> The action has <?php echo ($_GET["error"] == 2) ? 'been completed successfully.' : 'not been completed successfully.'; ?>
            </div>
        <?php } ?>

        <div class="card border-left-3 border-left-danger card-2by1">
            <div class="card-body">
                <form id="dbmsForm" action="api/actions/app_update_saas_datacenter.php" method="post" class="needs-validation">
                    <div class="row mb-3">
                        <input type="hidden" name="id" value="<?php echo $id ?>">
                        <input type="hidden" name="modificar" value="<?php echo $found ? 1 : 0; ?>">

                        <div class="col-4">
                            <label for="name" class="form-label">Name</label>
                            <input maxlength="256" type="text" class="form-control" value="<?php echo $name ?>" id="name" name="name" required>
                            <div class="invalid-feedback">
                                Please, insert the name.
                            </div>
                        </div>
                        <div class="col-4">
                            <label for="location" class="form-label">Location</label>
                            <input maxlength="256" type="text" class="form-control" value="<?php echo $location ?>" id="location" name="location" required>
                            <div class="invalid-feedback">
                                Please, insert the location.
                            </div>
                        </div>
                        <div class="col-4">
                            <label for="networkProvider" class="form-label">Network Provider</label>
                            <input maxlength="256" type="text" class="form-control" value="<?php echo $networkProvider ?>" id="networkProvider" name="networkProvider" required>
                            <div class="invalid-feedback">
                                Please, insert the network provider.
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <?php echo $found ? 'Modify' : 'New'; ?>
                    </button>
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
                        // Prepare data to send
                        var parametros = { "id": id };
                        $.ajax({
                            data: parametros,
                            type: "POST",
                            url: "api/actions/app_delete_saas_datacenter.php",
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
                window.location.href = `index.php?opcion=saas_datacenter&id=${id}&modificar=1`;
            }
        </script>
  
        <div class="card table-responsive" data-toggle="lists" data-lists-values='["js-lists-values-name", "js-lists-values-location", "js-lists-values-networkProvider"]' data-lists-sort-by="js-lists-values-name" data-lists-sort-asc="true">
            <table class="table mb-0">
                <thead class="thead-light">
                    <tr>
                        <th><a href="javascript:void(0)" class="sort" data-sort="js-lists-values-name">Name</a></th>
                        <th><a href="javascript:void(0)" class="sort" data-sort="js-lists-values-location">location</a></th>
                        <th><a href="javascript:void(0)" class="sort" data-sort="js-lists-values-networkProvider">License Type</a></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="list">
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><span class="js-lists-values-name"><?php echo $row["name"]; ?></span></td>
                            <td><span class="js-lists-values-location"><?php echo $row["location"]; ?></span></td>
                            <td><span class="js-lists-values-networkProvider"><?php echo $row["networkProvider"]; ?></span></td>
                            <td>
                                <div class="btn-group dropleft ">
                                    <button id="dropdown1" onclick="pulsar_delete('<?php echo $row['id']; ?>');" type="button btn-primary"  data-toggle="tooltip" data-placement="left" class="btn btn-flush " title="Delete">
                                        <i class="material-icons">delete</i>
                                    </button>
                                    <button id="dropdown1" onclick="pulsar_modificar('<?php echo $row['id']; ?>');" type="button btn-primary"  data-toggle="tooltip" data-placement="left" class="btn btn-flush " title="Modify">
                                        <i class="material-icons">edit</i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php } ?>
