<div class="page pt-0">

               

                <div class="container page__container mb-3">
                    <div class="row flex-sm-nowrap">
                        <div class="col-sm-auto mb-3 mb-sm-0" style="width: 265px;">
                            <h1 class="h2 mb-1"><?php echo $app_titulo_principal ?></h1>
                            <p class="d-flex align-items-center mb-4">
                                <a href="" class="btn btn-sm btn-success mr-2">Follow @<?php echo $app_titulo_principal ?></a>
                            </p>
                            <div class="text-muted d-flex align-items-center mb-2">
                                <i class="material-icons mr-1">account_box</i>
                                <div class="flex"><?php echo $firstname_user." ".$lastname_user ?></div>
                            </div>
                            <div class="text-muted d-flex align-items-center mb-4">
                                <i class="material-icons mr-1">email</i>
                                <div class="flex"><?php echo $email_user ?></div>
                            </div>

                            <h4>Database Practice</h4>
                            <p class="text-black-70 measure-paragraph">The company TotCloud, which specializes in providing infrastructure as a service (PaaS), in addition to software as a service (SaaS), has commissioned us to modify its software application to enhance current performance and incorporate some services that it does not currently offer. The functionalities that the application must have include everything from generating the complete catalog of PaaS or SaaS components to configuring and generating these services so that they can be used by end users.</p>

                            <h4>Authors</h4>
                            <p class="text-black-70 measure-paragraph">The authors of the practice are:</p>
                            <div class="d-flex align-items-center mb-2">
                                <i class="material-icons mr-1">account_box</i>
                                <div class="flex">Michele Gentile</div>
                            </div>
                            <div class=" d-flex align-items-center mb-4">
                                <i class="material-icons mr-1">account_box</i>
                                <div class="flex">Antonio Contestí</div>
                            </div>
                        </div>
                        <div class="col-sm">
                            <!-- <div class="flex search-form search-form--light mb-4">
  <button class="btn pr-3" type="button" role="button"><i class="material-icons">search</i></button>
  <input type="text" class="form-control" placeholder="Search" id="searchSample02">
</div> -->
<h1 class="h2">PaaS</h1>
<ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="index.php">Home</a></li>

            </ol>
                        <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <a href="index.php?opcion=requests" class="mr-3">
                                            <img src="assets/images/img/paas.png" alt="" class="rounded" width="100">
                                        </a>
                                        <div class="flex">
                                            <h4 class="card-title mb-0"><a href="index.php?opcion=requests">PaaS DEDICATED SERVERS</a></h4>
                                            <?php if ($es_admin!=1){ ?>
                                            <span class="badge badge-primary">ACCESS FOR ALL USERS</span>
                                            <?php } else {?>
                                                <span class="badge badge-danger">ACCESS FOR ADMIN USERS</span>
                                                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <ul class="list-group list-group-fit">
                                    <li class="list-group-item">
                                        <a href="index.php?opcion=paas" class="text-body text-decoration-0 d-flex align-items-center">
                                        <?php if ($es_admin==1){ ?>
                                            Management Module for TotCloud's PaaS Dedicated Servers
                                         <?php } else { ?>
                                            Consultation and Request Module for TotCloud's PaaS Dedicated Servers
                                            <?php } ?>
                                            <i class="material-icons text-muted ml-auto" style="font-size: inherit;">check</i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <?php if ($es_admin==1){ ?>
                            <h1 class="h2">Master Table Configuration</h1>
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <a href="index.php?opcion=paas_memory" class="mr-3">
                                            <img src="assets/images/img/paas_memory.png" width="100" alt="" class="rounded">
                                        </a>
                                        <div class="flex">
                                            <h4 class="card-title mb-0"><a href="index.php?opcion=paas_memory">Memory Master Table Configuration </a></h4>
                                            <span class="badge badge-danger">ACCESS FOR ADMIN USERS</span>
                                        </div>
                                    </div>
                                </div>
                                <ul class="list-group list-group-fit">
                                    <li class="list-group-item">
                                        <a href="index.php?opcion=paas_memory" class="text-body text-decoration-0 d-flex align-items-center">
                                        
                                        Maintenance Module for the Memory Items Table
                                        
                                            <i class="material-icons text-muted ml-auto" style="font-size: inherit;">check</i>
                                        </a>
                                    </li>
                                   
                                </ul>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <a href="index.php?opcion=paas_processor" class="mr-3">
                                            <img src="assets/images/img/paas_processor.png" width="100" alt="" class="rounded">
                                        </a>
                                        <div class="flex">
                                            <h4 class="card-title mb-0"><a href="index.php?opcion=paas_processor">Processor Master Table Configuration </a></h4>
                                            <span class="badge badge-danger">ACCESS FOR ADMIN USERS</span>
                                        </div>
                                    </div>
                                </div>
                                <ul class="list-group list-group-fit">
                                    <li class="list-group-item">
                                        <a href="index.php?opcion=paas_processor" class="text-body text-decoration-0 d-flex align-items-center">
                                        
                                        Maintenance Module for the Processors Items Table
                                        
                                            <i class="material-icons text-muted ml-auto" style="font-size: inherit;">check</i>
                                        </a>
                                    </li>
                                   
                                </ul>
                            </div>



                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <a href="index.php?opcion=paas_storage" class="mr-3">
                                            <img src="assets/images/img/paas_storage.png" width="100" alt="" class="rounded">
                                        </a>
                                        <div class="flex">
                                            <h4 class="card-title mb-0"><a href="index.php?opcion=paas_storage">Storage Master Table Configuration </a></h4>
                                            <span class="badge badge-danger">ACCESS FOR ADMIN USERS</span>
                                        </div>
                                    </div>
                                </div>
                                <ul class="list-group list-group-fit">
                                    <li class="list-group-item">
                                        <a href="index.php?opcion=paas_storage" class="text-body text-decoration-0 d-flex align-items-center">
                                        
                                        Maintenance Module for the Storage Items Table
                                        
                                            <i class="material-icons text-muted ml-auto" style="font-size: inherit;">check</i>
                                        </a>
                                    </li>
                                   
                                </ul>
                            </div>


                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <a href="index.php?opcion=paas_datacenterregion" class="mr-3">
                                            <img src="assets/images/img/paas_data_center.png" width="100" alt="" class="rounded">
                                        </a>
                                        <div class="flex">
                                            <h4 class="card-title mb-0"><a href="index.php?opcion=paas_datacenterregion">Data Center Master Table Configuration </a></h4>
                                            <span class="badge badge-danger">ACCESS FOR ADMIN USERS</span>
                                        </div>
                                    </div>
                                </div>
                                <ul class="list-group list-group-fit">
                                    <li class="list-group-item">
                                        <a href="index.php?opcion=paas_datacenterregion" class="text-body text-decoration-0 d-flex align-items-center">
                                        
                                        Maintenance Module for the Data Center Items Table
                                        
                                            <i class="material-icons text-muted ml-auto" style="font-size: inherit;">check</i>
                                        </a>
                                    </li>
                                   
                                </ul>
                            </div>             
                            
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <a href="index.php?opcion=paas_private_bandwidth" class="mr-3">
                                            <img src="assets/images/img/paas_private_bandwidth.png" width="100" alt="" class="rounded">
                                        </a>
                                        <div class="flex">
                                            <h4 class="card-title mb-0"><a href="index.php?opcion=paas_private_bandwidth">Private bandwidth Master Table Configuration </a></h4>
                                            <span class="badge badge-danger">ACCESS FOR ADMIN USERS</span>
                                        </div>
                                    </div>
                                </div>
                                <ul class="list-group list-group-fit">
                                    <li class="list-group-item">
                                        <a href="index.php?opcion=paas_private_bandwidth" class="text-body text-decoration-0 d-flex align-items-center">
                                        
                                        Maintenance Module for the Private bandwidth Items Table
                                        
                                            <i class="material-icons text-muted ml-auto" style="font-size: inherit;">check</i>
                                        </a>
                                    </li>
                                   
                                </ul>
                            </div>    
                            
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <a href="index.php?opcion=paas_public_bandwidth" class="mr-3">
                                            <img src="assets/images/img/paas_public_bandwidth.png" width="100" alt="" class="rounded">
                                        </a>
                                        <div class="flex">
                                            <h4 class="card-title mb-0"><a href="index.php?opcion=paas_public_bandwidth">Public bandwidth Master Table Configuration </a></h4>
                                            <span class="badge badge-danger">ACCESS FOR ADMIN USERS</span>
                                        </div>
                                    </div>
                                </div>
                                <ul class="list-group list-group-fit">
                                    <li class="list-group-item">
                                        <a href="index.php?opcion=paas_public_bandwidth" class="text-body text-decoration-0 d-flex align-items-center">
                                        
                                        Maintenance Module for the Public bandwidth Items Table
                                        
                                            <i class="material-icons text-muted ml-auto" style="font-size: inherit;">check</i>
                                        </a>
                                    </li>
                                   
                                </ul>
                            </div>           
                            
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <a href="index.php?opcion=paas_os" class="mr-3">
                                            <img src="assets/images/img/paas_os.png" width="100" alt="" class="rounded">
                                        </a>
                                        <div class="flex">
                                            <h4 class="card-title mb-0"><a href="index.php?opcion=paas_os">OS Master Table Configuration </a></h4>
                                            <span class="badge badge-danger">ACCESS FOR ADMIN USERS</span>
                                        </div>
                                    </div>
                                </div>
                                <ul class="list-group list-group-fit">
                                    <li class="list-group-item">
                                        <a href="index.php?opcion=paas_os" class="text-body text-decoration-0 d-flex align-items-center">
                                        
                                        Maintenance Module for the OS bandwidth Items Table
                                        
                                            <i class="material-icons text-muted ml-auto" style="font-size: inherit;">check</i>
                                        </a>
                                    </li>
                                   
                                </ul>
                            </div>     
                            
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <a href="index.php?opcion=paas_commitment_period" class="mr-3">
                                            <img src="assets/images/img/paas_commitment_period.png" width="100" alt="" class="rounded">
                                        </a>
                                        <div class="flex">
                                            <h4 class="card-title mb-0"><a href="index.php?opcion=paas_commitment_period">Commitment Period Master Table Configuration </a></h4>
                                            <span class="badge badge-danger">ACCESS FOR ADMIN USERS</span>
                                        </div>
                                    </div>
                                </div>
                                <ul class="list-group list-group-fit">
                                    <li class="list-group-item">
                                        <a href="index.php?opcion=paas_commitment_period" class="text-body text-decoration-0 d-flex align-items-center">
                                        
                                        Maintenance Module for the Commitment Period bandwidth Items Table
                                        
                                            <i class="material-icons text-muted ml-auto" style="font-size: inherit;">check</i>
                                        </a>
                                    </li>
                                   
                                </ul>
                            </div>                                

                            <?php } ?>
    

                        </div>
                    </div>
                </div>

       
            </div>