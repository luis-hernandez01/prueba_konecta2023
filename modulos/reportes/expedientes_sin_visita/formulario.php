<script type="text/javascript">
function ver() {
    $(".cargar").css({"display":"none","font-size":"100"});
    $(".acciones").css({"display":"block","font-size":"100"});
}

$(document).ready(function() {
    cargar();
});

function cargar() {
    var gif = '<div style="text-align:center;padding-top:80px"><img src="img/cargar.gif" alt="" style="height:200px;"></div>';
    $("#recibe_informacion").html(gif);

        $.ajax({
            url:page_root + "aceptar",
            type: "POST",
            dataType: "JSON",
            data: {},

            beforeSend: function(){
                 msg($(".respuesta"),"Espere mientras se procesa la información...","exito");
            },

            success: function(r){
                $("#recibe_informacion").html(r.tabla);
                tablesorte('informacion_listado',1000,8,"desc");
            },
            error: function(msg) {
               alert("ATENCION, envia de nuevo tu informacion.");
            }
        });    
}

</script>

<style type="text/css">
    td,th {
    white-space: normal !important;
    }
    th{
        text-align: center;
    }
</style>
<div class="col-md-12 grid-margin stretch-card">
              <div class="card position-relative">
                <div class="card-body">
                  
                  <div id="recibe_informacion"></div>

                  


                </div>
              </div>
            </div>