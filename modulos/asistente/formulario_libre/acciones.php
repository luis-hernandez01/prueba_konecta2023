<?php
require_once("php/clase_base.php");
require_once("php/validation.php");

$accion = ACCION;
$f = new Asistente();
$f->$accion();

class Asistente extends clase_base {

    function verificar() {
        if ($_POST['tabla'] == "NULL") {
            return;
        }
        $ruta = $_POST['ruta'];
        $menu = $_POST['menu'];

        if (file_exists($ruta)) {
            ?>

            <div class="ui-widget">
                <div class="ui-state-highlight ui-corner-all" style="margin-top: 5px; padding: 0 .7em;">
                    <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
                        La ruta <strong><?php echo $ruta ?></strong> ya existe. 
                        Si se continua se sobreescribiran los datos</p>
                </div>
            </div>
            <?php
        }

        $id = $this->db->select_one("SELECT id FROM admin_menu WHERE menu='$menu'");
        if ($id != "") {
            ?>

            <div class="ui-widget">
                <div class="ui-state-highlight ui-corner-all" style="margin-top: 5px; padding: 0 .7em;">
                    <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
                        El menú <strong><?php echo $menu ?></strong> ya existe. 
                        Si se continua se sobreescribiran los datos</p>
                </div>
            </div>
            <?php
        }
    }

    function generar() {
        ob_start();

        $v = new Validation($_POST);
        $v->addRules('titulo_formulario', 'Titulo formulario', array('required' => true));
        $v->addRules('menu_principal', 'Menu principal', array('required' => true));
        $v->addRules('menu', 'Menu', array('required' => true));
        $v->addRules('titulo_menu', 'Titulo menu', array('required' => true));
        $v->addRules('tipo_acceso', 'Tipo de acceso', array('required' => true));
        $v->addRules('ruta', 'Ruta', array('required' => true));
        $result = $v->validate();

        if ($result['messages'] != "") {//Errores de validación
            $r['error'] = true;
            $r['msg'] = $result['messages'];
            echo json_encode($r);
            exit(0);
        }

        $db = $this->db;
        $objetos = "";


        if (is_array($_POST['campo'])) {
            foreach ($_POST['campo'] as $i => $v) {
                $campo = $_POST['campo'][$i];
                $titulo = $_POST['titulo'][$i];
                $tipo = $_POST['tipo'][$i];
                $obligatorio = $_POST['obligatorio'][$i];
                $longitud = $_POST['longitud'][$i];

                if ($campo != "NULL" && $campo != "") {

                    if ($tipo == "c") {
                        $t = '
            <tr> 
                <td class="tdi">%1$s</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <select id="%2$s" name="%2$s" title="%1$s" required encrypt="true">
                        <?php
                        llenar_combo_encrypt("PONER_SQL_AQUI", true);
                        ?>
                    </select>
                </td>            
            </tr>                        
                            ';
                        $objetos .= sprintf($t, $titulo, $campo) . "\n";
                    } else {
                        $t = '
            <tr> 
                <td class="tdi">%1$s</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <input type="text" encrypt="true" name="%2$s" id="%2$s"  value="" title="%1$s" placeholder="%1$s" maxlength="%3$s" required/>
                </td>            
            </tr>';

                        $objetos .= sprintf($t, $titulo, $campo, $longitud) . "\n";
                    }

                    // Validaciones
                    $v = "";
                    $v .= "'required' => true, ";

                    //Longitud maxima 
                    if ($tipo == "c") {
                        $v .= "'maxLength' => $longitud, ";
                    }
                    $v = trim($v, ", ");
                    $validaciones .= sprintf("\t\t\$v->addRules('%s', '%s', array(%s) );\n", $campo, $titulo, $v);
                }
            }
        }


        $botones = "";
        $acciones_php = "";
        if (is_array($_POST['accion'])) {
            foreach ($_POST['accion'] as $i => $v) {
                $accion = $_POST['accion'][$i];
                $accion2 = strtolower($accion);
                $tipo = $_POST['tipo_accion'][$i];
                $descripcion = $_POST['descripcion'][$i];


                if ($accion != "NULL" && $accion != "") {

//Botones
                    $a = '<button type="submit" name="accion" value="%1$s" class="btn btn-success btn-icon-text accion-%s" style="float:right;"><i class="icon-check"></i> %1$s</button>';
                    $botones .= "\t\t" . sprintf($a, $accion, $accion2) . "\n";


                    //Acciones php
   $t = '/* '.$descripcion.' */
   function %s() {
       $this->validar_token();       
       $this->validar();
       $db = $this->db; // HOMOLOGAR CODIGO EXTERNO


       //PONER CODIGO AQUI ATT : FABIO GARCIA :D
                          
       $result=array();
       $result["error"] = false;
       $result["msg"] = "PONER MENSAJE A ENVIAR AQUI."; 
       echo json_encode($result);
   }';

                    $acciones_php .= sprintf($t, $accion2) . "\n";


$t='
$(function(){
    $("#formulario").submit(function(){
        var data = encriptar_form("formulario",TOKEN_GLOBAL);
        $.ajax({
                url:page_root + "%1$s",
                type: "POST",
                dataType: "JSON",
                data: data,
        beforeSend: function(xhr){
               xhr.setRequestHeader("Authorization",TOKEN_GLOBAL); 
               msg_cargando(true);
            },
        success: function(data){            
            var r = data;
            if (r.error == true)
            {
                for (ind in r.bad_fields)
                {
                    $("#" + r.bad_fields[ind]).addClass("error");
                }
                msg_cargando(false);
                msg($(".respuesta"),r.msg,"error");
                document.getElementById("formulario").reset();
            } else
            {
                msg_cargando(false);
                msg($(".respuesta"),r.msg,"exito");
            }
        },
        error: function(msg) {
               msg_cargando(false);
               msg($(".respuesta"),"Erro desconocido.","error");
            }
        });
        return false;
    })

 })';
                    $acciones_js .= sprintf($t, $accion2) . "\n";
                }
            }
        }

        $t1 = file_get_contents("modulos/asistente/formulario_libre/acciones.txt");
        $t2 = file_get_contents("modulos/asistente/formulario_libre/formulario.txt");
        
        

        $t1 = str_replace("{VALIDACION}", $validaciones, $t1);
        $t1 = str_replace("{ACCIONES_PHP}", $acciones_php, $t1);

        $t2 = str_replace("{TITULO}", $_POST['titulo_formulario'], $t2);
        $t2 = str_replace("{OBJETOS}", $objetos, $t2);
        $t2 = str_replace("{BOTONES}", $botones, $t2);
        $t2 = str_replace("{ACCIONES_JS}", $acciones_js, $t2);

        $ruta = $_POST['ruta'];
        @mkdir($ruta, 0777, true);
        @chmod($ruta, 0777);

        $file1 = "$ruta/acciones.php";
        $file1 = str_replace("//", "/", $file1);
        file_put_contents($file1, $t1);
        chmod($file1, 0777);

        $file2 = "$ruta/formulario.php";
        $file2 = str_replace("//", "/", $file2);
        file_put_contents($file2, $t2);
        chmod($file2, 0777);

        $menu = $_POST['menu'];
        $db->query("DELETE FROM admin_accion WHERE menu='$menu'");
        $db->query("DELETE FROM admin_menu WHERE menu='$menu'");


        $db->query("INSERT INTO admin_menu (`menu`, `padre`, `nombre`, `ruta`, `accion`, `orden`, `visible`,`acceso`,`descripcion`) 
            VALUES ('$menu', '$_POST[menu_principal]', '$_POST[titulo_menu]', '$ruta', 'ver', '1', 'S','$_POST[tipo_acceso]','$_POST[descripcion_menu]') ");

 
        $db->query("INSERT INTO `admin_accion` (`menu`, `accion`, `tipo_accion`, `archivo`,`requiere_permiso`,`descripcion`) 
                    VALUES ('$menu', 'ver', 'pagina', 'formulario.php','S','Permite cargar la vista del formulario (MVC)') ");
 
        //Acciones
        if (is_array($_POST['accion'])) {
            foreach ($_POST['accion'] as $i => $v) {
                $accion = $_POST['accion'][$i];
                $accion2 = strtolower($accion);
                $tipo = $_POST['tipo_accion'][$i];
                $descripcion = limpiarString($_POST['descripcion'][$i]);

                if ($accion != "NULL" && $accion != "") {
                    $db->query("INSERT INTO `admin_accion` (`menu`, `accion`, `tipo_accion`, `archivo`,`requiere_permiso`,`descripcion`) 
                    VALUES ('$menu', '$accion2', '$tipo', 'acciones.php','S','$descripcion') ");
                }
            }
        }

        //Agregar permisos         
        if (is_array($_POST['rol']) && $_POST['tipo_acceso'] == '7') {
            foreach ($_POST['rol'] as $rol => $v) {
                if ($v == "S") {
                    $db->query("INSERT IGNORE INTO admin_permiso_menu (rol,menu)"
                            . "VALUES('$rol','$menu')");
                    $db->query("INSERT IGNORE INTO admin_permiso_accion (rol,accion) "
                            . "(SELECT '$rol',id FROM admin_accion WHERE menu='$menu')");
                }
            }
        }
        $t = ob_get_contents();
        ob_clean();

        if ($t == "") {
            $r['error'] = false;
            $r['msg'] = 'Formulario generado con exito.';
            echo json_encode($r);
        } else {
            $r['error'] = true;
            $r['msg'] = "Formulario generado con errores. \n\n$t";
            echo json_encode($r);
        }
    }

}
