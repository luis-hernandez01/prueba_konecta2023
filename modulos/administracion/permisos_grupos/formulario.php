<script type="text/javascript">
function ver() {
    $(".cargar").css({"display":"none","font-size":"100"});
    $(".acciones").css({"display":"block","font-size":"100"});
    $("#actualizar").hide();
}
   function todos_corrientes() {
        $(".p_corrientes" ).prop( "checked", true );
    }

    function limpiar_corrientes() {
        $(".p_corrientes" ).prop( "checked", false );
    }

    function cambiar_todo(id) {
     
        if ($("#todos_"+id).is( ":checked" )) { 
             $(".p_"+id).prop( "checked", true );
        }else{
             $(".p_"+id ).prop( "checked", false );
        }
       
    }


   $(document).ready(function(e) {
         ver();
         $("#formulario").submit(function(){
            $.ajax({
                url: page_root+'aceptar',
                type: 'POST',
                dataType: 'json',
                data: $("#formulario").serialize(),
            })
            .done(function(data) {
               $("#recibe_datos_corrientes").html(data.msg);
               $("#actualizar").show();
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
            
            return false;
         })     

          $("#formulario_corrientes").submit(function(){
            $.ajax({
                url: page_root+'actualizar',
                type: 'POST',
                dataType: 'json',
                data: $("#formulario_corrientes").serialize(),
            })
            .done(function(r) {
               msg($(".respuesta"),r.msg,"complete");
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
            
            return false;
         })     
    });

</script>


<script type="text/javascript">
    $(document).ready(function(e) {
 
        $("#persona_nombre").autocompletar2(page_root + "listarPersonas", {
            form: "formulario",
            inputId: "persona_id",
            minLength: 3});        
    });
</script>


<div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">

<form id="formulario" style="margin:auto;width:100%">

<div class="form-group">
                    <div class="input-group">
                       <input type="hidden" name="persona_id" id="persona_id" class="no-modificable" title="Persona" required="true">
                       <input type="text" type="text" name="persona_nombre" id="persona_nombre" class="form-control" placeholder="Por favor ingrese usuario..." required="true">
                      <div class="input-group-append">
                        <button class="btn btn-sm btn-primary" type="submit">Buscar</button>
                      </div>
                    </div>
                  </div>

</form>


 <form id="formulario_corrientes" style="margin:auto;width:100%">

<div class="row" id="recibe_datos_corrientes">
</div>
<br>


<div class="row" id="actualizar">
   <div class="col-lg-4"><button type="button" class="btn btn-success" style="width:100%" onclick="todos_corrientes()">Seleccionar todos</button></div>
   <div class="col-lg-4"><button type="button" class="btn btn-warning" style="width:100%" onclick="limpiar_corrientes()">Limpiar</button></div>
   <div class="col-lg-4"><button type="submit" class="btn btn-primary" style="width:100%" >Actualizar</button></div>
</div>

<div class="respuesta" style="margin-top:0px"><i class="textomsg"></i></div>

</form>

                </div>
              </div>
            </div>


