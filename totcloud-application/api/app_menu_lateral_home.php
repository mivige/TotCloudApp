<div class="mdk-drawer js-mdk-drawer" id="default-drawer">
        <div class="mdk-drawer__content ">
            <div class="sidebar sidebar-left sidebar-dark bg-dark o-hidden" data-perfect-scrollbar>
                <div class="sidebar-p-y">
                    <div class="sidebar-heading"><?php echo $app_titulo_principal ?></div>
                    <ul class="sidebar-menu sm-active-button-bg">
                    <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="index.php">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">import_contacts</i>Home
                            </a>
                        </li>  


                        <?php if ($es_admin==1){ ?>
                            <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="index.php?opcion=users">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">import_contacts</i>Users Management
                            </a>
                        </li>
                        <?php } else { ?>
                 
                        </li>
                            
                            <?php }  ?>

                            <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="index.php?opcion=saas">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">import_contacts</i> SaaS Services
                            </a>
                        </li>                          
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="index.php?opcion=paas">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">import_contacts</i> PaaS Services
                            </a>
                        </li>                       
                    </ul>
                    <!-- Account menu -->
                    
                   
                    <div class="sidebar-heading">Account</div>
                    <ul class="sidebar-menu">
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button sidebar-js-collapse" data-toggle="collapse" href="#account_menu">
                                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">person_outline</i>
                                <?php echo $firstname_user." ".$lastname_user ?>
                                <span class="ml-auto sidebar-menu-toggle-icon"></span>
                            </a>

                            <?php if ($es_admin==1){ ?>
                            <ul class="sidebar-submenu sm-indent collapse" id="account_menu">
                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-button" href="index.php?opcion=users">
                                        <span class="sidebar-menu-text">User Management</span>
                                    </a>
                                </li>
                              
                            </ul>
                            <?php }  ?>
                            
                            <ul class="sidebar-submenu sm-indent collapse" id="account_menu">
                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-button" href="app_logout.php">
                                        <span class="sidebar-menu-text">Logout</span>
                                    </a>
                                </li>
                              
                            </ul>
                        </li>

                
                    </ul>


                    
              
                    
            </div>
        </div>
    </div>