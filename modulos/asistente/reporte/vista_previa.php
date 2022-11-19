<?php
require_once("php/tcpdf/tcpdf.php");
require_once("php/tcpdf_reporte.php");

 $sql = stripcslashes($_POST['sql']);

$rs = $db->query($sql);
ob_start();
?>

<table style="font-family:'Times New Roman', Times, serif; font-size:8.5pt; 
       border-collapse:collapse" border="1" bordercolor="<?php echo BORDE_HTML ?>" 
       cellpadding="1" cellspacing="0">
    <thead>
        <!-- TODO LO QUE VA AQUI SE REPITE EN EL ENCABEZADO -->
        <tr style="font-weight:bold; text-align:left; background-color:<?= FONDO_HTML_TITULO ?>">
            <th style="width: 30pt">NO</th>
            <?php
            foreach ($_POST['ancho'] as $campo => $v) {
                $ancho = $_POST['ancho'][$campo];
                $titulo = $_POST['titulo'][$campo];
                $t = '<th style="width: %spt">%s</th>';
                echo sprintf($t, $ancho, $titulo);
            }
            ?>

        </tr>
    </thead>
    <tbody>
        <?php
        while ($rw = $db->fetch_assoc($rs)) {
            $fondo = ($fondo == "#fff" ? FONDO_HTML : "#fff");
            ?>
            <tr style="text-align:left; background-color:<?= $fondo ?>" >
                <td style="width: 30pt"> <?= ++$x ?> </td>
                <?php
                foreach ($_POST['ancho'] as $campo => $v) {
                    $ancho = $_POST['ancho'][$campo];
                    $titulo = $_POST['titulo'][$campo];
                    $t = '<td style="width: %spt">%s</td>';
                    echo sprintf($t, $ancho, $rw[$campo]);
                }
                ?> 

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


$p = new TCPDF_REPORTE($_POST['orientacion'], "pt",  $_POST['tamano'], true);

/*
echo "<pre>";
$d = $p->getPageDimensions();
var_dump($d);
echo "</pre>";
*/

$p->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$p->SetMargins($_POST['mizq'], 70, $_POST['mder']);
$p->SetFooterMargin(30);
$p->SetAutoPageBreak(TRUE, $_POST['minf']);

$p->setPrintHeader(true);
$p->setPrintFooter(true);
$p->SetDisplayMode(100);
$p->setImageScale(PDF_IMAGE_SCALE_RATIO);

$p->SetDrawColorArray($DRAW_COLOR);
$p->SetFillColorArray($FILL_COLOR);


$a=$p->getPageWidth();
$p->SetLeftData(EMPRESA, EMPRESA_NIT, "VICERRECTORIA DE INVESTIGACIÓN", "TITULO 1");
$p->SetRightData("LISTADO DE MUNICIPIOS", "TITULO 3", "TITULO 4", "TITULO 5");

$p->AddPage();

$p->SetFont("times", "", 5);
$p->writeHTML($html, true, 0, true, 0);

$archivo = "reporte";
$p->Output("{$archivo}.pdf", PDF_MODO_IMPRESION);
?>