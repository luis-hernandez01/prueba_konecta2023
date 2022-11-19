<?php
 $sql="SELECT se.* FROM seccion se, tipo_seccion ts WHERE se.id_tipo_seccion=ts.id and ts.id=1";
$secciones= $db->select_all($sql);
$data_seciones=array();
foreach ($secciones as $key => $rw) {
    $sql="SELECT * FROM subseccion WHERE id_seccion='$rw[id]'";
    $subseccion= $db->select_all($sql);
    foreach ($subseccion as $k => $v) {
    	$v['seccion_padre'] = $rw['nombre'];
    	$v['id_seccion_padre'] = $rw['id'];
        $data_seciones[$rw['id']][]=$v;
    }
 }

$html_one_arbol = crear_arbol($data_seciones,'arbol_uno');


?>
<script type="text/javascript">
	function marcar(chk){
       if( $('#menu_'+chk).prop('checked') ) {
           $(".menu_"+chk).prop('checked', true);
       }else{
           $(".menu_"+chk).prop('checked', false);
       }
    }

    function asignar_fecha_inicio(id) {
        var f = $("#inicio_"+id).val();
        $(".fecha_inicio_"+id).val(f);
    }

    function asignar_fecha_fin(id) {
        var f = $("#fin_"+id).val();
        $(".fecha_fin_"+id).val(f);
    }
</script>

<form id="formulario_uno">
	<?php echo $html_one_arbol; ?>
	<button type="button" class="btn btn-success w-100 mt-2 text-center" onclick="guardar_global()">Guardar</button>
</form>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$("#arbol_uno").treeview({
            collapsed: true
        });

        $("input:checkbox").click(verificar);
	});

	function guardar_global() {
		$.ajax({
			url: page_root+'agregar',
			type: 'POST',
			dataType: 'json',
			data: $("#formulario_uno").serialize(),
		})
		.done(function(r) {
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	}
</script>