<div data-perfect-scrollbar class="position-relative">
                                        <div class="dropdown-header"><strong>Messages</strong></div>
                                        <div class="list-group list-group-flush mb-0">

                                        <a href="#" class="list-group-item list-group-item-action">
                                                <span class="d-flex align-items-center mb-1">
                                                    <small class="text-muted">Now</small>

                                                </span>
                                                <span class="d-flex">
                                                    <span class="avatar avatar-xs mr-2">
                                                        <span class="avatar-title rounded-circle bg-light">
                                                            <i class="material-icons font-size-16pt text-warning">chat</i>
                                                        </span>
                                                    </span>
                                                    <span class="flex d-flex flex-column">

                                                        <span class="text-black-70">

                                                        <?php if (isset($_SESSION['roles'])) {
                                                            echo "User Role: <br>";
                                                            foreach ($_SESSION['roles'] as $role) {
                                                                echo$role . "<br>";



                                                                
                                                            }}
                                                            ?>
                                                        </span>
                                                    </span>
                                                </span>
                                            </a>

                                          

                                        </div>
                                    </div>