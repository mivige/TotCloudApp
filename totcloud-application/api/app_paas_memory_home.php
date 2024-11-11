
<?php

$id="";
$price="";
$description="";
$code="";

$encontrado=false;
if ($es_admin==1){

   


include_once "config/actualizar_token.php";



if(!empty($_GET["id"]) || isset($_GET["id"]))
{$id=$_GET["id"];} 

$id=StringInputCleaner($id);

if(!empty($_GET["modificar"]) || isset($_GET["modificar"]))
{$modificar=$_GET["modificar"];} 

if(!empty($modificar) and !empty($id)){

    $stmt = $dbb->prepare("SELECT * FROM paas_memory WHERE id= ? ");
    $stmt->bind_param('s', $id); // 's' indica que el parámetro es una cadena
    $stmt->execute();
    $result = $stmt->get_result(); // Obtener el resultado de la ejecución
   
    if ($result->num_rows > 0) {
        // Obtener el único resultado
        $fila = $result->fetch_assoc();
        $code=$fila['code'];
        $description=$fila['description'];
        $price=$fila['price'];
        $encontrado=true;
        //$fecha_creacion=$fila['fecha_creacion'];
       // $fecha_creacion = explode(" ",$fecha_creacion)[0]; 
    }
} 

  $stmt = $dbb->prepare('select * from paas_memory pm inner join currencytype ct on pm.currency_type =ct.currency_code');
  $dbb->set_charset("utf8");
  $stmt->execute();
  $result = $stmt->get_result();
  
?>

<div class="row m-0">
    <div class="col-lg container-fluid page__container">
            <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Users</li>
            </ol>

        <h1 class="h2">Memory Management</h1>
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
                                    
                                    
        <form id="incidenciaForm" action="api/actions/app_update_paas_memory.php" method="post" class="needs-validation" >


         <div class="row mb-3">
         <input type="hidden" name="id" value="<?php echo $id ?>">
            <?php if ($encontrado) {?>
                <input type="hidden" name="modificar" value=1>
            <?php } else {?>
                <input type="hidden" name="modificar" value=0>
                <?php }?>


                <div class="col-2">
                    <label for="vode" class="form-label">Code</label>
                    <input maxlength="20" type="text" class="form-control" value="<?php echo $code ?>" id="code" name="code" required>
                    <div class="invalid-feedback">
                        Please, insert the code.
                    </div>
                </div>
                <div class="col-2">
                    <label for="price" class="form-label">Price</label>
                    <input maxlength="20" type="text" class="form-control" value="<?php echo $price ?>" id="price" name="price" required pattern="^-?\d+(\.\d{1,2})?$">
                    <div class="invalid-feedback">
                        Please, insert the price.
                    </div>
                </div>
    
        </div>

        <div class="row mb-3">
         

                <div class="col-12">
                    <label for="description" class="form-label">Description</label>
                    <input maxlength="255" type="text" class="form-control" value="<?php echo $description ?>" id="description" name="description" required>
                    <div class="invalid-feedback">
                        Please, insert the description.
                    </div>
                </div>
                
       
                 
        </div>        

<!--
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha de la Incidencia</label>
                <input type="date" class="form-control" id="fecha" value="<?php echo $fecha_creacion ?>" name="fecha_creacion" required>
                <div class="invalid-feedback">
                    Por favor, seleccione una fecha.
                </div>
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-control" id="estado" name="estado" required>
                <option value="">Seleccione el estado</option>
                <option value="abierto" <?php echo ($estado === 'abierto') ? 'selected' : ''; ?>>Abierto</option>
                <option value="en progreso" <?php echo ($estado === 'en progreso') ? 'selected' : ''; ?>>En progreso</option>
                <option value="cerrado" <?php echo ($estado === 'cerrado') ? 'selected' : ''; ?>>Cerrado</option>
                </select>
                <div class="invalid-feedback">
                    Por favor, seleccione un estado para la incidencia.
                </div>
            </div>  
            -->     
            


            <?php if ($encontrado) {?>
                <button type="submit" class="btn btn-primary">Modify</button>
                <?php } else {?>
                    <button type="submit" class="btn btn-primary">New</button>
                <?php }?>
            
        </form>
                                      
                                 
                                </div>
                            </div>

                            



                            <div class="card table-responsive" data-toggle="lists" data-lists-values='[
    "js-lists-values-document", 
    "js-lists-values-amount",
    "js-lists-values-status"
 

  ]' data-lists-sort-by="js-lists-values-document" data-lists-sort-asc="true">
                                <table class="table mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th colspan="4">
                                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-document">Code</a>
                                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-amount">Price</a>
                                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-status">Description</a>
                                              

                                                
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                    <?php while ($row = $result->fetch_assoc()) {?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <small class="text-uppercase text-muted mr-2">Code</small>
                                                    <a href="" class="text-body small"><span class="js-lists-values-document"><?php echo $row["code"]; ?></span></a>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center">
                                                    <small class="text-uppercase text-muted mr-2">PRICE</small>
                                                    <small class="text-uppercase"><span class="js-lists-values-amount"><?php echo $row["price"]; ?>&nbsp;<?php echo $row["currency_name"]; ?></span></small>
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <div class="d-flex align-items-center">
                                                    <small class="text-uppercase text-muted mr-2">DESCRIPTION</small>
                                                    <small class="text-uppercase"><span class="js-lists-values-status"><?php echo $row["description"]; ?></span></small>
                                                </div>
                                            </td>
                                                                                 

                                  
                                            <td>
                                            <div class="btn-group dropleft ">
									
                                  
                                       
                                    <button id="dropdown1" onclick="pulsar_delete('<?php echo $row['id']; ?>');" type="button btn-primary"  data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-flush " >
                                     <i class="material-icons  text-primary">delete</i>
                                   </button>
                                 
                                                                                                         
                                   <button id="dropdown1" onclick="pulsar_modificar('<?php echo $row['id']; ?>');" type="button btn-primary"  data-toggle="tooltip" data-placement="left" title="Modify" class="btn btn-flush " >
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

                    <?php }?>