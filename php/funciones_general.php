<?php
function getBrowser(){

$user_agent=$_SERVER['HTTP_USER_AGENT'];

if(strpos($user_agent, 'MSIE') !== FALSE)
   return false; //'Internet explorer';
 elseif(strpos($user_agent, 'Edge') !== FALSE) //Microsoft Edge
   return 'Microsoft Edge';
 elseif(strpos($user_agent, 'Trident') !== FALSE) //IE 11
    return false; //'Internet explorer';
 elseif(strpos($user_agent, 'Opera Mini') !== FALSE)
   return "Opera Mini";
 elseif(strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR') !== FALSE)
   return "Opera";
 elseif(strpos($user_agent, 'Firefox') !== FALSE)
   return 'Mozilla Firefox';
 elseif(strpos($user_agent, 'Chrome') !== FALSE)
   return 'Google Chrome';
 elseif(strpos($user_agent, 'Safari') !== FALSE)
   return "Safari";
 else
   return true; //'No hemos podido detectar su navegador';

}

function obtener_ruta_menu($menu, $accion) {

    global $db;
    
        //EVITAR ALGUNA INYECCION SQL -- OJO GUARDAR ESTE ATAQUE 
    if ($_GET) {
          
           foreach ($_GET as $key => $v) {
            $cadenas_no =array('DELETE','INSERT','UPDATE','SELECT');
            $sql1 = $v;
            $sql = strtoupper($sql1);
            $ver = true;
            foreach ($cadenas_no as $key => $v) {
                $buscar   = $v;
                $pos = strpos($sql, $buscar);                 
                 if ($pos !== false) {
                   return array("error" => true, "msg" => "Acceso denegado  !!!!");
                 }
            }
          }
    }

    $sql = " SELECT CONCAT_WS('/', m.ruta, a.archivo) as ruta, m.acceso
    FROM admin_accion a, admin_menu m
    WHERE a.menu=m.menu AND a.menu='$menu' AND a.accion='$accion'";


    if ($rw = $db->select_row($sql)) {


        if (in_array($rw['acceso'], $_SESSION['acceso_menu'])) {


            if ($rw['acceso'] == "7") {

                $sql = "SELECT p.menu FROM admin_permiso_menu p, admin_usuario u 
                WHERE u.rol=p.rol AND p.menu='$menu' AND p.rol='$_SESSION[usuario_rol]'";
                $p = $db->select_one($sql);

                if (!$p) {
                    return array("error" => true, "msg" => "Acceso denegado  !!!!");
                }

                //Verificar si se tiene permiso para la acción
                 $sql = "SELECT id,requiere_permiso FROM admin_accion WHERE menu='$menu' and accion='$accion'";
                 $datos_accion = $db->select_row($sql);         

                 $idA = $datos_accion['id'];
                 $requiere_permiso = trim(strtoupper($datos_accion['requiere_permiso']));

                if ($datos_accion['requiere_permiso']=='S') {                                  

                    $sql = "SELECT id FROM admin_permiso_accion WHERE rol='$_SESSION[usuario_rol]' and accion='$idA'";
                    $permiso_accion = $db->select_one($sql);
                    
                    if (!$permiso_accion) {
                        return array("error" => true, "msg" => "Acceso denegado (1) !!!!");
                    }
                }


            }

            $r = str_replace("//", "/", $rw['ruta']);
            if (file_exists($r)) {  //Verificar que exista la ruta.
                return array("error" => false, "ruta" => $r);
            } else {
                return array("error" => true, "msg" => "Ruta no valida !!!");
            }

        }else{
            // ERROR DE SESIÓN
            return array("error" => true, "msg" => "Acceso denegado (2) !!!");
        }

    } else { //No se encontro la combinación en la base de datos
        return array("error" => true, "msg" => "Vinculo no valido !!!");
    }

}


function insertar_log($archivo,$tipo,$mensaje)
{
  global $db;
  $insertar = array();
  $insertar['id_persona'] = $_SESSION['persona_id'];
  $insertar['archivo'] = $archivo;
  $insertar['tipo'] = $tipo;
  $insertar['mensaje'] = $mensaje;
  $insertar['menu'] = MENU;
  $insertar['accion'] = ACCION;

  if (isset($_SESSION['nombre_usuario'])) {
       $sql_log = $db->get_insert("admin_log",$insertar);
       if (ACCION=='ver' or ACCION=='set_token') {
          //ESPACION PARA POSIBLE ANALISIS
       }else{
          $db->query($sql_log);
       }
       
  }
}


function Generar_clave_aleatoria(){
    $ranStr = md5(microtime()); 
    $ranStr = substr($ranStr, 0, 8);
    return strtoupper($ranStr);
}

function returnJson($res=false,$datos=array()){
    echo json_encode(array('res'=>$res,'dataObj'=>$datos)); 
}


function insertar_bitacora($tipo,$nuevos,$mensaje,$viejos=false){ 
    global $db;
      
    $insertar = array();    
    $insertar['id_tipo_bitacora'] = $tipo;
    $insertar['datos_anteriores'] = serialize($viejos);
    $insertar['datos_insertados'] = serialize($nuevos);
    $insertar['observacion'] = $mensaje;
    $insertar['menu'] = MENU;
    $insertar['id_usuario'] = $_SESSION['persona_id'];

    $db->insert("admin_bitacoras_sistema",$insertar);
}

function escape_string($v){
    global $db;
    return $db->escape_string($v);
 
}

function is_login(){  
   if (isset($_SESSION['nombre_usuario'])) {return true;}
   else{ return false;}
}

function pre($array=array()){
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

function dato_basico($id,$tabla,$n=false){
    global $db;
    if ($n) {
       $rw = @$db->select_row("SELECT $n FROM $tabla WHERE id=".$id);
       return $rw[$n];
    }else{
       $rw = @$db->select_row("SELECT nombre FROM $tabla WHERE id=".$id);
       return $rw['nombre']; 
    }    
}

function dato_basico_codigo($id,$tabla,$n=false){
    global $db;
    if ($n) {
       $rw = @$db->select_row("SELECT $n FROM $tabla WHERE codigo=".$id);
       return $rw[$n]; 
    }else{
       $rw = @$db->select_row("SELECT nombre FROM $tabla WHERE codigo=".$id);
       return $rw['nombre']; 
    }   
}

function nombre_usuario_cedula($id,$tabla='persona'){
    global $db;
    $rw = @$db->select_row("SELECT CONCAT_WS('',p.nombre1,' ',p.apellido1,' ',p.apellido2, ' [',p.identifica,']') as persona_nombre FROM $tabla p WHERE id=".$id);
    return $rw['persona_nombre'];
}

function nombre_usuario($id,$tabla='persona'){
    global $db;
    $rw = @$db->select_row("SELECT CONCAT_WS('',p.nombre1,' ',p.apellido1,' ',p.apellido2) as persona_nombre FROM $tabla p WHERE id=".$id);
    return $rw['persona_nombre'];
}

function clave($clave, $extra1 = "6y", $extra2 = "6bvj6") {
    if (CRYPT_SHA512 == 1) {
        $salt1 = "{$extra1}bP5MrcqS7wsMXUPJ";
        $salt2 = "{$extra1}QvMQcHJXNhCtAmvy";
        $v = crypt($clave, '$6$rounds=6000$' . $salt1 . '$');
        return md5(sha1($v) . md5($salt2) . sha1($extra2));
    }
}

function llenar_comboSQL($sql, $blanco = false, $predeterminado = "") {
    global $dbsql;
    if ($blanco == true)
        echo '<option value="">Seleccione...</option>';
    $rs = @$dbsql->select_all($sql);
    foreach ($rs as $key => $rw) {
      $sel = (trim($rw['codigo']) == $predeterminado) ? "selected='selected'" : "";
      echo "<option $sel value='$rw[codigo]'>$rw[nombre]</option>";
    }
}

function llenar_combo($sql, $blanco = false, $predeterminado = "") {
    global $db;
    if ($blanco == true)
        echo '<option value="">Seleccione...</option>';
    $rs = @$db->query($sql);
    while ($rw = @$db->fetch_row($rs)) {
        $sel = (trim($rw[0]) == $predeterminado) ? "selected='selected'" : "";
        echo "<option $sel value='$rw[0]'>$rw[1]</option>";
    }
}


function get_llenar_combo($sql, $blanco = false, $predeterminado = "") {
    global $db;
    $op="";
    if ($blanco == true)
        $op.='<option value="" style="display: none">Seleccione...</option>';
    $rs = @$db->query($sql);
    while ($rw = @$db->fetch_row($rs)) {
        $sel = (trim($rw[0]) == $predeterminado) ? "selected='selected'" : "";
        $op.="<option $sel value='$rw[0]'>$rw[1]</option>";
    }
    return $op;
}


// encryp

function llenar_combo_encrypt($sql, $blanco = false, $predeterminado = "") {
    global $db;
    if ($blanco == true)
        echo '<option value="" style="display: none">Seleccione...</option>';
    $rs = @$db->query($sql);
    while ($rw = @$db->fetch_row($rs)) {
        $sel = (trim($rw[0]) == $predeterminado) ? "selected='selected'" : "";
        $rw[0] = encriptar_id($rw[0]);
        echo "<option $sel value='$rw[0]'>$rw[1]</option>";
    }
}

function get_llenar_combo_encrypt($sql, $blanco = false, $predeterminado = "") {
    global $db;
    $op="";
    if ($blanco == true)
        $op.='<option value="" style="display: none">Seleccione...</option>';
    $rs = @$db->query($sql);
    while ($rw = @$db->fetch_row($rs)) {
        $sel = (trim($rw[0]) == $predeterminado) ? "selected='selected'" : "";
        $rw[0] = encriptar_id($rw[0]);
        $op.="<option $sel value='$rw[0]'>$rw[1]</option>";
    }
    return $op;
}

function alerta($msg) {
    ?>
       
       <?php  if(is_login()){ ?>

<div class="container h-100-vh" style="background: white">
    <div class="row align-items-md-center h-100-vh">
        <div class="col-lg-6">
            <img class="img-fluid" src="img/error.jpg" alt="image">
        </div>
        <div class="col-lg-4 offset-lg-1">
                     
                      <div class="timeline">
                               
                                <div class="timeline-item">
                                    <div>
                                        <figure class="avatar avatar-sm mr-3 bring-forward">
                                            <span class="avatar-title bg-warning-bright text-warning rounded-circle">
                                                <i class="ti-bell"></i>
                                            </span>
                                        </figure>
                                    </div>
                                    <div>
                                        <h6 class="d-flex justify-content-between mb-4">
                                            <span>
                                                <a href="#">Error al procesar solicitud
                                            </span>
                                            <span class="text-muted font-weight-normal"><?php echo date('Y-m-d') ?></span>
                                        </h6>
                                        <a href="#">
                                            <div class="mb-3 border p-3 border-radius-1">
                                               <?php echo $msg ?>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                            </div>
        </div>
    </div>
</div>

       <?php }else{  include_once("404.php") ?>
         
       <?php } ?>
      
        
              
    <?php
}


function verIP(){   

    if (isset($_SERVER["HTTP_CLIENT_IP"])){
        return $_SERVER["HTTP_CLIENT_IP"];
    }elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
    }elseif (isset($_SERVER["HTTP_X_FORWARDED"])){
        return $_SERVER["HTTP_X_FORWARDED"];
    }elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])){
        return $_SERVER["HTTP_FORWARDED_FOR"];
    }elseif (isset($_SERVER["HTTP_FORWARDED"])){
       return $_SERVER["HTTP_FORWARDED"];
    }else{
       return $_SERVER["REMOTE_ADDR"];
    }

}
function fechaesp($date){

    $dia = explode("-", $date, 3);
    $year = $dia[0];
    $month = (string)(int)$dia[1];
    $day = (string)(int)$dia[2];

    $dias = array("Domingo","Lunes","Martes","Miércoles" ,"jueves","viernes","Sábado");
    $tomadia = $dias[intval((date("w",mktime(0,0,0,$month,$day,$year))))];
    $meses = array("", "enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");  
    return $tomadia.", ".$day." de ".$meses[$month]." de ".$year;

}


function tabla_dato($sql) {
    $db = $GLOBALS['db'];

    $totales = array();

    echo '<table style=" width:100%; ; border-collapse:collapse; border: 1px solid ' . BORDE_HTML . '" border="1">';
    echo '<tr style="font-weight:bold; background-color:' . FONDO_HTML_TITULO . '">';
    $rs = $db->query($sql);
    $i = 0;
    while ($f = $db->fetch_field($rs)) {
        if ($i == 0) {
            echo "<td style='text-align:left'>" . $f->name . "</td>";
        } else {
            echo "<td style='text-align:center'>" . $f->name . "</td>";
        }

        $i++;
    }
    echo "</tr>";


    while ($rw = $db->fetch_row($rs)) {
        $color = ($color == FONDO_HTML) ? "" : FONDO_HTML;
        echo "<tr style='background-color: $color'>";
        foreach ($rw as $i => $v) {

            if ($i == 0) {
                //echo "<td style='font-weight:bold'>".$rw[$i]."</td>";
                echo "<td>" . $rw[$i] . "</td>";
            } else {
                echo "<td style='text-align:center'>" . $rw[$i] . "</td>";
            }
            $totales[$i] += $rw[$i];
        }

        echo "</tr>";
    }


    //TOTALES
    echo '<tr style="font-weight:bold; background-color:' . FONDO_HTML_TITULO . '">';
    $rs = $db->query($sql);
    $i = 0;
    while ($f = $db->fetch_field($rs)) {
        if ($i == 0) {
            echo "<td style='text-align:lef; font-weight:bold' >TOTALES</td>";
        } else {
            echo "<td style='text-align:center'>" . $totales[$i] . "</td>";
        }

        $i++;
    }
    echo "</tr>";



    echo "</table>";
}


function registrar_bitacora($datos,$accion)
{     
     global $db;

     $insertar = array();
     $insertar['var_datos']  = $db->encrypt($datos);
     $insertar['usuario'] = $_SESSION['usuario'];
     $insertar['tb_accion_id']= $accion;

     $db->insert('tb_bitacora_acciones',$insertar);
}


function decifrar_id($cadena){
   $e1 = explode("@",$cadena);
   $e2 = explode("!",$e1[1]);
   return $e2[0];
}


function cifrar_id($id){

   $letras = array('A','C','M','Z','Y','q','c','a','m','F');

   if ($id>9) {
      $numero1 = rand(1,99);
      $numero2 = rand(1,99);
   }else{
      $numero1 = rand(1,9);
      $numero2 = rand(1,9);
   }  
   $numero3 = rand(0,9);
   $numero4 = rand(1,99);
   $numero5 = rand(1,9);
   return $numero1.'@'.$id.'!'.$numero2.$letras[$numero3].$numero1.$numero4.'|'.$numero5.$numero2.'#'.($numero3+1);

}


function eliminar_tildes($cadena){
 
    //Codificamos la cadena en formato utf8 en caso de que nos de errores
    //$cadena = utf8_encode($cadena);
 
    //Ahora reemplazamos las letras
    $cadena = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $cadena
    );
 
    $cadena = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $cadena );
 
    $cadena = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $cadena );
 
    $cadena = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $cadena );
 
    $cadena = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $cadena );
 
    $cadena = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C'),
        $cadena
    );
 
    return $cadena;
}


function validar_tildes($cadena){
 
    //Codificamos la cadena en formato utf8 en caso de que nos de errores
    //$cadena = utf8_encode($cadena);

    $cadena  = strtoupper($cadena);

    //Ahora reemplazamos las letras
    $cadena = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('Á', 'Á', 'Á', 'Á', 'Á', 'Á', 'Á', 'Á', 'Á'),
        $cadena
    );
 
    $cadena = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('É', 'É', 'É', 'É', 'É', 'É', 'É', 'É'),
        $cadena );
 
    $cadena = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('Í', 'Í', 'Í', 'Í', 'Í', 'Í', 'Í', 'Í'),
        $cadena );
 
    $cadena = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('Ó', 'Ó', 'Ó', 'Ó', 'Ó', 'Ó', 'Ó', 'Ó'),
        $cadena );
 
    $cadena = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('Ú', 'Ú', 'Ú', 'Ú', 'Ú', 'Ú', 'Ú', 'Ú'),
        $cadena );
 
    $cadena = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('Ñ', 'Ñ', 'C', 'C'),
        $cadena
    );
 
    return trim($cadena);
}


function validar_documento($documento,$tabla){
    global $db;
    if (empty($documento)) { return true; }
    $rw = @$db->select_row("SELECT id FROM $tabla WHERE identifica='$documento'");
    if ($rw) { return true; }
    return false;
}



function validar_correo($correo,$tabla){
    global $db;
    if (empty($correo)) { return true; }
    $rw = @$db->select_row("SELECT id FROM $tabla WHERE correo='$correo'");
    if ($rw) { return true; }
    return false;
}

function urlsafe_b64encode($string){
  $data = base64_encode($string);
  $data = str_replace(array('+','/','='),array('-','_','.'),$data);
  return $data;
}
function urlsafe_b64decode($string){
  $data = str_replace(array('-','_','.'),array('+','/','='),$string);
  $mod4 = strlen($data) % 4;
  if ($mod4) {
    $data .= substr('====', $mod4);
  }
  return base64_decode($data);
}


function url_encode($string,$clave){
  return urlencode(utf8_encode($string));
}
   
function url_decode($string){
  return utf8_decode(urldecode($string));
}
    

function get_token($data,$pass){
  return  AES_encrypt($data,$pass);
}

function encriptar_id($string){
  return ($string*TOKEN_ID);
}

function desencriptar_id($string){
  return ($string/TOKEN_ID);
}

function AES_encrypt($data, $password){
          $salt = openssl_random_pseudo_bytes(8);
          $salted = '';
          $dx = '';

          // Salt the key(32) and iv(16) = 48
          while (strlen($salted) < 48) {

              $dx = md5($dx.$password.$salt, true);
              $salted .= $dx;
          }

          $key = substr($salted, 0, 32);
          $iv  = substr($salted, 32, 16);

          $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

          return base64_encode('Salted__' . $salt . $encryptedData);
}

function AES_decrypt($data, $password){
          $data = base64_decode($data);
          $salt = substr($data, 8, 8);
          $ciphertext = substr($data, 16);

          $rounds = 3;
          $data00 = $password.$salt;
          $md5Hash = array();
          $md5Hash[0] = md5($data00, true);
          $result = $md5Hash[0];

          for ($i = 1; $i < $rounds; $i++) {
              $md5Hash[$i] = md5($md5Hash[$i - 1].$data00, true);
              $result .= $md5Hash[$i];
          }

          $key = substr($result, 0, 32);
          $iv  = substr($result, 32, 16);

          return openssl_decrypt($ciphertext, 'aes-256-cbc', $key, true, $iv);
}

function wrapForOpenSSL($data){
          return chunk_split($data, 64);
}

function fechaesp_hora($date){
    $dia = explode("-", $date, 3);
    $year = $dia[0];
    $hora = explode(" ",$date);
    $month = (string)(int)$dia[1];
    $day = (string)(int)$dia[2];

    $dias = array("Domingo","Lunes","Martes","Miércoles" ,"jueves","viernes","Sábado");
    $tomadia = $dias[intval((date("w",mktime(0,0,0,$month,$day,$year))))];
    $meses = array("", "enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");  
    return $tomadia.", ".$day." de ".$meses[$month]." de ".$year.'  a las :'.$hora[1];

}



function cifrar_texto($string)
{
   $pass = '12345';
   $method = 'aes256';
   return openssl_encrypt($string, $method, $pass);
}


function decifrar_texto($string)
{
   $pass = '12345';
   $method = 'aes256';
   return openssl_decrypt($string, $method, $pass);
}

function limpiarTexto($texto){
      $t = str_replace('\n','<br/>',$texto);
      $t = str_replace('\r','<br/>',$t);
      $t = str_replace('\r\n', "",$t);
      return $t;
}
function quitarBR($texto){
      $t = str_replace('<br/>',PHP_EOL,$texto);
      return $t;
}

function quitar_cosas_raras($texto){
      $t = str_replace('\n',PHP_EOL,$texto);
      $t = str_replace('\r',PHP_EOL,$t);
      $t = str_replace('\r\n', "",$t);
      return $t;
}

function limpiarString($texto){
     $texto = preg_replace("/[\r\n|\n|\r]+/", PHP_EOL,$texto);
     $texto = str_replace('\r\n', "",$texto); 
     $texto = trim($texto);   

     $texto = str_replace('\n',PHP_EOL,$texto);
     $texto = str_replace('\r',PHP_EOL,$texto);
     $texto = str_replace("'", "\'",$texto);
     $texto = str_replace("\\"," ", $texto);  
     return $texto; 
}


function validar_fecha($fecha){
  if ($fecha=='1969-12-31'  or $fecha=='0000-00-00' or $fecha==''){
    return 0;
  }else{
    return strtotime($fecha);
  }
}

function formatear_fecha($fecha){
  if ($fecha=='1969-12-31'  or $fecha=='0000-00-00' or $fecha==''){
    return "";
  }else{
    return $fecha;
  }
}

function getDiasCalendario($fechainicio, $fechafin, $diasferiados = array()) {
    
    if ($fechainicio==0 or $fechafin==0) {  return 0; }

    $datetime1 = new DateTime($fechainicio);
    $datetime2 = new DateTime($fechafin);
    $interval = $datetime1->diff($datetime2);

    return  ($interval->days)+1;
}


function getDiasHabiles($fechainicio, $fechafin, $diasferiados = array()) {

        global $db;    
        if (!$diasferiados) {         
           $info = @$db->select_all("SELECT * FROM control_terminos.festivos WHERE fecha >='$fechainicio' and fecha <='$fechafin'");
           foreach ($info as $key => $v) {
               $diasferiados[] = $v['fecha'];
           }         
        }
        $fechainicio = strtotime($fechainicio);
        $fechafin = strtotime($fechafin);
        // Incremento en 1 dia
        $diainc = 24*60*60;
       // Arreglo de dias habiles, inicianlizacion
        $diashabiles = array();
        // Se recorre desde la fecha de inicio a la fecha fin, incrementando en 1 dia
        for ($midia = $fechainicio; $midia <= $fechafin; $midia += $diainc) {
                // Si el dia indicado, no es sabado o domingo es habil
                if (!in_array(date('N', $midia), array(6,7))) { // DOC: http://www.php.net/manual/es/function.date.php
                        // Si no es un dia feriado entonces es habil
                        if (!in_array(date('Y-m-d', $midia), $diasferiados)) {
                                array_push($diashabiles, date('Y-m-d', $midia));
                        }
                }
        }
        return $diashabiles;

}

function get_favoritos(){
  
  global $db;
  $sql = "SELECT am.nombre, am.menu, (SELECT mp.nombre FROM admin_menu mp WHERE mp.nombre=am.padre ) as padre 
  FROM admin_favoritos af, admin_menu am WHERE af.id_menu=am.id and id_persona='$_SESSION[persona_id]'";
  $menus = $db->select_all($sql);

    $li='<li class="nav-item">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Link Favoritos</a>
                <div class="dropdown-menu">';
                   foreach ($menus as $key => $v) {
                    if ($v['padre']) {
                      $v['nombre'] = $v['padre'].' / '.$v['nombre'];
                    }
                     $li.='<a href="'.$v[menu].'" class="dropdown-item">'.$v[nombre].'</a>';
                   }
                $li.='</div>
          </li>';

    return $li;
}

function is_superadmin()
{
  $roles = array(4);
  if (in_array($_SESSION['usuario_rol'], $roles)) {
    return true;
  }
  return false;
}

function get_lia_admin(){
  
  $li='';
 if (is_superadmin()) {
   $li='<li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Asistente Rapido</a>
                <div class="dropdown-menu dropdown-menu-big">
                    <div class="p-3">
                        <div class="row row-xs">
                            <div class="col-6">
                                <a href="crear-formulario-crud">
                                    <div class="p-3 border-radius-1 border text-center mb-3">
                                        <i class="width-23 height-23" data-feather="columns"></i>
                                        <div class="mt-2">Crear Formulario CRUD</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="crear-formulario-libre">
                                    <div class="p-3 border-radius-1 border text-center mb-3">
                                        <i class="width-23 height-23" data-feather="columns"></i>
                                        <div class="mt-2">Crear Formulario Libre</div>
                                    </div>
                                </a>
                            </div>

                             <div class="col-6">
                                <a href="crear-reporte">
                                    <div class="p-3 border-radius-1 border text-center mb-3">
                                        <i class="width-23 height-23" data-feather="columns"></i>
                                        <div class="mt-2">Crear Formulario de Reporte</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="permisos-por-rol">
                                    <div class="p-3 border-radius-1 border text-center mb-3">
                                        <i class="width-23 height-23" data-feather="users"></i>
                                        <div class="mt-2">Actualizar Permisos por Rol</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="gestion-menu">
                                    <div class="p-3 border-radius-1 border text-center">
                                        <i class="width-23 height-23" data-feather="layout"></i>
                                        <div class="mt-2">Gestión del Menú</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="usuarios">
                                    <div class="p-3 border-radius-1 border text-center">
                                        <i class="width-23 height-23" data-feather="user"></i>
                                        <div class="mt-2">Gestión de Usuarios</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>';

 }
  
      return $li;
}


function modal($id,$html)
{
  

 $modal='<div class="modal fade" id="'.$id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          
         <div class="modal-body">'.$html.'</div>
        
         <div class="modal-footer">

            <div class="dlg-acciones">
               <button type="button" data-dismiss="modal" class="btn btn-danger btn-icon-text">
                 <i class="icon-close"></i>Cancelar
               </button>

              <button type="button" id="btn_aceptar" class="btn btn-success btn-icon-text">
                 <i class="icon-plus"></i> Aceptar
              </button>
            </div>

        </div>

      </div>
    </div>
  </div>';
  return $modal;
}

function desencriptar_post($data,$hash)
{
   $formateo_post =array();
   foreach ($data as $k => $v) {
      $valor = AES_decrypt($v, $hash);
      if (!$valor) {
        $valor=$v;
      }
      $k = AES_decrypt($k,$hash);
      $formateo_post[$k]=$valor;
    } 
    return $formateo_post;
}
include_once("funciones_general_calidad.php");
?>