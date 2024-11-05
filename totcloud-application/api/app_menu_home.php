<ul class="nav navbar-nav">
                                <li class="nav-item dropdown active">
                                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><?php echo $app_titulo_principal ?></a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="index.php">Home</a>
                                        <?php if ($es_admin==1){ ?>
                                        <a class="dropdown-item" href="index.php?opcion=users">User Managemet</a>
                                        <?php }  ?>
                                        <a class="dropdown-item" href="index.php?opcion=">SaaS Services</a>
                                        <a class="dropdown-item" href="index.php?opcion=">PaaS Services</a>
                                        
                                    </div>
                                </li>
                              
                            </ul>