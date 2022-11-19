<script type="text/javascript">
    $(document).ready(function(e) {
                
        $("#persona_nombre").autocompletar2(page_root + "listarPersonas", {
            form: "formulario",
            inputId: "persona_id",
            minLength: 3});        
    });
</script>

<script type="text/javascript">
function ver() {
    $(".cargar").css({"display":"none","font-size":"100"});
    $(".acciones").css({"display":"block","font-size":"100"});
}


$(function(){
      $(".cargar").css({"display":"none","font-size":"100"});
      $("#formulario").submit(function(){
            $.ajax({
            url:page_root + "aceptar",
            type: "POST",
            dataType: "JSON",
            data: $("#formulario").serialize(),

            beforeSend: function(){
                 $(".cargar").css({"display":"block","font-size":"100"});
                 $(".acciones").css({"display":"none","font-size":"100"});
            },

            success: function(data){
            var r = data;
            if (r.error == true)
            {
                for (ind in r.bad_fields)
                {
                    $("#" + r.bad_fields[ind]).addClass("error");
                }
                msg($(".respuesta"),r.msg,"error");
                ver();
            } else
            {
                msg($(".respuesta"),r.msg,"exito");
                ver();
                document.getElementById("formulario").reset();
            }

            },
            error: function(msg) {
            alert("ATENCION, envia de nuevo tu informacion.");
            ver();
            }
            });
            return false;
            })
 })


</script>




    <form id="formulario" method="POST" class="form-horizontal" style="margin:auto;width:85%">
    <div class="box-body">
        <table style="width:100%">
 

            <tr> 
                <td class="tdi">Usuario</td>
                <td class="tdc">:</td>
                <td class="tdd">
                     <input type="hidden" name="persona_id" id="persona_id" 
                           class="no-modificable" title="Persona">
                    <input type="text" name="persona_nombre" id="persona_nombre" 
                           class="no-modificable" title="Persona">
                </td>            
            </tr>

            <tr> 
                <td class="tdi">Contraseña</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <input type="text" name="clave" id="clave"  value="" title="Contraseña" maxlength="30" required placeholder="Por favor ingrese la clave que desea asignar..." />
                </td>            
            </tr>


    </table>

    <div class="error"></div>
    <div class="cargar" style="float:right;">  <img src="img/loader.gif" class="img-responsive"></div>
    <div class="box-footer">
		<input type="submit" name="accion" value="Aceptar" class="btn btn-block btn-success" style="float:right;width:80px" />

    </div>
    </div>
    <div class="respuesta" style="margin-top:0px"><i class="textomsg"></i></div>
</form>
