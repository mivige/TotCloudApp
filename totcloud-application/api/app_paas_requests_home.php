
<?php

$id="";
$titulo="";
$descripcion="";
$encontrado=false;
$estado="";
$fecha_creacion="";




if(!empty($_GET["id"]) || isset($_GET["id"]))
{$id=$_GET["id"];} 

$id=StringInputCleaner($id);

if(!empty($_GET["modificar"]) || isset($_GET["modificar"]))
{$modificar=$_GET["modificar"];} 

if(!empty($modificar) and !empty($id)){

    $stmt = $dbb->prepare("SELECT * FROM paas_request WHERE id= ? ");
    $stmt->bind_param('s', $id); // 's' indica que el parámetro es una cadena
    $stmt->execute();
    $result = $stmt->get_result(); // Obtener el resultado de la ejecución
   
    if ($result->num_rows > 0) {
        // Obtener el único resultado
        $fila = $result->fetch_assoc();
        $titulo=$fila['titulo'];
        $descripcion=$fila['descripcion'];
        $estado=$fila['estado'];
        $encontrado=true;
        $fecha_creacion=$fila['fecha_creacion'];
        $fecha_creacion = explode(" ",$fecha_creacion)[0]; 
    }
} 

  $stmt = $dbb->prepare('select * from paas_request pr inner join paas_dedicated_server pds on pr.dedicated_server_code=pds.id inner join category cat on cat.code=pds.category_code inner join users us on us.id=pr.user_id');
  $dbb->set_charset("utf8");
  $stmt->execute();
  $result = $stmt->get_result();
  
?>

<div class="row m-0">
    <div class="col-lg container-fluid page__container">
            <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Paas Requests</li>
            </ol>

        <h1 class="h2">Requests List</h1>
        <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="index.php?opcion=paas">PaaS</a></li>

            </ol>


            <?php      if(!empty($_GET["error"])) {?>
        <?php      if($_GET["error"]==1) {?>
    <div class="alert alert-dismissible bg-danger text-white border-0 fade show" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  
  <strong>ATENTION - </strong> The action has not been completed successfully.
  <?php } ?> 

  <?php      if($_GET["error"]==2) {?>
    <div class="alert alert-dismissible bg-primary text-white border-0 fade show" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  
  <strong>ATENTION - </strong> The action has  been completed successfully.
  <?php } ?>
</div>
<?php } ?>



        <div class="card border-left-3 border-left-danger card-2by1">
        <div class="card-body">
        <h3 class="h3">DEDICATED SERVER <span class="badge badge-pill badge-primary">Monthly Price: 200 €</span>  </h3>       
        



        <div class="card border-left-3 border-left-primary">
  <div class="card-body">
    Bare Metal servers at the best price-performance ratio!<br>
  With our dedicated servers, you get all the advantages and performance of a dedicated server, while staying within your budget. Ideal for projects that require high performance without compromising on cost.
  </div>
</div>
                                    
        <form id="incidenciaForm" action="api/actions/app_update_requests.php" method="post" class="needs-validation" >
            <div class="row mb-3">
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="hidden" name="id_user" value="<?php echo $id_user ?>">
           
            <?php if ($encontrado) {?>
                <input type="hidden" name="modificar" value=1>
            <?php } else {?>
                <input type="hidden" name="modificar" value=0>
                <?php }?>
                

                <?php $stmt_tmp = $dbb->prepare('select * from paas_processor pp inner join currencytype ct  on pp.currency_type = ct.currency_code;');
            $stmt_tmp->execute();
            $result_tmp = $stmt_tmp->get_result();
            ?>

            <div class="col-6">
                <label for="processor" class="form-label">Processor</label>
                <select id="custom-select" class="form-control custom-select" id="processor" name="processor" required>
                    <option value="" disabled selected>Select a Processor</option>
                    <?php while ($processor = $result_tmp->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($processor['code']); ?>"><?php echo htmlspecialchars($processor['description']); echo" +";echo htmlspecialchars($processor['price']);echo" ";echo htmlspecialchars($processor['currency_name'])?></option>
                    <?php endwhile; ?>
                </select>
                <div class="invalid-feedback">
                    Please, select a Processor.
                </div>
            </div>

            <?php $stmt_tmp = $dbb->prepare('select * from paas_memory pm inner join currencytype ct  on pm.currency_type = ct.currency_code;');
            $stmt_tmp->execute();
            $result_tmp = $stmt_tmp->get_result();
            ?>           
            <div class="col-6">
                <label for="memory" class="form-label">Memory</label>
                <select id="custom-select" class="form-control custom-select" id="memory" name="memory" required>
                    <option value="" disabled selected>Select a Memory</option>
                    <?php while ($memory = $result_tmp->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($memory['code']); ?>"><?php echo htmlspecialchars($memory['description']); echo" +";echo htmlspecialchars($memory['price']);echo" ";echo htmlspecialchars($memory['currency_name']) ?></option>
                    <?php endwhile; ?>
                </select>
                <div class="invalid-feedback">
                    Please, select a memory.
                </div>
            </div> 


            </div>


            <div class="row mb-3">
            <?php $stmt_tmp = $dbb->prepare('select * from paas_private_bandwidth pp inner join currencytype ct  on pp.currency_type = ct.currency_code;');
            $stmt_tmp->execute();
            $result_tmp = $stmt_tmp->get_result();
            ?>

            <div class="col-6">
                <label for="private_bandwith" class="form-label">Private Bandwith</label>
                <select id="custom-select" class="form-control custom-select" id="private_bandwith" name="private_bandwith" required>
                    <option value="" disabled selected>Select a Private Bandwith</option>
                    <?php while ($private_bandwith = $result_tmp->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($private_bandwith['code']); ?>"><?php echo htmlspecialchars($private_bandwith['description']); ; echo" +";echo htmlspecialchars($private_bandwith['price']);echo" ";echo htmlspecialchars($private_bandwith['currency_name']) ?></option>
                    <?php endwhile; ?>
                </select>
                <div class="invalid-feedback">
                    Please, select a Private Bandwidth.
                </div>
            </div>

            <?php $stmt_tmp = $dbb->prepare('select * from paas_public_bandwidth pp inner join currencytype ct  on pp.currency_type = ct.currency_code;');
            $stmt_tmp->execute();
            $result_tmp = $stmt_tmp->get_result();
            ?>           
            <div class="col-6">
                <label for="public_bandwith" class="form-label">Public Bandwith</label>
                <select id="custom-select" class="form-control custom-select" id="public_bandwith" name="public_bandwith" required>
                    <option value="" disabled selected>Select a Public Bandwith</option>
                    <?php while ($public_bandwith = $result_tmp->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($public_bandwith['code']); ?>"><?php echo htmlspecialchars($public_bandwith['description']); ; echo" +";echo htmlspecialchars($public_bandwith['price']);echo" ";echo htmlspecialchars($public_bandwith['currency_name']) ?></option>
                    <?php endwhile; ?>
                </select>
                <div class="invalid-feedback">
                    Please, select a memory.
                </div>
            </div> 
            </div>



            <div class="row mb-3">
            <?php $stmt_tmp = $dbb->prepare('select * from paas_storage ps inner join currencytype ct  on ps.currency_type = ct.currency_code;');
            $stmt_tmp->execute();
            $result_tmp = $stmt_tmp->get_result();
            ?>

            <div class="col-6">
                <label for="storage" class="form-label">Storage</label>
                <select id="custom-select" class="form-control custom-select" id="storage" name="storage" required>
                    <option value="" disabled selected>Select a Storage</option>
                    <?php while ($storage = $result_tmp->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($storage['code']); ?>"><?php echo htmlspecialchars($storage['description']); echo" +";echo htmlspecialchars($storage['price']);echo" ";echo htmlspecialchars($storage['currency_name']) ?></option>
                    <?php endwhile; ?>
                </select>
                <div class="invalid-feedback">
                    Please, select a Storage.
                </div>
            </div>

            <?php $stmt_tmp = $dbb->prepare('select * from paas_os po inner join currencytype ct  on po.currency_type = ct.currency_code;');
            $stmt_tmp->execute();
            $result_tmp = $stmt_tmp->get_result();
            ?>           
            <div class="col-6">
                <label for="os" class="form-label">OS SYSTEM</label>
                <select id="custom-select" class="form-control custom-select" id="os" name="os" required>
                    <option value="" disabled selected>Select a Operating Systemy</option>
                    <?php while ($os = $result_tmp->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($os['code']); ?>"><?php echo htmlspecialchars($os['name']);  echo" +";echo htmlspecialchars($os['price']);echo" ";echo htmlspecialchars($os['currency_name'])?></option>
                    <?php endwhile; ?>
                </select>
                <div class="invalid-feedback">
                    Please, select a OS.
                </div>
            </div>         

            </div>


            <div class="row mb-3">
            <?php $stmt_tmp = $dbb->prepare('SELECT * FROM paas_datacenterregion');
            $stmt_tmp->execute();
            $result_tmp = $stmt_tmp->get_result();
            ?>

            <div class="col-6">
                <label for="datacenterregion" class="form-label">Data Center Region</label>
                <select id="custom-select" class="form-control custom-select" id="datacenterregion" name="datacenterregion" required>
                    <option value="" disabled selected>Select a Data Center Region</option>
                    <?php while ($datacenterregion = $result_tmp->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($datacenterregion['code']); ?>"><?php echo htmlspecialchars($datacenterregion['description']); ?></option>
                    <?php endwhile; ?>
                </select>
                <div class="invalid-feedback">
                    Please, select a Data Center Region.
                </div>
            </div>

            <?php $stmt_tmp = $dbb->prepare('SELECT * FROM paas_commitment_period');
            $stmt_tmp->execute();
            $result_tmp = $stmt_tmp->get_result();
            ?>           
            <div class="col-6">
                <label for="commitment_period" class="form-label">Commitment Period</label>
                <select id="custom-select" class="form-control custom-select" id="commitment_period" name="commitment_period" required>
                    <option value="" disabled selected>Select a Commitment Period</option>
                    <?php while ($commitment_period = $result_tmp->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($commitment_period['code']); ?>"><?php echo htmlspecialchars($commitment_period['description']); ?></option>
                    <?php endwhile; ?>
                </select>
                <div class="invalid-feedback">
                    Please, select a Commitment Period.
                </div>
            </div>   
            </div>  
            
            


            <?php if ($encontrado) {?>
                <button type="submit" class="btn btn-primary">Modify</button>
                <?php } else {?>
                    <button type="submit" class="btn btn-primary">Register</button>
                <?php }?>
            
        </form>
                                      
                                 
                                </div>
                            </div>

                            



                            <div class="card table-responsive" data-toggle="lists" data-lists-values='[
    "js-lists-values-document", 
    "js-lists-values-amount",
    "js-lists-values-status",
    "js-lists-values-date"
  ]' data-lists-sort-by="js-lists-values-document" data-lists-sort-asc="true">
                                <table class="table mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th colspan="5">
                                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-document">Fecha</a>
                                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-amount">Descripción</a>
                                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-status">Estado</a>
                                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-date">Usuario</a>
                                                
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                    <?php while ($row = $result->fetch_assoc()) {?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <small class="text-uppercase text-muted mr-2">DATE</small>
                                                    <a href="fixed-student-invoice.html" class="text-body small">#<span class="js-lists-values-document"><?php echo $row["date"]; ?></span></a>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center">
                                                    <small class="text-uppercase text-muted mr-2"></small>
                                                    <small class="text-uppercase text-muted mr-2">PRODUCT</small>
                                                    <small class="text-uppercase"><span class="js-lists-values-amount"><?php echo $row["name"]; ?></span></small>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center">
                                                    <small class="text-uppercase text-muted mr-2">Status</small>
                                                    <?php if ($row["estado"]=='0'){ 
                                                        $est="OPEN";?>
                                                    <i class="material-icons text-danger md-18 mr-2">lens</i>
                                                    <?php }?>
                                                    <?php if ($row["estado"]=='1'){ 
                                                        $est="ON PROGRESS";?>
                                                    <i class="material-icons text-primary md-18 mr-2">lens</i>
                                                    <?php }?>    
                                                    <?php if ($row["estado"]=='2'){
                                                        $est="CLOSED";?>
                                                    <i class="material-icons text-success md-18 mr-2">lens</i>
                                                    <?php }?>                                                                                                   
                                                    <small class="text-uppercase js-lists-values-status"><?php echo $est; ?></small>
                                                </div>
                                            </td>
                                            <td class="text-right">
                                                <div class="d-flex align-items-center text-right">
                                                    <small class="text-uppercase text-muted mr-2">USER EMAIL</small>
                                                    <small class="text-uppercase js-lists-values-date"><?php echo $row["email"]; ?></small>
                                              
                                                </div>
                                            </td>
                                            <td>
                                            <div class="btn-group dropleft ">
									
                                  
                                    <button id="dropdown1" onclick="pulsar_delete('<?php echo $row['request_id']; ?>');" type="button btn-primary"  data-toggle="tooltip" data-placement="left" title="Borrar" class="btn btn-flush " >
                                     <i class="material-icons  text-primary">delete</i>
                                   </button>
                                   
                                                                                                         
                                   <button id="dropdown1" onclick="pulsar_modificar('<?php echo $row['request_id']; ?>');" type="button btn-primary"  data-toggle="tooltip" data-placement="left" title="Modificar" class="btn btn-flush " >
                                     <i class="material-icons  text-primary">edit</i>
                                   </button>  
                                            </td>
                                        </tr>
                                        <?php } ?>

                                        

                                    </tbody>
                                </table>
                            </div>

                     

                        </div>
                    
                    </div>