<?php
class Formulario extends Base {
    function validar() {
        $v = new Validation($_POST);

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


function tabla($data)
{


    foreach ($data as $key => $v) {
        
        
    }




return $tabla;
}


    function aceptar() {
        $this->validar();
    
    $sql="SELECT ex.*, ss.homologacion, ss.nombre as sector, sti.nombre as tipo_instrumento, sti.sigla
    FROM expedientes_seguimiento  ex,
    sub_tipo_instrumentos sti,
    sub_sectores ss
    WHERE ss.id=ex.sub_sectores_id and sti.id=ex.sub_tipo_instrumentos_id";

    $expedientes = $this->db->select_all($sql);
    $fechainicio ="2010-01-01";
    $fechafin = date('Y-m-d');
    $info = $this->db->select_all("SELECT * FROM festivos WHERE fecha >='$fechainicio' and fecha <='$fechafin'");
    foreach ($info as $key => $v) {
        $diasferiados[] = $v['fecha'];
    }      
    $clasi = $this->db->select_all("SELECT * FROM tipo_visita WHERE 1 ");
    foreach ($clasi as $key => $v) {
        $clasificacion[$v['id']] = $v['nombre'];
    }
    $tabla="<table class='table table-striped' id='informacion_listado'>
    <thead>
        <tr>
            <th>Sector</th>
            <th>Sector Hijo</th>
            <th>Tipo de Instrumento</th>
            <th>Número de Expediente</th>
            <th>Nombre del Proyecto</th>
            <th>Solicitante</th>
            <th>Fecha Ultima Visita</th>
            <th>Tipo de Visita</th>
            <th>Días Hábiles</th>
            <th>Días Calendarios</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>";
    $num=0;

        foreach ($expedientes as $key => $v) {
            $id = $v['id'];
            $visitas = $this->db->select_row("SELECT * FROM visitas_seguimiento WHERE expedientes_seguimiento_id='$id' ORDER BY fecha_fin_hora DESC");
            $fechainicio = $visitas['fecha_fin'];
            $fechafin = date('Y-m-d');
            $dias_calendarios = getDiasCalendario($fechainicio, $fechafin,$diasferiados);

            if ($dias_calendarios>=365) {
                $dias_habiles = count(getDiasHabiles($fechainicio, $fechafin,$diasferiados));
                $tipos_clasificacion = $clasificacion[$visitas['tipo_visita_id']];
                $num=$num+1;
                $tabla.="<tr>
                   <td>$v[homologacion]</td>
                   <td>$v[sector]</td>
                   <td>$v[sigla]</td>            
                   <td>$v[numero_expediente]</td>
                   <td>$v[nombre_proyecto]</td>
                   <td>$v[solicitante]</td>
                   <td>$visitas[fecha_fin]</td>
                   <td>$tipos_clasificacion</td>
                   <td>$dias_habiles</td>
                   <td>$dias_calendarios</td>
                   <td>-</td>
                </tr>";
            }
        }
        

    $tabla.="</tbody>
</table>";
        $result=array();
        $result["error"] = false;
        $result["msg"] = "Análisis Terminado con Exito!"; 
        $result["tabla"] = $tabla; 
        echo json_encode($result);
    }

}
//$_POST = array_map("strtoupper", $_POST); //CONVERTIR TODO EN MAYUSCULA
 
$accion = ACCION;
$f = new Formulario();
$f->$accion();
?>