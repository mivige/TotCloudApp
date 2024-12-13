
<?php

$id="";
$fila = "";
$category_code="";
$public_bandwidth_code="";
$private_bandwidth_code="";
$storage_code="";
$memory_code="";
$processor_code="";
$data_center_region_code="";
$os_code="";
$commitment_period_code="";
$request_id="";
$encontrado=true;
$encontrado=false;

//$fecha_creacion="";


include_once "config/actualizar_token.php";
   


   


if(!empty($_GET["id"]) || isset($_GET["id"]))
{$id=$_GET["id"];} 

$id=StringInputCleaner($id);

if(!empty($_GET["modificar"]) || isset($_GET["modificar"]))
{$modificar=$_GET["modificar"];} 

if(!empty($modificar) and !empty($id)){

    $stmt = $dbb->prepare("SELECT id,category_code,public_bandwidth_code,private_bandwidth_code,storage_code,memory_code,processor_code,data_center_region_code,os_code,state,commitment_period,pds.request_id FROM paas_dedicated_server pds join request rq on pds.request_id=rq.request_id WHERE id= ? ");
    $stmt->bind_param('s', $id); // 's' indica que el parámetro es una cadena
    $stmt->execute();
    $result = $stmt->get_result(); // Obtener el resultado de la ejecución
   
    if ($result->num_rows > 0) {
        // Obtener el único resultado
        $fila = $result->fetch_assoc();
        $category_code=$fila['category_code'];
        $public_bandwidth_code=$fila['public_bandwidth_code'];
        $private_bandwidth_code=$fila['private_bandwidth_code'];
        $storage_code=$fila['storage_code'];
        $memory_code=$fila['memory_code'];
        $processor_code=$fila['processor_code'];
        $data_center_region_code=$fila['data_center_region_code'];
        $os_code=$fila['os_code'];
        $state=$fila['state'];
        $commitment_period_code=$fila['commitment_period'];
        $request_id=$fila['request_id'];
        $encontrado=true;
        //$fecha_creacion=$fila['fecha_creacion'];
        //$fecha_creacion = explode(" ",$fecha_creacion)[0]; 
    }
} 

if ($es_admin==1){

  $stmt = $dbb->prepare('select pr.request_id,pr.date,pr.state,pr.user_id,us.email,pds.id as pdsid,cat.name from request pr inner join paas_dedicated_server pds on pr.request_id=pds.request_id inner join category cat on cat.code=pds.category_code inner join user us on us.id=pr.user_id');
  $dbb->set_charset("utf8");
  $stmt->execute();
  $result = $stmt->get_result();
} else{

    $stmt = $dbb->prepare('select pr.request_id,pr.date,pr.state,pr.user_id,us.email,pds.id as pdsid,cat.name from request pr inner join paas_dedicated_server pds on pr.request_id=pds.request_id inner join category cat on cat.code=pds.category_code inner join user us on us.id=pr.user_id and us.id=?');
    
    $stmt->bind_param('s', $id_user); // 's' indica que el parámetro es una cadena
    $dbb->set_charset("utf8");
    $stmt->execute();
    $result = $stmt->get_result();

}
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
                

                <?php $stmt_tmp = $dbb->prepare('select code,description,price from ds_processor ;');
            $stmt_tmp->execute();
            $result_tmp = $stmt_tmp->get_result();
            ?>

            <div class="col-6">
                <label for="processor" class="form-label">Processor</label>
                <select id="custom-select" class="form-control custom-select" id="processor" name="processor" required>
                    <option value="" disabled selected>Select a Processor</option>
                    <?php while ($processor = $result_tmp->fetch_assoc()): ?>
                        
                        <option  <?php if ($processor_code==$processor['code']){?>selected<?php };?> value="<?php echo htmlspecialchars($processor['code']); ?>"><?php echo htmlspecialchars($processor['description']); echo" +";echo htmlspecialchars($processor['price']);echo" ";echo htmlspecialchars("€")?></option>
                    <?php endwhile; ?>
                </select>
                <div class="invalid-feedback">
                    Please, select a Processor.
                </div>
            </div>

            <?php $stmt_tmp = $dbb->prepare('select code,description,price from ds_memory ;');
            $stmt_tmp->execute();
            $result_tmp = $stmt_tmp->get_result();
            ?>           
            <div class="col-6">
                <label for="memory" class="form-label">Memory</label>
                <select id="custom-select" class="form-control custom-select" id="memory" name="memory" required>
                    <option value="" disabled selected>Select a Memory</option>
                    <?php while ($memory = $result_tmp->fetch_assoc()): ?>
                        <option <?php if ($memory_code==$memory['code']){?>selected<?php };?>    value="<?php echo htmlspecialchars($memory['code']); ?>"><?php echo htmlspecialchars($memory['description']); echo" +";echo htmlspecialchars($memory['price']);echo" ";echo htmlspecialchars("€") ?></option>
                    <?php endwhile; ?>
                </select>
                <div class="invalid-feedback">
                    Please, select a memory.
                </div>
            </div> 


            </div>


            <div class="row mb-3">
            <?php $stmt_tmp = $dbb->prepare('select code,description,price from ds_private_bandwidth ;');
            $stmt_tmp->execute();
            $result_tmp = $stmt_tmp->get_result();
            ?>

            <div class="col-6">
                <label for="private_bandwith" class="form-label">Private Bandwith</label>
                <select id="custom-select" class="form-control custom-select" id="private_bandwith" name="private_bandwith" required>
                    <option value="" disabled selected>Select a Private Bandwith</option>
                    <?php while ($private_bandwith = $result_tmp->fetch_assoc()): ?>
                        <option <?php if ($private_bandwidth_code==$private_bandwith['code']){?>selected<?php };?>  value="<?php echo htmlspecialchars($private_bandwith['code']); ?>"><?php echo htmlspecialchars($private_bandwith['description']); ; echo" +";echo htmlspecialchars($private_bandwith['price']);echo" ";echo htmlspecialchars("€") ?></option>
                    <?php endwhile; ?>
                </select>
                <div class="invalid-feedback">
                    Please, select a Private Bandwidth.
                </div>
            </div>

            <?php $stmt_tmp = $dbb->prepare('select code,description,price from ds_public_bandwidth ;');
            $stmt_tmp->execute();
            $result_tmp = $stmt_tmp->get_result();
            ?>           
            <div class="col-6">
                <label for="public_bandwith" class="form-label">Public Bandwith</label>
                <select id="custom-select" class="form-control custom-select" id="public_bandwith" name="public_bandwith" required>
                    <option value="" disabled selected>Select a Public Bandwith</option>
                    <?php while ($public_bandwith = $result_tmp->fetch_assoc()): ?>
                        <option <?php if ($public_bandwidth_code==$public_bandwith['code']){?>selected<?php };?>   value="<?php echo htmlspecialchars($public_bandwith['code']); ?>"><?php echo htmlspecialchars($public_bandwith['description']); ; echo" +";echo htmlspecialchars($public_bandwith['price']);echo" ";echo htmlspecialchars("€") ?></option>
                    <?php endwhile; ?>
                </select>
                <div class="invalid-feedback">
                    Please, select a memory.
                </div>
            </div> 
            </div>



            <div class="row mb-3">
            <?php $stmt_tmp = $dbb->prepare('select code,description,price from ds_storage ps ;');
            $stmt_tmp->execute();
            $result_tmp = $stmt_tmp->get_result();
            ?>

            <div class="col-6">
                <label for="storage" class="form-label">Storage</label>
                <select id="custom-select" class="form-control custom-select" id="storage" name="storage" required>
                    <option value="" disabled selected>Select a Storage</option>
                    <?php while ($storage = $result_tmp->fetch_assoc()): ?>
                        <option <?php if ($storage_code==$storage['code']){?>selected<?php };?>   value="<?php echo htmlspecialchars($storage['code']); ?>"><?php echo htmlspecialchars($storage['description']); echo" +";echo htmlspecialchars($storage['price']);echo" ";echo htmlspecialchars("€") ?></option>
                    <?php endwhile; ?>
                </select>
                <div class="invalid-feedback">
                    Please, select a Storage.
                </div>
            </div>

            <?php $stmt_tmp = $dbb->prepare('select code,name,price from ds_os ;');
            $stmt_tmp->execute();
            $result_tmp = $stmt_tmp->get_result();
            ?>           
            <div class="col-6">
                <label for="os" class="form-label">OS SYSTEM</label>
                <select id="custom-select" class="form-control custom-select" id="os" name="os" required>
                    <option value="" disabled selected>Select a Operating Systemy</option>
                    <?php while ($os = $result_tmp->fetch_assoc()): ?>
                        <option <?php if ($os_code==$os['code']){?>selected<?php };?>   value="<?php echo htmlspecialchars($os['code']); ?>"><?php echo htmlspecialchars($os['name']);  echo" +";echo htmlspecialchars($os['price']);echo" ";echo htmlspecialchars("€")?></option>
                    <?php endwhile; ?>
                </select>
                <div class="invalid-feedback">
                    Please, select a OS.
                </div>
            </div>         

            </div>


            <div class="row mb-3">
            <?php $stmt_tmp = $dbb->prepare('SELECT code,description FROM ds_datacenterregion');
            $stmt_tmp->execute();
            $result_tmp = $stmt_tmp->get_result();
            ?>

            <div class="col-6">
                <label for="datacenterregion" class="form-label">Data Center Region</label>
                <select id="custom-select" class="form-control custom-select" id="datacenterregion" name="datacenterregion" required>
                    <option value="" disabled selected>Select a Data Center Region</option>
                    <?php while ($datacenterregion = $result_tmp->fetch_assoc()): ?>
                        <option <?php if ($data_center_region_code==$datacenterregion['code']){?>selected<?php };?>   value="<?php echo htmlspecialchars($datacenterregion['code']); ?>"><?php echo htmlspecialchars($datacenterregion['description']); ?></option>
                    <?php endwhile; ?>
                </select>
                <div class="invalid-feedback">
                    Please, select a Data Center Region.
                </div>
            </div>

            <?php $stmt_tmp = $dbb->prepare('SELECT code,description FROM commitment_period');
            $stmt_tmp->execute();
            $result_tmp = $stmt_tmp->get_result();
            ?>           
            <div class="col-6">
                <label for="commitment_period" class="form-label">Commitment Period</label>
                <select id="custom-select" class="form-control custom-select" id="commitment_period" name="commitment_period" required>
                    <option value="" disabled selected>Select a Commitment Period</option>
                    <?php while ($commitment_period = $result_tmp->fetch_assoc()): ?>
                        <option <?php if ($commitment_period_code==$commitment_period['code']){?>selected<?php };?> value="<?php echo htmlspecialchars($commitment_period['code']); ?>"><?php echo htmlspecialchars($commitment_period['description']); ?></option>
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
                    Please, select a Commitment Period.
                </div>
            </div>

  </div>
</div>
<?php } ?>

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
                                   
                                                                                                         
                                   <button id="dropdown1" onclick="pulsar_modificar('<?php echo $row['pdsid']; ?>');" type="button btn-primary"  data-toggle="tooltip" data-placement="left" title="Modificar" class="btn btn-flush " >
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
