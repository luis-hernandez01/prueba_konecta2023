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
                        El menu <strong><?php echo $menu ?></strong> ya existe. 
                        Si se continua se sobreescribiran los datos</p>
                </div>
            </div>
            <?php
        }
    }

    function cargarCampos() {
        $db = $this->db;

        $sql = stripcslashes($_POST['sql']);
        $rs = $db->query($sql);
        ?>

        <script type="text/javascript">

            $(document).ready(function() {
                $("input:text").change(function() {
                    calcular_ancho();
                });

                $("input:text").keyup(function() {
                    calcular_ancho();
                });
                calcular_ancho();
            });

        </script>

        <table style="width: 100%; border-collapse: collapse"> 
            <tr class='ui-widget-header'>
                <th style="text-align: left; width: 50px">NO</th>
                <th style="text-align: left">CAMPO</th>
                <th style="text-align: left">TITULO</th>
                <th style="text-align: center">ANCHO</th>
            </tr>
            <?php
            $i = 1;
            while ($f = $db->fetch_field($rs)) {
                $campo = $f->name;
                ?>
                <tr>
                    <td><?php echo $i++ ?></td>
                    <td>
                        <?php echo $campo ?>
                    </td>
                    <td>
                        <input type="text" name="titulo[<?php echo $campo ?>]" 
                               value="<?php echo strtoupper($campo) ?>" style="width: 300px"/>
                    </td>
                    <td style="text-align: center">
                        <input type="text" name="ancho[<?php echo $campo ?>]" class="input-ancho" 

                               value="100" style="width: 100px; text-align: center"/>
                    </td>
                </tr>           

            <?php } ?>
            <tr>
                <td></td>
                <td>

                </td>
                <td>

                </td>
                <td style="text-align: center">
                    <span id="total_ancho" style="font-weight: bold">-</span>

                </td>
            </tr>               

        </table>
        <?php
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
                    <select id="%2$s" name="%2$s" title="%1$s" required>
                        <?php
                        llenar_combo("PONER_SQL_AQUI", true);
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
                    <input type="text" name="%2$s" id="%2$s"  value="" title="%1$s" placeholder="%1$s" maxlength="%3$s" required/>
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

        $titulos = "";
        $filas = "";
        foreach ($_POST['ancho'] as $campo => $v) {
            $ancho = $_POST['ancho'][$campo];
            $titulo = $_POST['titulo'][$campo];
            $t = '<th style="width: %spt">%s</th>';
            $titulos .= "\t\t" . sprintf($t, $ancho, $titulo) . "\n";

            $t = '<td style="width: %spt"> <?php echo $rw["%s"] ?></td>';
            $filas .= "\t\t\t" . sprintf($t, $ancho, $campo) . "\n";            
        }

        $sql = stripcslashes($_POST['sql']);

        $t1 = file_get_contents("modulos/asistente/reporte/acciones.txt");
        $t2 = file_get_contents("modulos/asistente/reporte/formulario.txt");

        $t1 = str_replace("{VALIDACION}", $validaciones, $t1);
        $t1 = str_replace("{SQL}", $sql, $t1);
        $t1 = str_replace("{TITULOS}", $titulos, $t1);
        $t1 = str_replace("{FILAS}", $filas, $t1);
        
        $t1 = str_replace("{MARGEN_IZQUIERDO}", $_POST['mizq'], $t1);
        $t1 = str_replace("{MARGEN_DERECHO}", $_POST['mder'], $t1);
        $t1 = str_replace("{MARGEN_INFERIOR}", $_POST['minf'], $t1);
        $t1 = str_replace("{ORIENTACION_PAPEL}", $_POST['orientacion'], $t1);
        $t1 = str_replace("{TAMANO_PAPEL}", $_POST['tamano'], $t1);
        
        $t2 = str_replace("{TITULO}", $_POST['titulo_formulario'], $t2);
        $t2 = str_replace("{OBJETOS}", $objetos, $t2);

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


        $db->query("INSERT INTO admin_menu (`menu`, `padre`, `nombre`, `ruta`, `accion`, `orden`, `visible`,acceso) 
            VALUES ('$menu', '$_POST[menu_principal]', '$_POST[titulo_menu]', '$ruta', 'ver', '1', 'S','$_POST[tipo_acceso]') ");

        $db->query("INSERT INTO `admin_accion` (`menu`, `accion`, `tipo_accion`, `archivo`,`requiere_permiso`) 
                    VALUES ('$menu', 'ver', 'pagina', 'formulario.php','N') ");        
        
        $db->query("INSERT INTO `admin_accion` (`menu`, `accion`, `tipo_accion`, `archivo`,`requiere_permiso`) 
                    VALUES ('$menu', 'mostrar', 'descarga', 'acciones.php','N') ");

        $db->query("INSERT INTO `admin_accion` (`menu`, `accion`, `tipo_accion`, `archivo`,`requiere_permiso`) 
                    VALUES ('$menu', 'validar', 'json', 'acciones.php','N') ");

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
