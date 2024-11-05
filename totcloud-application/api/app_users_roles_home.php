
<?php

$id="";
$nombre="";
$descripcion="";

$encontrado=false;


include_once "config/actualizar_token.php";

if(!empty($_GET["id"]) || isset($_GET["id"]))
{$id=$_GET["id"];} 

$id=StringInputCleaner($id);

if(!empty($_GET["modificar"]) || isset($_GET["modificar"]))
{$modificar=$_GET["modificar"];} 

if(!empty($modificar) and !empty($id)){

    $stmt = $dbb->prepare("SELECT * FROM roles WHERE id= ? ");
    $stmt->bind_param('s', $id); // 's' indica que el parámetro es una cadena
    $stmt->execute();
    $result = $stmt->get_result(); // Obtener el resultado de la ejecución
   
    if ($result->num_rows > 0) {
        // Obtener el único resultado
        $fila = $result->fetch_assoc();
        $nombre=$fila['role_name'];
        $descripcion=$fila['description'];
        $encontrado=true;
        //$fecha_creacion=$fila['fecha_creacion'];
       // $fecha_creacion = explode(" ",$fecha_creacion)[0]; 
    }
} 

  $stmt = $dbb->prepare('select * from roles');
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

        <h1 class="h2">Users Rols Management</h1>

        <div class="card border-left-3 border-left-danger card-2by1">
        <div class="card-body">
                                    
                                    
        <form id="incidenciaForm" action="api/actions/app_update_user_rol.php" method="post" class="needs-validation" >


         <div class="row mb-3">
         <input type="hidden" name="id" value="<?php echo $id ?>">
            <?php if ($encontrado) {?>
                <input type="hidden" name="modificar" value=1>
            <?php } else {?>
                <input type="hidden" name="modificar" value=0>
                <?php }?>

                <div class="col-12">
                    <label for="nombre" class="form-label">Name</label>
                    <input type="text" class="form-control" value="<?php echo $nombre ?>" id="nombre" name="nombre" required>
                    <div class="invalid-feedback">
                        Por favor, ingrese su nombre.
                    </div>
                </div>
    
        </div>

        <div class="row mb-3">
         

                <div class="col-4">
                    <label for="descripcion" class="form-label">Description</label>
                    <input type="text" class="form-control" value="<?php echo $descripcion ?>" id="ddescripcion" name="descripcion" required>
                    <div class="invalid-feedback">
                        Por favor, ingrese su nombre.
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
                <button type="submit" class="btn btn-primary">Modificar</button>
                <?php } else {?>
                    <button type="submit" class="btn btn-primary">Registrar</button>
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
                                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-document">id</a>
                                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-amount">Name</a>
                                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-status">Description</a>
                                              

                                                
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                    <?php while ($row = $result->fetch_assoc()) {?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <small class="text-uppercase text-muted mr-2">Id</small>
                                                    <a href="" class="text-body small"><span class="js-lists-values-document"><?php echo $row["id"]; ?></span></a>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center">
                                                    <small class="text-uppercase text-muted mr-2">NAME</small>
                                                    <small class="text-uppercase"><span class="js-lists-values-amount"><?php echo $row["role_name"]; ?></span></small>
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