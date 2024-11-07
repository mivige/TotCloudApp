
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


if(empty($id)){

    
} else {


if(!empty($id)){

    $stmt = $dbb->prepare("SELECT * FROM users WHERE id= ? ");
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
        $activo=$fila['activo'];
        $admin=$fila['admin'];
        $encontrado=true;
        //$fecha_creacion=$fila['fecha_creacion'];
       // $fecha_creacion = explode(" ",$fecha_creacion)[0]; 
    } else {

        

    }
} 


  
?>

<div class="row m-0">
    <div class="col-lg container-fluid page__container">
            <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Users</li>
            </ol>

        <h1 class="h2">Roles from a User Management</h1>
        <ol class="breadcrumb">
                    <li class="breadcrumb-item "><a href="index.php?opcion=users">Users Management</a></li>
                    <?php if (tieneRol("DEFROL1")){?>
                    <li class="breadcrumb-item active "><a href="index.php?opcion=roles">Users Rols Management</a></li>
                <?php } ?>
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
                                    
                                    
        <form id="incidenciaForm" action="api/actions/app_update_user_roles_assign.php" method="post" class="needs-validation" >


         <div class="row mb-3">
         <input type="hidden" name="id" value="<?php echo $id ?>">
       
                <input type="hidden" name="modificar" value=0>
              

                <div class="col-4">
                    <label for="firstname" class="form-label">Name: <?php echo $firstname ?></label>
                   
                </div>
                <div class="col-4">
                    <label for="lastname" class="form-label">First Surname: <?php echo $lastname ?></label>
                    
                </div>
                <div class="col-4">
                    <label for="lastname2" class="form-label">Second Surname: <?php echo $lastname2 ?></label>
                   
                </div>
        </div>

        <div class="row mb-3">
         

                <div class="col-4">
                    <label for="mobile_phone" class="form-label">Phone: <?php echo $mobile_phone ?></label>
                  
                </div>
                <div class="col-4">
                    <label for="email" class="form-label">Email: <?php echo $email ?></label>
                   
                </div>
                <div class="col-4">

            
               
                                    <label class="form-label" for="activo">ACTIVE: <?php if($activo==1){ echo "YES";}else{echo "NO";} ?></label><br>
                                 
            </div>


        
                 
        </div>  
        
        
        <div class="row mb-3">

<?php $stmt = $dbb->prepare('SELECT code, role_name FROM roles');
$stmt->execute();

// Obtenemos los resultados
$result = $stmt->get_result();
?>

 <div class="col-4">
     <label for="role" class="form-label">Role</label>
     <select id="custom-select" class="form-control custom-select" id="role" name="role" required>
         <option value="" disabled selected>Select a role</option>
         <?php while ($role = $result->fetch_assoc()): ?>
             <option value="<?php echo htmlspecialchars($role['code']); ?>"><?php echo htmlspecialchars($role['role_name']); ?></option>
         <?php endwhile; ?>
     </select>
     <div class="invalid-feedback">
         Please, select a role.
     </div>
 </div>

 </div>



<?php
// Cerramos la declaración
$stmt->close();
?>



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
                <button type="submit" class="btn btn-primary">Add</button>
                
                <?php }?>
            
        </form>
                                      
                                 
                                </div>
                            </div>
                            <h2 class="h2">Roles assigned to the selected User</h2>
                            
                            <?php                         $stmt = $dbb->prepare('select ur.id as id1,u.id,r.code,r.description,r.role_name from user_roles as ur inner join users u on ur.user_id=u.id inner join roles r on ur.role_id=r.code and u.id=?');
  $stmt->bind_param('s', $id); // 's' indica que el parámetro es una cadena
  $dbb->set_charset("utf8");
  $stmt->execute();
  $result = $stmt->get_result();
  ?>

                            <div class="card table-responsive" data-toggle="lists" data-lists-values='[
    "js-lists-values-document", 
    "js-lists-values-amount",
    "js-lists-values-status"
 
  ]' data-lists-sort-by="js-lists-values-document" data-lists-sort-asc="true">
                                <table class="table mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th colspan="4">
                                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-document">CODE</a>
                                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-amount">NAME</a>
                                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-status">DESCRPTION</a>

                                                
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
                                                    <small class="text-uppercase text-muted mr-2">NAME</small>
                                                    <small class="text-uppercase"><span class="js-lists-values-amount"><?php echo $row["role_name"]; ?></span></small>
                                                </div>
                                            </td>


                                            <td class="text-right">
                                                <div class="d-flex align-items-center text-right">
                                                    <small class="text-uppercase text-muted mr-2">DESCRIPTION</small>
                                                    <small class="text-uppercase js-lists-values-status"><?php echo $row["description"]; ?></small>
                                              
                                                </div>
                                            </td>
                                            <td>
                                            <div class="btn-group dropleft ">
									
                                  
                                     
                                    <button id="dropdown1" onclick="pulsar_delete('<?php echo $row['id1']; ?>');" type="button btn-primary"  data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-flush " >
                                     <i class="material-icons  text-primary">delete</i>
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