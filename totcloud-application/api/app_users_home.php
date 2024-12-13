
<?php

$id="";
$firstname="";
$lastname="";
$lastname2="";
$email="";
$mobile_phone="";
$activo=0;
$admin=0;
$encontrado=false;


include_once "config/actualizar_token.php";

if(!empty($_GET["id"]) || isset($_GET["id"]))
{$id=$_GET["id"];} 

$id=StringInputCleaner($id);

if(!empty($_GET["modificar"]) || isset($_GET["modificar"]))
{$modificar=$_GET["modificar"];} 

if(!empty($modificar) and !empty($id)){

    $stmt = $dbb->prepare("SELECT id,firstname,lastname,lastname2,email,mobile_phone,active,admin FROM user WHERE id= ? ");
    $stmt->bind_param('s', $id); // 's' indica que el parámetro es una cadena
    $stmt->execute();
    $result = $stmt->get_result(); // Obtener el resultado de la ejecución
   
    if ($result->num_rows > 0) {
        // Obtener el único resultado
        $fila = $result->fetch_assoc();
        $firstname=$fila['firstname'];
        $lastname=$fila['lastname'];
        $lastname2=$fila['lastname2'];
        $email=$fila['email'];
        $mobile_phone=$fila['mobile_phone'];
        $activo=$fila['active'];
        $admin=$fila['admin'];
        $encontrado=true;
        //$fecha_creacion=$fila['fecha_creacion'];
       // $fecha_creacion = explode(" ",$fecha_creacion)[0]; 
    }
} 

  $stmt = $dbb->prepare('select id,firstname,lastname,lastname2,email,mobile_phone,active,admin from user');
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

        <h1 class="h2">Users Management</h1>
        <ol class="breadcrumb">
                    <li class="breadcrumb-item "><a href="index.php?opcion=users">Users Management</a></li>
                    <?php if (tieneRol("DEFROL1") or ($es_admin==1)){?>
                    <li class="breadcrumb-item active "><a href="index.php?opcion=roles">Users Rols Management</a></li>
                    <?php }?>
            </ol>


            <?php      if(!empty($_GET["error"])) {?>
        <?php      if(($_GET["error"]==1) || ($_GET["error"]==3)) {?>
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
                                    
                                    
        <form id="incidenciaForm" action="api/actions/app_update_user.php" method="post" class="needs-validation" >


         <div class="row mb-3">
         <input type="hidden" name="id" value="<?php echo $id ?>">
            <?php if ($encontrado) {?>
                <input type="hidden" name="modificar" value=1>
            <?php } else {?>
                <input type="hidden" name="modificar" value=0>
                <?php }?>

                <div class="col-4">
                    <label for="firstname" class="form-label">Name</label>
                    <input type="text" class="form-control" value="<?php echo $firstname ?>" id="firstname" name="firstname" required>
                    <div class="invalid-feedback">
                    Please enter your name.
                    </div>
                </div>
                <div class="col-4">
                    <label for="lastname" class="form-label">First Surname</label>
                    <input type="text" class="form-control" value="<?php echo $lastname ?>" id="lastname" name="lastname" required>
                    <div class="invalid-feedback">
                    Please enter your First Surname.
                    </div>
                </div>
                <div class="col-4">
                    <label for="lastname2" class="form-label">Second Surname</label>
                    <input type="text" class="form-control" value="<?php echo $lastname2 ?>" id="lastname2" name="lastname2" required>
                    <div class="invalid-feedback">
                    Please enter your Second Surname
                    </div>
                </div>
        </div>

        <div class="row mb-3">
         

                <div class="col-4">
                    <label for="mobile_phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" value="<?php echo $mobile_phone ?>" id="mobile_phone" name="mobile_phone" required>
                    <div class="invalid-feedback">
                    Please enter your mobile phone
                    </div>
                </div>
                <div class="col-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" value="<?php echo $email ?>" id="email" name="email" required>
                    <div class="invalid-feedback">
                    Please enter your email
                    </div>
                </div>
                <div class="col-4">

            
               
                                    <label class="form-label" for="activo">ACTIVE</label><br>
                                    <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                                        <?php if($activo==1){ ?>
                                        <input checked="" type="checkbox" id="activo" name="activo" class="custom-control-input">
                                        <?php } else { ?>
                                            <input type="checkbox" id="activo" name="activo" class="custom-control-input">
                                            <?php }  ?>
                                        <label class="custom-control-label" for="activo">Yes</label>
                                    </div>
                                    <label class="form-label mb-0" for="activo">Yes</label>
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
    "js-lists-values-status",
    "js-lists-values-status1",
    "js-lists-values-date"
  ]' data-lists-sort-by="js-lists-values-document" data-lists-sort-asc="true">
                                <table class="table mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th colspan="6">
                                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-document">Name</a>
                                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-amount">Email</a>
                                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-status">Active</a>
                                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-status1">Admin</a>
                                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-date">Mobile Phone</a>
                                                
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                    <?php while ($row = $result->fetch_assoc()) {?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <small class="text-uppercase text-muted mr-2">Name</small>
                                                    <a href="" class="text-body small"><span class="js-lists-values-document"><?php echo $row["firstname"]." ".$row["lastname"]." ".$row["lastname2"]; ?></span></a>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center">
                                                    <small class="text-uppercase text-muted mr-2">EMAIL</small>
                                                    <small class="text-uppercase"><span class="js-lists-values-amount"><?php echo $row["email"]; ?></span></small>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center">
                                                    <small class="text-uppercase text-muted mr-2">ACTIVE</small>
                                                    <?php if ($row["active"]==1){ ?>
                                                    <i class="material-icons text-primary md-18 mr-2">lens</i>
                                                    <small class="text-uppercase js-lists-values-status">ACTIVE</small>
                                                    <?php }?>
                                                    <?php if ($row["active"]!=1){ ?>
                                                    <i class="material-icons text-danger md-18 mr-2">lens</i>
                                                    <small class="text-uppercase js-lists-values-status">NO ACTIVE</small>
                                                    <?php }?>    
                                                                                                                                                    
                                                    
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <div class="d-flex align-items-center">
                                                    <small class="text-uppercase text-muted mr-2">ADMIN</small>
                                                    <?php if ($row["admin"]==1){ ?>
                                                    <i class="material-icons text-primary md-18 mr-2">lens</i>
                                                    <small class="text-uppercase js-lists-values-status1">ADMIN</small>
                                                    <?php }?>
                                                    <?php if ($row["admin"]!=1){ ?>
                                                    <i class="material-icons text-danger md-18 mr-2">lens</i>
                                                    <small class="text-uppercase js-lists-values-status1">NO ADMIN</small>
                                                    <?php }?>    
                                                                                                                                                    
                                                    
                                                </div>
                                            </td>

                                            <td class="text-right">
                                                <div class="d-flex align-items-center text-right">
                                                    <small class="text-uppercase text-muted mr-2">PHONE</small>
                                                    <small class="text-uppercase js-lists-values-date"><?php echo $row["mobile_phone"]; ?></small>
                                              
                                                </div>
                                            </td>
                                            <td>
                                            <div class="btn-group dropleft ">
									
                                  
                                      <?php if ($row["email"]!=$email_user)  { ?>   
                                    <button id="dropdown1" onclick="pulsar_delete('<?php echo $row['id']; ?>');" type="button btn-primary"  data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-flush " >
                                     <i class="material-icons  text-primary">delete</i>
                                   </button>
                                   <?php } ?>
                                                                                                         
                                   <button id="dropdown1" onclick="pulsar_modificar('<?php echo $row['id']; ?>');" type="button btn-primary"  data-toggle="tooltip" data-placement="left" title="Modify" class="btn btn-flush " >
                                     <i class="material-icons  text-primary">edit</i>
                                   </button>  

                                   <button id="dropdown1" onclick="window.location.href='index.php?opcion=users_roles&id=<?php echo $row['id']; ?>'" type="button btn-primary"  data-toggle="tooltip" data-placement="left" title="Add Rol" class="btn btn-flush " >
                                   <i class="material-icons">group_add</i>
                                   </button>  
                                            </td>
                                        </tr>
                                        <?php } ?>

                                        

                                    </tbody>
                                </table>
                            </div>

                     

                        </div>
                    
                    </div>