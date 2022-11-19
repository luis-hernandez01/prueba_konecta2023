<?php
require_once("php/tcpdf/tcpdf.php");
require_once("php/tcpdf_reporte.php");

class Formulario extends Base {
    function validar() {
        $v = new Validation($_POST);
		$v->addRules('fecha_inicio', 'Fecha inicio', array('required' => true) );
		$v->addRules('fecha_fin', 'Fecha fin', array('required' => true) );

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

        $fecha_inicio= $_POST['fecha_inicio']; 
        $fecha_fin= $_POST['fecha_fin'];
        $empleado = $_POST['empleados'];

        $sql = "SELECT distinct pr.id as empleados, pr.nombre as proyectos
        FROM personas p,
        checking c,
        honorarios h,
        proyecto pr
        WHERE c.honorario_id=h.id
        and h.proyecto_id=pr.id
        and h.persona_id=p.id
        and ( c.fecha_salida>='$fecha_inicio' and c.fecha_salida<='$fecha_fin' and p.id= '$empleado') and c.visible=1 ";

        $rs = $db->query($sql);

        ob_start();
        ?>
        <meta charset="utf-8">
        <?php
            while ($rw3 = $db->fetch_assoc($rs)) {
            $id_proyecto = $rw3['empleados'];
            $nombre_proyecto = $rw3['proyectos'];
        ?>
        <br> <br>
        <table style="font-family:'Times New Roman', Times, serif; font-size:8.5pt; 
                   border-collapse:collapse" border="0" bordercolor="<?php echo BORDE_HTML ?>" 
                   cellpadding="0" cellspacing="0">
            <tr>
                <td style="text-align: center; font-family:'Times New Roman', Times, serif; font-size:15.5pt;">
                   PROYECTO: <?php echo strtoupper($nombre_proyecto) ?>
                </td>
            </tr>
        </table>

        


        <table style="font-family:'Times New Roman', Times, serif; font-size:8.5pt; 
                   border-collapse:collapse" border="0" bordercolor="<?php echo BORDE_HTML ?>" 
                   cellpadding="0" cellspacing="0">
            <thead>
                <tr style="font-weight:bold; text-align:left; background-color:<?= FONDO_HTML_TITULO ?>">
                    <td style="width: 30pt;"><b>No</b></td>
                    <td style="width: 100pt;"><b>EMPLEADO</b></td>
                    <td style="width: 80;"><b>FECHA INGRESO</b></td>
                    <td style="width: 50;"><b>HORA ENTRADA</b></td>
                    <td style="width: 50;"><b>HORA SALIDA</b></td>
                    <td style="width: 60pt;"><b>VALOR POR HORAS</b></td>
                    <td style="width: 80pt;"><b>HORAS TRABAJADAS</b></td>
                    <td style="width: 50pt;"><b>TOTAL A PAGAR</b></td>
                    <td style="width: 40pt;"><b>ESTADO</b></td>
                </tr>
            </thead>
            <tbody>
                <?php
                $num = 1;
                $sql2 = "SELECT distinct c.id, CONCAT_WS(' ', p.primer_nombre,p.segundo_nombre,p.primer_apellido,p.segundo_apellido) AS nombre_completo, h.pago_por_hora, c.fecha_salida, c.hora_llegada, c.hora_salida, es.nombre as estados
            FROM
            checking c,
            honorarios h,
            personas p,
            proyecto pr,
            cuentas cu,
            estado es
            WHERE cu.checking_id=c.id
            and cu.estado_id=es.id
            AND c.honorario_id=h.id
            and c.visible=1
            and ( c.fecha_salida>='$fecha_inicio' and c.fecha_salida<='$fecha_fin' )
            and h.proyecto_id=pr.id
            and h.persona_id=p.id
            and h.proyecto_id = '$id_proyecto' ";

                $rs2 = $db->query($sql2);
                while ($rw2 = $db->fetch_array($rs2)) {
                    $fecha1 = new DateTime($rw2['hora_llegada']);//fecha inicial
                    $fecha2 = new DateTime($rw2['hora_salida']);//fecha de cierre

                    $intervalo = $fecha1->diff($fecha2);

                    $diferencia = $intervalo->format('%H');
                    $total= $diferencia * $rw2['pago_por_hora'] .",00";

                    /*$llegada = strtotime($rw2['hora_llegada']);
                    $salida = strtotime($rw2['hora_salida']);

                    $total_horas = round((($llegada-$salida)/60/60),2);*/
                    
                ?>

                    <tr style="text-align:left; background-color:<?= $fondo ?>">
                        <td style="width: 30pt;"><?php echo $num++ ?></td>
                        <td style="width: 100pt;"><?php echo $rw2['nombre_completo'] ?></td>
                        <td style="width: 80pt;"><?php echo $rw2['fecha_salida'] ?></td>
                        <td style="width: 50pt;"><?php echo $rw2['hora_llegada'] ?></td>
                        <td style="width: 50pt;"><?php echo $rw2['hora_salida'] ?></td>
                        <td style="width: 60pt;">$ <?php echo $rw2['pago_por_hora'] ?></td>
                        <td style="width: 80pt;"><?php echo $diferencia ?></td>
                        <td style="width: 50pt;">$ <?php echo $total ?></td>
                        <td style="width: 40pt;"><?php echo $rw2['estados'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>


        </table>
        <table style="font-family:'Times New Roman', Times, serif; font-size:8.5pt; 
                   border-collapse:collapse" border="0" bordercolor="<?php echo BORDE_HTML ?>" 
                   cellpadding="0" cellspacing="0">
            <tr style="font-weight:bold; text-align:left; background-color:<?= FONDO_HTML_TITULO ?>">
                <td style="width: 540pt; text-align: center; font-family:'Times New Roman', Times, serif; font-size:11.5pt;">
                    TOTALIDAD TRABAJADA
                </td>
            </tr>
        </table>

        <table style="font-family:'Times New Roman', Times, serif; font-size:8.5pt; 
                   border-collapse:collapse" border="0" bordercolor="<?php echo BORDE_HTML ?>" 
                   cellpadding="0" cellspacing="0">
            <tbody>
                <?php
                $num = 1;
                $sql4 = "SELECT distinct c.id, SUM( 
            HOUR(TIMEDIFF(c.hora_llegada, c.hora_salida)) + 
            CASE WHEN MINUTE(TIMEDIFF(c.hora_llegada, c.hora_salida)) % 60 > 30 THEN 1 ELSE 0 END
        ) as totalhora, CONCAT_WS(' ', p.primer_nombre,p.segundo_nombre,p.primer_apellido,p.segundo_apellido) AS nombre_completo, h.pago_por_hora, c.fecha_salida, c.hora_llegada, es.nombre as estados
            FROM
            checking c,
            honorarios h,
            personas p,
            proyecto pr,
            cuentas cu,
            estado es
            WHERE cu.checking_id=c.id
            and cu.estado_id=es.id
            AND c.honorario_id=h.id
            and c.visible=1
            and ( c.fecha_salida>='$fecha_inicio' and c.fecha_salida<='$fecha_fin' )
            and h.proyecto_id=pr.id
            and h.persona_id=p.id
            and h.proyecto_id = '$id_proyecto' ";

                $rs4 = $db->query($sql4);
                while ($rw4 = $db->fetch_array($rs4)) {
                    $total= $rw4['totalhora'] * $rw4['pago_por_hora'] .",00";

                    /*$llegada = strtotime($rw2['hora_llegada']);
                    $salida = strtotime($rw2['hora_salida']);

                    $total_horas = round((($llegada-$salida)/60/60),2);*/
                    
                ?>

                    <tr style="text-align:left; background-color:<?= $fondo ?>">
                        <td style="width: 30pt;"><?php echo $num++ ?></td>
                        <td style="width: 340pt;"><?php echo $rw4['nombre_completo'] ?></td>
                        <td style="width: 80pt;"><?php echo $rw4['totalhora'] ?></td>
                        <td style="width: 100pt;">$ <?php echo $total ?></td>
                        <!-- <td style="width: 100pt;"><?php /*echo $rw4['estados']*/ ?></td> -->
                    </tr>
                <?php } ?>
            </tbody>


        </table>



        <?php 

        $sql1 = "SELECT SUM( 
            HOUR(TIMEDIFF(c.hora_llegada, c.hora_salida)) + 
            CASE WHEN MINUTE(TIMEDIFF(c.hora_llegada, c.hora_salida)) % 60 > 30 THEN 1 ELSE 0 END
        ) as totalhora, h.pago_por_hora
        FROM
        checking c,
        honorarios h,
        personas p,
        proyecto pr,
        cuentas cu,
        estado es
        WHERE cu.checking_id=c.id
        and cu.estado_id=es.id
        AND c.honorario_id=h.id
        and c.visible=1
        and ( c.fecha_salida>='$fecha_inicio' and c.fecha_salida<='$fecha_fin' )
        and h.proyecto_id=pr.id
        and h.persona_id=p.id
        and h.proyecto_id = '$id_proyecto' ";

        $rs1 = $db->query($sql1);
       while ($rw1 = $db->fetch_array($rs1)) {

            $totales= $rw1['totalhora'] * $rw1['pago_por_hora'] ;
            $formatterES = new NumberFormatter("en", NumberFormatter::SPELLOUT);
            $convertiraletra =$formatterES->format($totales);
            $fondo = ($fondo == "#fff" ? FONDO_HTML : "#fff");
         ?>
        <table style="font-family:'Times New Roman', Times, serif; font-size:8.5pt; 
                   border-collapse:collapse" border="0" bordercolor="<?php echo BORDE_HTML ?>" 
                   cellpadding="0" cellspacing="0">
            <tr style="font-weight:bold; text-align:left; background-color:<?= FONDO_HTML_TITULO ?>">
                <td style="width: 540pt; text-align: left; font-family:'Times New Roman', Times, serif; font-size:11.5pt;">
                    <?php echo $convertiraletra ?> dollars with zero cents.
                </td>
            </tr>
        </table>

        <?php } } ?>




        

        <?php
        $html = ob_get_contents();
        ob_clean();
        ob_flush();

        
        $nombre_archivo = "reporte";
        
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