<?php
include_once 'https_redirect.php';
//include_once 'https_redirect.php';
session_start();
include_once 'api/config/database_app.php';
date_default_timezone_set('UTC');
include_once "api/config/variables.php";


if(!isset($_SESSION['app_user_token']) || empty($_SESSION['app_user_token'])) {
    header("Location: app_login.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
       
    if(!empty($_POST["opcion"]) || isset($_POST["opcion"]))
    {
        $opcion=$_POST["opcion"];
    
    } else {$opcion="home";}
} else {

    if(!empty($_GET["opcion"]) || isset($_GET["opcion"]))
    {$opcion=$_GET["opcion"];} else {$opcion="home";}
}


include_once "api/config/actualizar_token.php";
	

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Base de Dades - Practica 2</title>

    <!-- Prevent the demo from appearing in search engines (REMOVE THIS) -->
    <meta name="robots" content="noindex">

    <!-- Perfect Scrollbar -->
    <link type="text/css" href="assets/vendor/perfect-scrollbar.css" rel="stylesheet">

    <!-- Material Design Icons -->
    <link type="text/css" href="assets/css/material-icons.css" rel="stylesheet">
    <link type="text/css" href="assets/css/material-icons.rtl.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link type="text/css" href="assets/css/fontawesome.css" rel="stylesheet">
    <link type="text/css" href="assets/css/fontawesome.rtl.css" rel="stylesheet">

    <!-- App CSS -->
    <link type="text/css" href="assets/css/app.css" rel="stylesheet">
    <link type="text/css" href="assets/css/app.rtl.css" rel="stylesheet">

    <!-- App HIGCHARTS -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/stock.js"></script> <!-- Necesario para velas -->
    <script src="https://code.highcharts.com/highcharts-more.js"></script> <!-- Para gráficos de rango y burbujas -->
    <script src="https://code.highcharts.com/modules/histogram-bellcurve.js"></script> <!-- Para el histograma -->
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>







</head>

<body class=" fixed-layout">

    <div class="preloader">
        <div class="sk-double-bounce">
            <div class="sk-child sk-double-bounce1"></div>
            <div class="sk-child sk-double-bounce2"></div>
        </div>
    </div>

    <!-- Header Layout -->
    <div class="mdk-header-layout js-mdk-header-layout">

        <!-- Header -->

        <div id="header" class="mdk-header bg-dark js-mdk-header m-0" data-fixed data-effects="waterfall">
            <div class="mdk-header__content">

                <!-- Navbar -->
                <nav id="default-navbar" class="navbar navbar-expand navbar-dark bg-primary m-0">
                    <div class="container">
                        <!-- Toggle sidebar -->
                        <button class="navbar-toggler d-block" data-toggle="sidebar" type="button">
                            <span class="material-icons">menu</span>
                        </button>

                        <!-- Brand -->
                        <a href="http://localhost/practica_bdds" class="navbar-brand">
                            <img src="assets/images/logos_apps/logo.png" width="30%" class="mr-2" alt="TOTCLOUD" /> </a>
                            <a href="index.php" class="navbar-brand">  <span class="d-none d-xs-md-block"><?php echo $app_titulo_principal ?></span></a>
                       

                        <!-- Buscar 
                        <form class="search-form d-none d-md-flex">
                            <input type="text" class="form-control" placeholder="Search">
                            <button class="btn" type="button"><i class="material-icons font-size-24pt">search</i></button>
                        </form>
                         // END Search -->

                        <div class="flex"></div>

                         <!-- Menu -->
                         <ul class="nav navbar-nav flex-nowrap d-none d-lg-flex">
                            <li class="nav-item">
                            <a class="nav-link" href=""><i class="material-icons">file_download</i>Readme</a>
                            </li>
    
                        </ul>     

                        <!-- Menu -->
                        <ul class="nav navbar-nav flex-nowrap">

                       
                            <!-- Alertas dropdown -->
                            <li class="nav-item dropdown dropdown-notifications dropdown-menu-sm-full">
                                <button class="nav-link btn-flush dropdown-toggle" type="button" data-toggle="dropdown" data-dropdown-disable-document-scroll data-caret="false">
                                    <i class="material-icons">notifications</i>
                                    <span class="badge badge-notifications badge-danger">0</span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                <?php   include ('api/app_alertas_home_vacio.php'); ?>
                                </div>
                            </li>
                            <!-- // END Notifications dropdown -->

                            <!-- User dropdown -->
                            <li class="nav-item dropdown ml-1 ml-md-3">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"><img src="assets/images/logos_apps/icon_user.png" alt="Avatar" class="rounded-circle" width="40">&nbsp;<small><?php echo $firstname_user." ".$lastname_user ?></small> <?php if ($es_admin==1){ ?><img src="assets/images/logos_apps/icon_admin.png" alt="Avatar" class="rounded-circle" width="40"><?php } ?></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                
                                    <a class="dropdown-item" href="app_logout.php">
                                    <i class="material-icons">lock</i> Logout
                                    </a>
                                </div>
                            </li>
                            <!-- // END User dropdown -->

                        </ul>
                        <!-- // END Menu -->
                    </div>
                </nav>
                <!-- // END Navbar -->

            </div>
        </div>

        <!-- // END Header -->

        <!-- Header Layout Content -->
        <div class="mdk-header-layout__content d-flex flex-column">

            <div class="page__header">
                <div class="navbar bg-dark navbar-dark navbar-expand-sm d-none2 d-md-flex2">
                    <div class="container">

                        <div class="navbar-collapse collapse" id="navbarsExample03">
                        
                        <!-- MENU -->
                        <?php   include ('api/app_menu_home.php'); ?>
                        </div>

                        <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarsExample03" type="button">
                            <span class="material-icons">menu</span>
                        </button>

                    </div>
                </div>
            </div>

            <?php if ($opcion=="home"){?>
            <div class="bg-primary mdk-box js-mdk-box mb-0" style="height: 192px;" data-effects="parallax-background blend-background">
                    <div class="mdk-box__bg">
                        <div class="mdk-box__bg-front" style="background-image: url(assets/images/cloud_3.jpg); background-position: center;"></div>
                    </div>
                </div>
                <div class="container page__container d-flex align-items-end position-relative mb-4">
                    <div class="avatar avatar-xxl position-absolute bottom-0 left-0 right-0">
                        <img src="assets/images/logos_apps/logo_avatar.png"" alt="avatar" class="avatar-img rounded-circle border-3">
                    </div>
                    <ul class="nav nav-tabs-links flex" style="margin-left: 265px;">
                        <li class="nav-item">
                            <a href="" class="nav-link active"></a>
                        </li>

                    </ul>
                </div>
                <?php }?>
            <div class="page ">

                <div class="container page__container">


                   <?php if ($opcion=="home"){
                         include ('api/app_home_get.php'); 
                    }?>

                    <?php if ($opcion=="users"){
                        include ('api/app_users_home.php'); 
                    }?>

                    <?php if ($opcion=="roles"){
                         include ('api/app_users_roles_home.php'); 
                    }?>                   

                    <?php if ($opcion=="users_roles"){
                        include ('api/app_users_roles_assign_home.php'); 
                    }?>    

                <?php if ($opcion=="paas"){
                        include ('api/app_paas_home.php'); 
                    }?>    
                 
                </div>

                <div class="container page__container">
                    <div class="footer">
                        <?php echo $app_titulo_secundario ?> - <a href="https://uib.es">Universitat de les Illes Balears</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- // END Header Layout Content -->

    </div>
    <!-- // END Header Layout -->




    <!-- // Incluimos Menu Lateral -->
    <?php   include ('api/app_menu_lateral_home.php'); ?>

    <!-- jQuery -->
    <script src="assets/vendor/jquery.min.js"></script>

    <!-- Bootstrap -->
    <script src="assets/vendor/popper.min.js"></script>
    <script src="assets/vendor/bootstrap.min.js"></script>

    <!-- Perfect Scrollbar -->
    <script src="assets/vendor/perfect-scrollbar.min.js"></script>

    <!-- MDK -->
    <script src="assets/vendor/dom-factory.js"></script>
    <script src="assets/vendor/material-design-kit.js"></script>

    <!-- App JS -->
    <script src="assets/js/app.js"></script>

    <!-- Highlight.js -->
    <script src="assets/js/hljs.js"></script>

    <!-- App Settings (safe to remove) -->
    <script src="assets/js/app-settings.js"></script>

   <!-- List.js -->
   <script src="assets/vendor/list.min.js"></script>
    <script src="assets/js/list.js"></script>

    <!-- Sweet Alert -->
    <script src="assets/vendor/sweetalert.min.js"></script>
    <script src="assets/js/sweetalert.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




    <?php if ($opcion=="users") { 
        include ('api/app_footer_users_home.php'); }
    ?>
      

   <?php if ($opcion=="roles") { 
       include ('api/app_footer_users_roles_home.php'); }
   ?>

<?php if ($opcion=="users_roles"){
         include ('api/app_footer_users_roles_assign_home.php'); 
        }?>


</body>

</html>