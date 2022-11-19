<?php
if (!function_exists('is_login')) { include_once '404.php'; exit(); }
     ob_start();
      $r = obtener_ruta_menu(MENU, ACCION);
      if ($r['error'] == false) {
        require_once($r['ruta']);
      } else {
        alerta($r['msg']);
      }            
?>