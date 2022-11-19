<?php
require_once("php/tcpdf/tcpdf.php");
require_once("php/tcpdf_reporte.php");

class Formulario extends Base {
    function validar() {
        $v = new Validation($_POST);
		$v->addRules('fecha_inicial', 'Fecha Inicial', array('required' => true) );
		$v->addRules('fecha_final', 'Fecha Final', array('required' => true) );

        $result = $v->validate();

        if ($result['messages'] == "") {//No hay errores de validacion
            return true;
        } else { //Errores de validación
            $r['error'] = true;
            $r['msg'] = $result['messages'];
            $r['bad_fields'] = $result['bad_fields'];
            $r['errors'] = $result['errors'];
            echo json_encode($r);
            exit(0);
        }
        return true;
    }

    function mostrar() {
        global $DRAW_COLOR, $FILL_COLOR, $FILL_COLOR_TITULO;
        $db = $this->db;
        
        $fecha_inicio= $_POST['fecha_inicial']; 
        $fecha_fin= $_POST['fecha_final']; 

        $sql = "SELECT ex.*,ex.id as id_expediente, vs.id as id_visita, ss.homologacion, ss.nombre as sector, sti.nombre as tipo_instrumento, sti.sigla, vs.*, YEAR(fecha_fin) as ano_fin, MONTH(fecha_fin) as mes_fin
        FROM expedientes_seguimiento  ex,
        sub_tipo_instrumentos sti,
        sub_sectores ss,
        visitas_seguimiento vs
        WHERE vs.expedientes_seguimiento_id=ex.id 
        and ss.id=ex.sub_sectores_id 
        and ( vs.fecha_fin>='$fecha_inicio' and vs.fecha_fin<='$fecha_fin' )
        and sti.id=ex.sub_tipo_instrumentos_id and vs.visible=1 ORDER BY ano_fin,mes_fin ASC
                ";

        $rs = $db->query($sql);
        ob_start();
        ?>
        
        <meta charset="utf-8">
        <table style="font-family:'Times New Roman', Times, serif; font-size:8.5pt; 
               border-collapse:collapse" border="1" bordercolor="<?php echo BORDE_HTML ?>" 
               cellpadding="1" cellspacing="0">
            <thead>
                <!-- TODO LO QUE VA AQUI SE REPITE EN EL ENCABEZADO -->
                <tr style="font-weight:bold; text-align:left; background-color:<?= FONDO_HTML_TITULO ?>">
                    <th style="width: 30pt">NO</th>
                    <th style="width: 100pt">NÚMERO EXPEDIENTE</th>
                    <th style="width: 100pt">SOLICITANTE</th>
                    <th style="width: 100pt">NOMBRE PROYECTO</th>
                    <th style="width: 100pt">SECTOR PADRE</th>
                    <th style="width: 100pt">SECTOR HIJO</th>
                    <th style="width: 100pt">TIPO INSTRUMENTO</th>
                    <th style="width: 100pt">FECHA INICIO</th>
                    <th style="width: 100pt">FECHA FIN</th>
                    <th style="width: 100pt">TIPO DE VISITA</th>
                    <th style="width: 100pt">AÑO FIN</th>
                    <th style="width: 100pt">MES FIN</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($rw = $db->fetch_assoc($rs)) {
                    $fondo = ($fondo == "#fff" ? FONDO_HTML : "#fff");
                    $tipos_clasificacion = $this->db->select_one("SELECT nombre FROM tipo_visita WHERE id='$rw[tipo_visita_id]' ");
                    ?>
                    <tr style="text-align:left; background-color:<?= $fondo ?>" >
                        <td style="width: 30pt"> <?= ++$x ?> </td>
                        <td style="width: 100pt"> <?php echo $rw["numero_expediente"] ?></td>
                        <td style="width: 100pt"> <?php echo $rw["solicitante"] ?></td>
                        <td style="width: 100pt"> <?php echo $rw["nombre_proyecto"] ?></td>
                        <td style="width: 100pt"> <?php echo $rw["homologacion"] ?></td>
                        <td style="width: 100pt"> <?php echo $rw["sector"] ?></td>
                        <td style="width: 100pt"> <?php echo $rw["sigla"] ?></td>
                        <td style="width: 100pt"> <?php echo $rw["fecha_inicio"] ?></td>
                        <td style="width: 100pt"> <?php echo $rw["fecha_fin"] ?></td>
                        <td style="width: 100pt"> <?php echo $tipos_clasificacion ?></td>
                        <td style="width: 100pt"> <?php echo $rw["ano_fin"] ?></td>
                        <td style="width: 100pt"> <?php echo $rw["mes_fin"] ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

        <?php
        $html = ob_get_contents();
        ob_clean();
        ob_flush();

        
        $nombre_archivo = "reporte_expedientes_visita";
        
        $formato = $_POST['formato'];
       
        if ($formato == "XLS") {
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment; filename={$nombre_archivo}.xls;");
            echo $html;
        } else if ($formato == "DOC") {
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment; filename={$nombre_archivo}.doc;");
            echo $html;
        } else if ($formato == "PDF") {
            $p = new TCPDF_REPORTE("P", "pt", "LETTER", true);
            
            $_SESSION['ancho_header'] = 580; // SI ES   P (VERTICAL)
            //$_SESSION['ancho_header'] = 740; // SI ES   L (HORIZONTAL)
            $p->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            //$p->SetMargins(20, 100, 20);// SI ES   L (HORIZONTAL)
            $p->SetMargins(20, 80, 20);// SI ES   P (VERTICAL)

            $p->SetFooterMargin(30);
            $p->SetAutoPageBreak(TRUE, 45);

            $p->setPrintHeader(true);
            $p->setPrintFooter(true);
            $p->SetDisplayMode(100);
            $p->setImageScale(PDF_IMAGE_SCALE_RATIO);

            $p->SetDrawColorArray($DRAW_COLOR);
            $p->SetFillColorArray($FILL_COLOR);

            //$p->SetLeftData(EMPRESA, EMPRESA_NIT, "CONTROL DE TERMINOS", "TITULO DE REPORTE");
            //$p->SetRightData("TITULO 1", "TITULO 2", "TITULO 3", "TITULO 4");

            $p->AddPage();

            $p->SetFont("times", "", 5);
            $p->writeHTML($html, true, 0, true, 0);   
            $p->Output("{$archivo}.pdf", PDF_MODO_IMPRESION);
        } else {
            //Formato = HTML
            echo $html;
        }
    }
 
}
 
$accion = ACCION;
$f = new Formulario();
$f->$accion();
?>