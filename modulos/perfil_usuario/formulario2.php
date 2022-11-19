<?php
if ($_SESSION['contratista']) {
  $datos = $db->select_row("SELECT * FROM persona WHERE id=".$_SESSION['persona_id']);
}else{
  $datos = $db->select_row("SELECT * FROM supervisor WHERE id=".$_SESSION['persona_id']);
}

?>
<script type="text/javascript">
function ver() {
    $(".cargar").css({"display":"none","font-size":"100"});
    $(".acciones").css({"display":"block","font-size":"100"});
}


$(function(){
      $(".cargar").css({"display":"none","font-size":"100"});
      $("#formulario_basico").submit(function(){
            $.ajax({
            url:page_root + "aceptar",
            type: "POST",
            dataType: "JSON",
            data: $("#formulario_basico").serialize(),

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
                alert(r.msg);
                ver();
            } else
            {
               alert("Datos actualizados con exito");
                ver();
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

$(function(){
      $(".cargar").css({"display":"none","font-size":"100"});
      $("#formulario_clave").submit(function(){
            $.ajax({
            url:page_root + "cambiar_clave",
            type: "POST",
            dataType: "JSON",
            data: $("#formulario_clave").serialize(),

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
                alert(r.msg);
                ver();
            } else
            {
                alert(r.msg);
                window.location.href="iniciar-sesion";
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

$(function(){

        $('#formulario_foto').submit(function(){

          var formData = new FormData($(".frm_foto")[0])

            $.ajax({
            url:page_root + "cambiar_foto",
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,

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
                alert(r.msg);
                ver();
            } else
            {
               alert("Foto actualizada con exito");
                location.reload();
            }

            },
            error: function(msg) {
            alert('ATENCION, envia de nuevo tu informacion.');
            ver();
            }
            });
            return false;
            })
}) 


</script>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#datos_basicos" aria-controls="home" role="tab" data-toggle="tab">Datos Basicos</a></li>
    <li role="presentation"><a href="#datos_acceso" aria-controls="profile" role="tab" data-toggle="tab">Datos de acceso</a></li>
    <li role="presentation"><a href="#datos_foto" aria-controls="profile" role="tab" data-toggle="tab">Cambiar foto</a></li>
    <li role="presentation"><a href="#datos_firma" aria-controls="profile" role="tab" data-toggle="tab">Firma digital</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="datos_basicos">
      

      <div class="login-box" style="overflow:hidden;opacity: 0.9;margin:0px;width:100%">
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Cambiar Datos basicos</p>

   <form id="formulario_basico">
                <div class="row">
                   <div class="col-md-6"><label>Documento</label><input type="number" id="identifica" name="identifica" title="Documento" maxlength="15" value="<?php echo $datos['identifica']; ?>"  class="form-control"/></div>
                   <div class="col-md-6"><label>Tipo</label>
                      <select id="tipoide" name="tipoide" title="Tipo">
                        <?php llenar_combo("SELECT id, nombre FROM tipo_documento ORDER BY nombre",false,$datos['tipoide']); ?>
                      </select> 
                   </div>
                </div>

                <div class="row">
                   <div class="col-md-6"><label>Primer nombre</label><input type="text" id="nombre1" name="nombre1" title="Nombre1" maxlength="80" value="<?php echo $datos['nombre1']; ?>" /></div>
                   <div class="col-md-6"><label>Segundo nombre</label><input type="text" id="nombre2" name="nombre2" title="Nombre2" maxlength="80" value="<?php echo $datos['nombre2']; ?>" /></div>
                </div>

                <div class="row">
                   <div class="col-md-6"><label>Primer apellido</label><input type="text" id="apellido1" name="apellido1" title="Nombre completo" maxlength="80" value="<?php echo $datos['apellido1']; ?>" /></div>
                   <div class="col-md-6"><label>Segundo apellido</label><input type="text" id="apellido2" name="apellido2" title="Apellido2" maxlength="80" value="<?php echo $datos['apellido2']; ?>" /></div>
                </div>
                <br>
                <div class="dlg-acciones">
                   <input type="submit" id="btn_aceptar" value="Aceptar" class="btn btn-block btn-success" style="margin-left:10px;width:80px;float:right"/>
                </div>

      </form>
  </div>
  <!-- /.login-box-body -->
</div>

    </div>
   
<!-- datos acceso -->
    <div role="tabpanel" class="tab-pane" id="datos_acceso">
      
<div class="login-box" style="overflow:hidden;opacity: 0.9;">
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Cambiar contraseña</p>

      <form id="formulario_clave" method="POST"  >
     <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Contraseña vieja" id="clave1" name="clave1" value="" maxlength="12" required>
        
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Contraseña nueva" id="clave2" name="clave2" value="" maxlength="12" required>
        
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Confirmar contraseña" id="clave3" name="clave3" value="" maxlength="12" required>
        
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        
          <div class="col-xs-4" style="border:none;float:right">
          <div class="cargar" style="float:right;">  <img src="img/loader.gif" class="img-responsive"></div>
          <div class="dlg-acciones">
          <button type="submit" class="btn btn-primary btn-block btn-flat" style="float:right">Cambiar</button>
          </div>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.login-box-body -->
</div>

    </div>


<!-- DATOS FOTO -->
<div role="tabpanel" class="tab-pane" id="datos_foto">
 

 <div class="login-box" style="overflow:hidden;opacity: 0.9;">
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Cambiar foto</p>

   <form id="formulario_foto"  enctype="multipart/form-data" class="frm_foto">
     <div class="form-group has-feedback">

      <center><img src="<?php echo $datos['foto'] ?>" class="img-circle" style="border:1px solid;width:160px"></center>

      </div>

      <div class="form-group has-feedback">
       <input   type="file" name="archivo" id="archivo"  class="form-control" required/>
        <span class="glyphicon glyphicon-picture form-control-feedback"></span>
      </div>
      <div class="row">
        
        <div class="col-xs-4" style="border:none;float:right">
          <div class="cargar" style="float:right;">  <img src="img/loader.gif" class="img-responsive"></div>
          <div class="dlg-acciones">
          <button type="submit" class="btn btn-primary btn-block btn-flat" style="float:right">Cambiar</button>
          </div>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.login-box-body -->
</div>

 </div>


 <!-- DATOS FIRMA -->
<div role="tabpanel" class="tab-pane" id="datos_firma">
 

 <div class="login-box" style="overflow:hidden;opacity: 0.9;">
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Cambiar firma</p>

   <form id="formulario_foto"  enctype="multipart/form-data" class="frm_foto">
     <div class="form-group has-feedback">

      <center><img src="<?php echo $datos['foto'] ?>" class="img-circle" style="border:1px solid;width:160px"></center>

      </div>

      <div class="form-group has-feedback">
       <input   type="file" name="archivo" id="archivo"  class="form-control" required/>
        <span class="glyphicon glyphicon-picture form-control-feedback"></span>
      </div>
      <div class="row">
        
        <div class="col-xs-4" style="border:none;float:right">
          <div class="cargar" style="float:right;">  <img src="img/loader.gif" class="img-responsive"></div>
          <div class="accionesS">
          <button type="submit" class="btn btn-primary btn-block btn-flat" style="float:right">Cambiar</button>
          </div>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.login-box-body -->
</div>

 </div>


</div>