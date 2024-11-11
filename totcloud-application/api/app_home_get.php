
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
                       <?php if ($es_admin==1){ ?>
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <a href="index.php?opcion=users" class="mr-3">
                                            <img src="assets/images/img/admin_users.png" alt="" class="rounded" width="100">
                                        </a>
                                        <div class="media-body">
                                            <h4 class="card-title mb-0"><a href="index.php?opcion=users">USER MANAGEMENT</a></h4>
                                            <span class="badge badge-danger">RESTRICTED ACCESS FOR ADMINISTRATORS</span>
                                        </div>
                                    </div>
                                </div>
                                <ul class="list-group list-group-fit">
                                    <li class="list-group-item">
                                        <a href="index.php?opcion=users" class="text-body text-decoration-0 d-flex align-items-center">
                                        Access to the User and User Roles Maintenance Module
                                            <i class="material-icons text-muted ml-auto" style="font-size: inherit;">check</i>
                                        </a>
                                    </li>
                                    
                                </ul>
                            </div>
                            <?php } ?>

                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <a href="index.php?opcion=saas" class="mr-3">
                                            <img src="assets/images/img/saas.png" width="100" alt="" class="rounded">
                                        </a>
                                        <div class="flex">
                                            <h4 class="card-title mb-0"><a href="index.php?opcion=saas">SaaS SERVICES </a></h4>
                                            <span class="badge badge-primary">ACCESS FOR ALL USERS</span>
                                        </div>
                                    </div>
                                </div>
                                <ul class="list-group list-group-fit">
                                    <li class="list-group-item">
                                        <a href="index.php?opcion=saas" class="text-body text-decoration-0 d-flex align-items-center">
                                        <?php if ($es_admin==1){ ?>
                                            Management Module for TotCloud's SaaS Services
                                         <?php } else { ?>
                                            Consultation and Request Module for TotCloud's SaaS Services
                                            <?php } ?>  
                                            <i class="material-icons text-muted ml-auto" style="font-size: inherit;">check</i>
                                        </a>
                                    </li>
                                   
                                </ul>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <a href="index.php?opcion=paas" class="mr-3">
                                            <img src="assets/images/img/paas.png" alt="" class="rounded" width="100">
                                        </a>
                                        <div class="flex">
                                            <h4 class="card-title mb-0"><a href="index.php?opcion=paas">PaaS SERVICES</a></h4>
                                            <span class="badge badge-primary">ACCESS FOR ALL USERS</span>
                                        </div>
                                    </div>
                                </div>
                                <ul class="list-group list-group-fit">
                                    <li class="list-group-item">
                                        <a href="index.php?opcion=paas" class="text-body text-decoration-0 d-flex align-items-center">
                                        <?php if ($es_admin==1){ ?>
                                            Management Module for TotCloud's PaaS Services
                                         <?php } else { ?>
                                            Consultation and Request Module for TotCloud's PaaS Services
                                            <?php } ?>
                                            <i class="material-icons text-muted ml-auto" style="font-size: inherit;">check</i>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>

       
            </div>