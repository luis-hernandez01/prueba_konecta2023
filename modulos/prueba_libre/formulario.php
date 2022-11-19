<script type="text/javascript">
  
$(function(){
   
    $("#formulario").submit(function(){
         var data = encriptar_form("formulario",TOKEN_GLOBAL);
        $.ajax({
                url:page_root + "aceptar",
                type: "POST",
                dataType: "JSON",
                data: data,
        beforeSend: function(xhr){
               xhr.setRequestHeader("Authorization",TOKEN_GLOBAL); 
               msg_cargando(true);
            },
        success: function(data){            
            var r = data;
            if (r.error == true)
            {
                for (ind in r.bad_fields)
                {
                    $("#" + r.bad_fields[ind]).addClass("error");
                }
                msg_cargando(false);
                msg($(".respuesta"),r.msg,"error");
                document.getElementById("formulario").reset();
            }else
            {
                msg_cargando(false);
                msg($(".respuesta"),r.msg,"exito");
            }
        },
        error: function(msg) {
               msg_cargando(false);
               msg_error('Error Desconocido, Por Favor Actualizar la Pagina.');
            }
        });
        return false;
    })
 })
</script>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="formulario" method="POST" class="form-horizontal" style="margin:auto;width:95%">
                          <table style="width:100%">                          
                             
              <tr> 
                <td class="tdi">SINO</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <select name="sino" id="sino" encrypt="true">
                       <?php llenar_combo_encrypt("SELECT * FROM si_no") ?>
                    </select>
                </td>            
            </tr>
            <tr> 
                <td class="tdi">Nombre</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <input type="text" name="nombre" id="nombre" encrypt="true" value="1234" title="Nombre" placeholder="Nombre" maxlength="30" required/>
                </td>            
            </tr>
            <tr> 
                <td class="tdi">Apellido</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <input type="text" name="apellido" id="apellido" encrypt="true" value="xxx33" title="Apellido" placeholder="Apellido" maxlength="30" required/>
                </td>            
            </tr>
            
            <tr> 
                <td class="tdi">Apodo</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <input type="text" name="apodo" id="apodo" encrypt="true" value="xxx37" title="Apellido" placeholder="Apellido" maxlength="30" required/>
                </td>            
            </tr>
            
             <tr> 
                <td class="tdi">Apodo2</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <input type="text" name="apodo" id="apodo" encrypt="true" value="xxx37" title="Apellido" placeholder="Apellido" maxlength="30" required/>
                </td>            
            </tr>
                          
                          </table>
                          
                          <div class="error" id="cargando_libre" style="display: none">
                              <div class="clearfix">
                                 <div class="spinner-border float-right" role="status">
                                    <span class="sr-only">Loading...</span>
                                 </div>
                              </div>
                          </div>
                          <div class="box-footer acciones" id="libre_acciones">
                             		<button type="submit" name="accion" value="Aceptar" id="Aceptar" encrypt="true" class="btn btn-success btn-icon-text accion-Aceptar" style="float:right;"><i class="icon-check"></i> Aceptar</button>
                          </div>
                          <div class="respuesta" style="margin-top:70px"><i class="textomsg"></i></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>                            
                                                    
                        