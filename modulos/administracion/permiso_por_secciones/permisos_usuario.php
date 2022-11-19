<script type="text/javascript">
    var r;
    $(document).ready(function(e) {       
        $("#persona_nombre").autocompletar2(page_root + "listarPersonas", {
            form: "formulario",
            inputId: "persona_id",
            minLength: 3});  
    });



</script>
<script type="text/javascript">
	function marcarU(chk){
       if( $('#umenu_'+chk).prop('checked') ) {
           $(".umenu_"+chk).prop('checked', true);
       }else{
           $(".umenu_"+chk).prop('checked', false);
       }
    }

    function asignar_fecha_inicioU(id) {
        var f = $("#uinicio_"+id).val();
        $(".ufecha_inicio_"+id).val(f);
    }

    function asignar_fecha_finU(id) {
        var f = $("#ufin_"+id).val();
        $(".ufecha_fin_"+id).val(f);
    }
</script>

<form id="formulario_dos">
	<tr> 
        <td class="tdi">Buscar persona</td>
        <td class="tdc">:</td>
        <td class="tdd">
            <input type="hidden" name="persona_id" id="persona_id" 
                   class="no-modificable" title="Persona" >
            <input type="text" name="persona_nombre" id="persona_nombre" 
                   class="no-modificable" title="Persona">
             <button type="button" class="btn btn-info mt-2 w-100 text-center" onclick="cargar()">Buscar</button>
        </td>            
    </tr>
</form>

<div id="recibe_informacion"></div>


           



<script type="text/javascript">
	function cargar() {
		$.ajax({
			url: page_root+'listar_seccion',
			type: 'POST',
			dataType: 'json',
			data: $("#formulario_dos").serialize(),
		})
		.done(function(r) {
			$("#recibe_informacion").html(r.data);
			setTimeout(function() {
				$("#arbol_dos").treeview({
                 collapsed: true
               });

              $("input:checkbox").click(verificar);
			}, 100);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	}
</script>