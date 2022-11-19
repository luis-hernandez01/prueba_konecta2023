<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo (RUTA_MENU) ?></title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo WEB_ROOT ?>img/logo_empresa.jpeg"/>
    <!-- Plugin styles -->
    <link rel="stylesheet" href="<?php echo WEB_ROOT ?>plantilla/vendors/bundle.css" type="text/css">
    <!-- App styles -->
    <link rel="stylesheet" href="<?php echo WEB_ROOT ?>plantilla/assets/css/app.min.css" type="text/css">
     <!-- Datepicker -->
    <link rel="stylesheet" href="<?php echo WEB_ROOT ?>plantilla/vendors/datepicker/daterangepicker.css">
    <!-- Vmap -->
    <link rel="stylesheet" href="<?php echo WEB_ROOT ?>plantilla/vendors/vmap/jqvmap.min.css">
     <!-- Prism -->
    <link rel="stylesheet" href="<?php echo WEB_ROOT ?>plantilla/vendors/prism/prism.css" type="text/css">
        <!-- Form wizard -->
    <link rel="stylesheet" href="<?php echo WEB_ROOT ?>plantilla/vendors/form-wizard/jquery.steps.css" type="text/css">
    <!-- Tour -->
    <link rel="stylesheet" href="<?php echo WEB_ROOT ?>plantilla/vendors/enjoyhint/enjoyhint.css" type="text/css">

    <!-- begin::global scripts -->
    <script src="<?php echo WEB_ROOT ?>plantilla/vendors/bundle.js"></script>
    <script src="<?php echo WEB_ROOT ?>plantilla/vendors/form-wizard/jquery.steps.min.js"></script>
    <!-- end::global scripts -->
   

     <script type="text/javascript">   
        const BASE_URL ='<?php echo WEB_ROOT ?>inicio/';    
        const menu = "<?php echo MENU ?>";
        const web_root = "<?php echo WEB_ROOT ?>";
        const page_root = "<?php echo PAGE_ROOT ?>";
        let  TOKEN_GLOBAL;
     </script>
     
     <?php
       include_once 'style_lia.php';
       include_once 'script_lia.php';

       include_once 'menu.php';

     ?>

</head>

<body class="sticky-header  navigation-toggle-one">

<!-- begin::preloader-->
<div class="preloader">
    <img src="img/cargarXXXXX.gif" alt="">
</div>
<!-- end::preloader -->

<!-- begin::header -->
<div class="header">

    <div>
        <ul class="navbar-nav">
            <!-- begin::navigation-toggler -->
             
           
            <li class="nav-item navigation-toggler" id="hidden_menu" style="display: none">
                <a href="#" class="nav-link" title="Hide navigation">
                    <i data-feather="arrow-left"></i>
                </a>
            </li>
           
            <li class="nav-item navigation-toggler mobile-toggler">
                <a href="#" class="nav-link" title="Show navigation">
                    <i data-feather="menu"></i>
                </a>
            </li>
            <!-- end::navigation-toggler -->        
           <?php  echo get_favoritos();  ?>           
           <?php  echo get_lia_admin();  ?>
        </ul>
    </div>

    <div>
        <ul class="navbar-nav">

            <!-- begin::header search -->
            <li class="nav-item">
                <a href="#" class="nav-link" title="Search" data-toggle="dropdown">
                    <i data-feather="search"></i>
                </a>
                <div class="dropdown-menu p-2 dropdown-menu-right">
                    <form action="buscar" method="POST">
                        <div class="input-group">
                            <input type="text" name="buscar" class="form-control" placeholder="¿Que deseas buscar?">
                            <div class="input-group-prepend">
                                <button class="btn" type="button">
                                    <i data-feather="search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </li>
            <!-- end::header search -->

            <!-- begin::header minimize/maximize -->
            <li class="nav-item dropdown">
                <a href="#" class="nav-link" title="Fullscreen" data-toggle="fullscreen">
                    <i class="maximize" data-feather="maximize"></i>
                    <i class="minimize" data-feather="minimize"></i>
                </a>
            </li>
            <!-- end::header minimize/maximize -->
            <?php
              $mensajes_activos = $db->select_all("SELECT s.*, t.nombre as tipo_solicitud,g.nombre as grupo
                FROM `solicitar_documento` s,$DE_DB.grupos g, tipo_solicitud t 
                WHERE g.id=s.id_grupo and t.id=s.tipo_solicitud and s.estado=1 and s.id_responsable_actual='$_SESSION[persona_id]' GROUP BY s.tiquete ORDER BY s.fechar DESC"); 
            ?>
            <!-- begin::header messages dropdown -->
            <?php if ($mensajes_activos): ?>
             <li class="nav-item dropdown">
                <a class="btn btn-light btn-badge nav-link" data-toggle="dropdown" title="Solicitudes" style="background: white;cursor: pointer;">
                    <i data-feather="message-circle" style="color:black"></i> 
                    <span class="badge badge-warning"><?php echo count($mensajes_activos); ?></span>
                </a>

                <div class="dropdown-menu dropdown-menu-right dropdown-menu-big">
                    <div class="p-4 text-center d-flex justify-content-between"
                         data-backround-image="https://via.placeholder.com/1000X563">
                        <h6 class="mb-0">Solicitudes</h6>
                        <small class="font-size-11 opacity-7"><?php echo count($mensajes_activos); ?> Solicitudes</small>
                    </div>
                    <div>
                        <ul class="list-group list-group-flush">
                           <?php foreach ($mensajes_activos as $key => $v) { ?>
                               
                            <li style="border-bottom: 1px solid #bbb;">
                                <a href="listado-solicitudes?q=<?php echo encriptar_id($v[id]) ?>" class="list-group-item d-flex hide-show-toggler">
                                    <div>
                                        <figure class="avatar avatar-sm m-r-15">
                                            <img src="<?php echo $_SESSION['foto'] ?>"
                                                 class="rounded-circle" alt="user">
                                        </figure>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 line-height-20 d-flex justify-content-between">
                                            <?php echo $v['grupo'] ?>
                                        </p>
                                        <div class="small text-muted">
                                            <span class="mr-2"><?php echo fechaesp_hora($v['fechar']) ?></span>
                                            <br><span><?php echo $v['tipo_solicitud'] ?></span>
                                        </div>
                                    </div>
                                </a>
                            </li>

                           <?php } ?>
                          

                        </ul>
                    </div>
                    <div class="p-2 text-right">
                        <ul class="list-inline small">
                            <li class="list-inline-item">
                                <a href="listado-solicitudes">Ver todos</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
            <?php endif ?>

            <!-- end::header messages dropdown -->
            <?php
              $pre_publicacion = $db->select_all("SELECT s.*, t.nombre as tipo_solicitud,g.nombre as grupo
                FROM `solicitar_documento` s,$DE_DB.grupos g, tipo_solicitud t, pre_publicacion p 
                WHERE g.id=s.id_grupo and t.id=s.tipo_solicitud and p.id_estado=1 and p.tiquete=s.tiquete
                and p.id_persona='$_SESSION[persona_id]' GROUP BY s.tiquete ORDER BY s.fechar DESC"); 
            ?>
            <!-- begin::header messages dropdown -->
            <?php if ($pre_publicacion): ?>
            <!-- begin::header notification dropdown -->
            <li class="nav-item dropdown" style="margin-left: 2px">
                 <a class="btn btn-light btn-badge nav-link" data-toggle="dropdown" title="Prepublicación" style="background: white;cursor: pointer;">
                    <i data-feather="bell" style="color:black"></i> 
                    <span class="badge badge-danger"><?php echo count($pre_publicacion); ?></span>
                </a>

                <div class="dropdown-menu dropdown-menu-right dropdown-menu-big">
                    <div class="p-4 text-center d-flex justify-content-between"
                         data-backround-image="https://via.placeholder.com/1000X563">
                        <h6 class="mb-0">Notificaciones</h6>
                        <small class="font-size-11 opacity-7"><?php echo count($pre_publicacion); ?> Total</small>
                    </div>
                    <div>
                        <ul class="list-group list-group-flush">
                           <?php foreach ($pre_publicacion as $key => $v) { ?>
                               
                            <li style="border-bottom: 1px solid #bbb;">
                                <a href="prepublicacion?q=<?php echo encriptar_id($v[id]) ?>" class="list-group-item d-flex hide-show-toggler">
                                    <div>
                                        <figure class="avatar avatar-sm m-r-15">
                                                <span class="avatar-title bg-success-bright text-success rounded-circle">
                                                    <i class="ti-file"></i>
                                                </span>
                                        </figure>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 line-height-20 d-flex justify-content-between">
                                            <?php echo $v['grupo'] ?>
                                        </p>
                                        <span class="mr-2"><?php echo fechaesp($v['fecha_limite_respuesta']) ?></span>
                                            <br><span><?php echo $v['tipo_solicitud'] ?></span>
                                    </div>
                                </a>
                            </li>

                        <?php } ?>
                        </ul>
                    </div>
                    <div class="p-2 text-right">
                        <ul class="list-inline small">
                            <li class="list-inline-item">
                                <a href="prepublicacion">Ver todas</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
             <?php endif ?>
            <!-- end::header notification dropdown -->

            <!-- begin::user menu -->
            <li class="nav-item dropdown">
                <a href="#" class="nav-link" title="User menu" data-toggle="dropdown">
                    <i data-feather="help-circle"></i>
                </a>
            </li>
            <!-- end::user menu -->
        </ul>

        <!-- begin::mobile header toggler -->
        <ul class="navbar-nav d-flex align-items-center">
            <li class="nav-item header-toggler">
                <a href="#" class="nav-link">
                    <i data-feather="arrow-down"></i>
                </a>
            </li>
        </ul>
        <!-- end::mobile header toggler -->
    </div>

</div>
<!-- end::header -->


<!-- begin::main -->
<div id="main">

    <!-- begin::navigation -->
    <div class="navigation">

        <div class="navigation-menu-tab">
            <div>
                <div class="navigation-menu-tab-header" data-toggle="tooltip" title="<?php echo $_SESSION['nombre_usuario_c'] ?>" data-placement="right">
                    <a href="#" class="nav-link" data-toggle="dropdown" aria-expanded="false">
                        <figure class="avatar avatar-sm">
                            <img src="img/logo_empresa.jpeg" class="rounded-circle" alt="avatar">
                        </figure>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-big">
                        <div class="p-3 text-center" data-backround-image="img/logo_empresa.jpeg">
                            <figure class="avatar mb-3">
                                <img src="img/logo_empresa.jpeg" class="rounded-circle" alt="image">
                            </figure>
                            <h6 class="d-flex align-items-center justify-content-center">
                               <?php echo $_SESSION['nombre_usuario_c'] ?>
                            </h6>
                            <small><strong>Bienvenido</strong></small>
                        </div>
                        <div class="dropdown-menu-body">
                            <div class="border-bottom p-4  text-center">
                                <h6 class="font-size-11 d-flex justify-content-center">
                                    <span>Ultimo Ingreso</span>                                    
                                </h6>
                                <span><?php echo fechaesp_hora($_SESSION['ultimo_ingreso']) ?></span>
                            </div>
                            <div class="list-group list-group-flush">
                                <a href="perfil" class="list-group-item">Editar Perfil</a>
                                <a href="#" class="list-group-item" data-sidebar-target="#settings">Necesitas ayuda?</a>
                                <a href="cerrar-sesion" class="list-group-item text-danger" data-sidebar-target="#Salir">Cerrar Sesión</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            

            <div class="flex-grow-1">
                <ul>
                    <?php echo $_SESSION['menu_padre']; ?>
                </ul>
            </div>


            <div>
                <ul>
                    <li>
                        <a href="cerrar-sesion" data-toggle="tooltip" data-placement="right" title="Cerrar Sesión">
                            <i data-feather="log-out"></i>
                        </a>
                    </li>
                </ul>
            </div>


        </div>

        <!-- begin::navigation menu -->
        <div class="navigation-menu-body">

            <!-- begin::navigation-logo -->
            <div>
                <div id="navigation-logo">
                    <a href="inicio">
                        <img class="logo" src="img/logo_empresa.jpeg" alt="logo" style="margin-left: 10%;width: 80%;">
                        <img class="logo-light" src="img/logo_empresa.jpeg" alt="light logo" style="margin-left: 10%;width: 80%;">
                    </a>
                </div>
            </div>
            <!-- end::navigation-logo -->

            <div class="navigation-menu-group">
                <?php echo $_SESSION['menu_hijos']; ?>
            </div>
        </div>
        <!-- end::navigation menu -->

    </div>
    <!-- end::navigation -->

        <!-- begin::main-content -->
    <main class="main-content">

     <?php if (MENU<>'inicio') { ?>
          <!-- begin::page-header -->
        <div class="page-header">
            <div class="container-fluid d-sm-flex justify-content-between">
                <h4 id="titulo_ubicacion_frm"></h4>
                <nav aria-label="breadcrumb" id="ruta_global"></nav>
            </div>
        </div>
        <!-- end::page-header -->
    <?php } ?>   