<?php  include_once 'cabeza.php';  ?>
  <?php  
        if (is_login()) { 
              $r = obtener_ruta_menu(MENU, ACCION);
              if ($r['error'] == false) {
                require_once($r['ruta']);
              }else {
                insertar_log('404.php',2,$r['msg']);
                alerta($r['msg']);
              }
          }else{ 
              insertar_log('404.php',2,'Error 404');
              include_once '404.php';
          }                   
    ?>                         
<?php  include_once 'pie.php';  ?>
