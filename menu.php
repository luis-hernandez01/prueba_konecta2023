<?php
//$t1= microtime(true); 
$menu = NULL;
$acceso = "'" . implode("','", $_SESSION['acceso_menu']) . "'";
$_SESSION['menu_padre_hijos']=array();
$_SESSION['menu_padre']=array();

$sql = "SELECT 
                m.*,
                (SELECT COUNT(*) FROM admin_menu WHERE padre=m.menu) as hijos,
                (SELECT 'S'FROM admin_permiso_menu p, admin_usuario u 
                        WHERE u.rol=p.rol AND p.menu=m.menu AND u.persona_id='$_SESSION[persona_id]') as disponible
        FROM admin_menu m
        WHERE m.visible='S' AND m.acceso IN ($acceso)
        ORDER BY m.orden,m.nombre";

$data_menu = $db->select_all($sql);
$menu = formatear_menu($data_menu);
$_SESSION['menu_padre'] = menu_padre($menu);
generarMenu($menu);
?>

<?php


function formatear_menu($menu){
   
    foreach ($menu as $rw) {                  
        if ($rw['acceso'] == "7" && $rw['disponible'] != "S") { /* NO INGRESA */}else{
            if (!$rw['padre']) { $padres_globlales[]=$rw; }else{
                $_SESSION['menu_padre_hijos'][$rw['padre']][$rw['menu']]=$rw;
            }
        } 
    }

    return $padres_globlales;
}


function menu_padre($menu){
  
  $menu_padres= "";
  foreach ($menu as $mk => $rw) {
     
     if ($rw['hijos'] == 0) {
          $href = WEB_ROOT . $rw['menu'];
      }else{
        $href = "#";
      }

     if ($rw['hijos'] > 0){
        
        $menu_padres.='<li>
          <a href="'.$href.'" class="menur menup-'.$rw[menu].'" target="'.$rw[target].'" data-toggle="tooltip" data-placement="right" title="'.$rw[nombre].'"
              data-nav-target="#menu-'.$rw[menu].'">

              <button type="button" class="btn btn-light btn-badge" style="background: transparent;border: none;color:white">
                <i class='.$rw[icono].'></i> <span class="badge badge-warning">'.$rw['hijos'].'</span>
             </button>
          </a>
        </li>';

     }else{
        $menu_padres.='<li>
          <a href="'.$href.'" class="menur menup-'.$rw[menu].'" target="'.$rw[target].'" data-toggle="tooltip" data-placement="right" title="'.$rw[nombre].'"">
              <i class='.$rw[icono].'></i>
          </a>
        </li>';
     }
    
  }

  return $menu_padres;
}



function generarMenu($menu) {  

  $li = "";
  foreach ($menu as $key => $rw) {
   
    $hijos = $_SESSION['menu_padre_hijos'][$rw['menu']];

    if ($hijos>0){
          $li.='<div  id="menu-'.$rw[menu].'"> ';
           $li.='<ul><li class="navigation-divider">'.$rw[nombre].'</li>';
           foreach ($hijos as $pp => $ph) {  // pre($ph);            
               $hijos = $_SESSION['menu_padre_hijos'][$ph['menu']];
                if ($hijos == 0) {
                  $href = WEB_ROOT . $ph['menu'];                  
                  $li.='<li><a class="menur menup-'.$ph[menu].'" href="'.$href.'" target="'.$ph[target].'" data-toggle="tooltip" title="'.$ph[descripcion].'" >'.$ph[nombre].'</a></li>';
                }else{
                  $href = "#";
                  $li.='<li><a class="menur menup-'.$ph[menu].'" href="'.$href.'" target="'.$ph[target].'" data-toggle="tooltip" title="'.$ph[descripcion].'" >'.$ph[nombre].'</a>';
                      $li.=crear_hijos($ph); 
                      $li.='</li>';     
                }                                 
           }// FIN FOR EACH   
           $li.='</ul>';                
         $li.='</div>';
     }      
  }

  $_SESSION['menu_hijos']=$li;
  
}


function crear_hijos($rw){
   
   $hijos = $_SESSION['menu_padre_hijos'][$rw['menu']];
   $li="";
   $li.='<ul>';
    foreach ($hijos as $pp => $ph) {              
           
        $shijos = $_SESSION['menu_padre_hijos'][$ph['menu']];
        if ($shijos == 0) {
          $href = WEB_ROOT . $ph['menu'];
          $li.='<li><a class="menur menup-'.$ph[menu].'" href="'.$href.'" target="'.$ph[target].'" data-toggle="tooltip" title="'.$ph[descripcion].'" >'.$ph[nombre].'</a></li>';
        }else{
          $href = "#";
       //   $li.='<ul>';
          $li.='<li><a class="menur menup-'.$ph[menu].'" href="'.$href.'" target="'.$ph[target].'" data-toggle="tooltip" title="'.$ph[descripcion].'" >'.$ph[nombre].'</a></li>';
                  $li.=crear_hijos($ph);       
         // $li.='</ul>';
        }

     }// FIN FOR EACH
    $li.='</ul>';  
   return $li;                 
}
?>