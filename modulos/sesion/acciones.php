<?php
//error_reporting(0);
require_once("php/clase_base.php");
require_once('php/ldap.php');
class Sesion extends clase_base {

    function validar() {
         
        $hash = $_SERVER['HTTP_AUTHORIZATION'];
        $_POST['clave'] = AES_decrypt($_POST['clave'], $hash);
        $_POST['usuario'] = AES_decrypt($_POST['usuario'], $hash);

        $v = new Validation($_POST);
        $v->addRules('usuario', 'Usuario', array('required' => true, 'length' => array(1, 45)));
        $v->addRules('clave', 'Clave', array('required' => true, 'length' => array(5, 45)));
        $v->addRules('validador', 'Codigo de validacion', array('required' => true, 'length' => array(6, 7)));

        $result = $v->validate();
        if ($result['messages'] == "") {//No hay errores de validacion
            return true;
        } else {
            //Errores de validación
            /*
              $r['error'] = true;
              $r['msg'] = $result['messages'];
              $r['bad_fields'] = $result['bad_fields'];
              $r['errors'] = $result['errors'];
              echo json_encode($r);
              exit(0);

             */
            $this->denegarSesion($result['messages'], 4);
        }
    }
    private function jazmin($num,$nombre)
    {
       $datos = array();
          $datos[1] = array('imagen'=>'img/jaz/jaz.gif','mensaje'=>'Hola Señor'.$nombre.' , es un placer servirte hoy. ');
          $datos[2] = array('imagen'=>'img/jaz/1.png','mensaje'=>'Hola Señor'.$nombre.' , hoy me parece un dia frio, pero vamos trabajemos.');
          $datos[3] = array('imagen'=>'img/jaz/2.png','mensaje'=>'Hola Señor'.$nombre.' , hoy estoy un poco alegre, todo me sale bien ¿Que deseas?.');
          $datos[4] = array('imagen'=>'img/jaz/3.png','mensaje'=>'Hola Señor'.$nombre.' , hoy me encuentro algo iluminada, dime en que te puedo ayudar.');
          $datos[5] = array('imagen'=>'img/jaz/4.png','mensaje'=>'Hola Señor'.$nombre.' , hoy espero te encante mi look, dime en que te puedo ayudar. ');
       return $datos[1];
    }

    function iniciar() {
        $this->validar();
        
        if (isset($_SESSION['usuario'])) {
            $this->denegarSesion("Acceso denegado !", 3);
        }

        if ($_SESSION['cap_code'] <> strtoupper($_POST['validador']) ) {
           $r = array();
           $r['error'] = true;
           //$r['nuevo_codigo'] = $this->Generar_codigo();
           $r['msg'] = 'Codigo de validacion incorrecto';
           echo json_encode($r);
           exit();
        }
        
        if (isset($_POST['usuario']) && isset($_POST['clave'])) {
            
            $_POST['usuario'] = explode("@",$_POST['usuario'])[0];
            $_POST['usuario'] = strtoupper($_POST['usuario']);
            $_POST['usuario'] = $this->db->escape_string($_POST['usuario']);
            $DA = DA;
            //$_POST['clave'] = $this->db->escape_string($_POST['clave']);

            $sql = "SELECT * FROM  konecta.personas WHERE 1 and ( user='".$_POST['usuario']."' ) ";
            $rw = $this->db->select_row($sql);
            // DIRECTORIO ACTIVO 
            if (!$rw) {
              $r = array();
              $r['error'] = true;
              $r['alert'] = 1;
              $r['msg'] = 'Acceso denegado 7, por favor contactar al administrador y solicitar tu activación en este sistema.';
              echo json_encode($r);
              exit();
            }

            //$ldap = new ldap("LDAP://172.17.0.20/DC=anla", "anla.gov.co");
            $ldap = new ldap("ldap://anla.gov.co/DC=anla", "anla.gov.co");
            $user = trim($_POST['usuario']);
            $clave = $_POST['clave'];

            /*if ($ldap->autenticarUsuario($user,$clave)) {*/

                if ($rw) {

                $rol = $this->db->select_one("SELECT rol FROM admin_usuario WHERE persona_id='$rw[id]'");
                if ($rol == "") {
                    //Usuario y clave valido pero no tiene permiso para ingresar a este modulo
                    $insertar_rol = array();
                    $insertar_rol['persona_id']=$rw['id'];
                    $insertar_rol['rol']=8;
                    $insertar_rol['_usuario']=$rw['identificacion'];
                    $insertar_rol['_fecha']=date('Y-m-d H:i:s');

                    $this->db->insert('admin_usuario',$insertar_rol);
                    if ($this->db->error()) {
                      $this->denegarSesion("Acceso denegado!!", 2);
                    }
                    $rol=8;
                }
                
                $_SESSION['ultimo_ingreso']= $this->db->select_one("SELECT inicio FROM admin_sesion WHERE usuario='$rw[identificacion]' ORDER BY inicio DESC");
                
                $n = rand(1,5);
                $row['usuario'] =  $rw['identificacion'];//$_POST['usuario'];
                $row['session_id'] = session_id();
                $row['user_agent'] = $this->db->escape_string($_SERVER['HTTP_USER_AGENT']);
                $row['refer'] = $_SERVER['HTTP_REFERER'];
                $row['ip'] = $_SERVER['REMOTE_ADDR'];
                $row['inicio'] = date('Y-m-d H:i:s');
                $row['fin'] = date('Y-m-d H:i:s');
                $row['salida'] = "N";
                $row['jaz'] = $n;
                $this->db->insert("admin_sesion", $row);
                
                echo $this->db->error();
                
                $id = $this->db->last_insert_id();
                if ($id == "") {
                    //Error al crear la sesion en la base de datos
                    $this->denegarSesion("Acceso denegado!!!", 5);
                }
                
                $tour = $this->db->select_row("SELECT * FROM admin_tour where id_persona='$rw[id]' ");
                if ($tour) {
                  $_SESSION['tour'] = 1;
                }else{
                  $_SESSION['tour'] = 2;
                }
                //Datos de sesion
                $_SESSION['sesion_id'] = $id;
                $_SESSION['usuario'] = $rw['identificacion'];
                $_SESSION['nombre_usuario'] = $rw['nombre1'] . " " . $rw['apellido1'];
                $_SESSION['nombre_usuario_c'] = $rw['nombre1'] . " ".$rw['nombre2'] . " " . $rw['apellido1'] . " " . $rw['apellido2'];
                $_SESSION['persona_id'] = $rw['id'];
                $_SESSION['usuario_rol'] = $rol;
                if (!file_exists($rw['foto'])) {
                  $rw['foto'] = 'img/anla.png';
                }
                $_SESSION['foto']= $rw['foto'];
                $_SESSION['TOKEN_ID']  = strtotime(date('Y-m-d H:m:s'))*2;

                $text = strtotime(date('Y-m-d H:m:s'))*3;
                $_SESSION['TOKEN']  = get_token($text,$_SESSION['TOKEN_ID']);

                //Determinar accesos
                $_SESSION['acceso_menu'] = array();
                $_SESSION['acceso_menu'][] = 1; //Permisos para todos los usuarios
                $_SESSION['acceso_menu'][] = 3; //Permisos para usuarios logueados
                $_SESSION['acceso_menu'][] = 7; //Menus que dependen del rol
                
              

                $r = array();
                $r['error'] = false;
                $r['msg'] = "Ok";
                echo json_encode($r);
                exit(0);
            }
        }

        $this->denegarSesion("Acceso denegado!!!", 1);
    }

    private function denegarSesion($msg, $tipo) {

        /*
         * Tipo
         * 1: Usuario o clave incorrecta
         * 2: No tiene rol para este aplicativo
         * 3: Ya habia iniciado sesión previamente
         * 4: Error de validación
         * 5: Error al crear la sesion en la base de datos
         */
        $db = $this->db;
        $row['session_id'] = session_id();
        $row['user_agent'] = $db->escape_string($_SERVER['HTTP_USER_AGENT']);
        $row['refer'] = $_SERVER['HTTP_REFERER'];
        $row['ip'] = verIP();
        $row['fecha'] = date('Y-m-d H:i:s');
        $row['usuario'] = $_POST['usuario'];
        $row['tipo'] = $tipo;

        $db->insert("admin_sesion_denegada", $row);
        $r = array();
        $r['error'] = true;
        $r['msg'] = $msg ." ($tipo)";
        echo json_encode($r);
        exit(0);
    }


    function set_login()
    {
        $hash = strtotime(date('Y-m-d H:m:s'))*27;
        $r['error'] = false;
        $r['data'] = md5($hash);
        echo json_encode($r);
    }

}

$accion = ACCION;

$c = new Sesion();
$c->$accion();
?>