<?php
$datos = $db->select_row("SELECT * FROM general.persona WHERE id=".$_SESSION['persona_id']);
?>
    <div class="card card-body overflow-hidden" data-backround-image="img/fondo_login.jpg">
        <div class="p-3 d-lg-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div>
                    <figure class="avatar avatar-xl mr-3">
                        <img src="<?php echo $datos['foto'] ?>" class="rounded-circle" alt="...">
                    </figure>
                </div>
                <div class="text-black">
                    <h3>
                       <?php echo $datos['nombre1']; ?>  <?php echo $datos['nombre2']; ?>  <?php echo $datos['apellido1']; ?>  <?php echo $datos['apellido2']; ?>
                        <a href="#" data-toggle="tooltip" title="Profile Edit"
                           class="font-size-15 text-white btn-floating m-l-5">
                            <i class="fa fa-pencil"></i>
                        </a>
                    </h3>
                    <p class="mb-0 opacity-8"><?php echo $db->select_one("SELECT nombre FROM admin_rol WHERE id='$_SESSION[usuario_rol]' "); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-sm">
        <div class="col-md-8">

            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills flex-column flex-sm-row" id="myTab" role="tablist">
                        <li class="flex-sm-fill text-sm-center nav-item">
                            <a class="nav-link active" id="hometimeline-tab" data-toggle="tab" href="#timeline"
                               role="tab" aria-selected="true"><span class="badge badge-light m-l-5">1</span> Datos Básicos</a>
                        </li>
                        <li class="flex-sm-fill text-sm-center nav-item">
                            <a class="nav-link" id="photos-tab" data-toggle="tab" href="#photos" role="tab"
                               aria-selected="false"> <span class="badge badge-light m-l-5">2</span> Datos de Accesos</a>
                        </li>
                        <li class="flex-sm-fill text-sm-center nav-item">
                            <a class="nav-link" id="earnings-tab" data-toggle="tab" href="#earnings" role="tab"
                               aria-selected="false"><span class="badge badge-light m-l-5">3</span> Foto de Perfil</a>
                        </li>
                    </ul>
                    <div class="tab-content p-t-30" id="myTabContent">
                        <div class="tab-pane fade show active" id="timeline" role="tabpanel">
                        
         <form id="formulario_basico">
                <div class="row">
                   <div class="col-md-6">
                     <label>Documento</label>
                     <input type="number" id="identifica" name="identifica" title="Documento" maxlength="15" value="<?php echo $datos['identifica']; ?>" />
                   </div>
                   <div class="col-md-6"><label>Tipo</label>
                      <select id="tipoide" name="tipoide" title="Tipo">
                        <?php llenar_combo("SELECT id, nombre FROM general.tipo_documento ORDER BY nombre",false,$datos['tipoide']); ?>
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
                      <div class="row">
                      
                      <div class="col-md-12" style="border:none;float:right">
                      <div class="cargar" style="float:right;">  <img src="img/loader.gif" class="img-responsive"></div>
                      <div class="dlg-acciones">
                        <button type="submit"  id="btn_aceptar" class="btn btn-primary btn-block btn-flat" style="float:right">Actualizar Datos Personales</button>
                      </div>
                      </div>
                      <!-- /.col -->
                      </div>
            </form>
                        </div>
                        <div class="tab-pane fade" id="photos" role="tabpanel">
  
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
        
          <div class="col-md-12" style="border:none;float:right">
          <div class="cargar" style="float:right;">  <img src="img/loader.gif" class="img-responsive"></div>
          <div class="dlg-acciones">
          <button type="submit" class="btn btn-primary btn-block btn-flat" style="float:right">Actualizar Contraseñas</button>
          </div>
        </div>
        <!-- /.col -->
      </div>
    </form>

                        </div>


                        <div class="tab-pane fade" id="earnings" role="tabpanel">

  <form id="formulario_foto"  enctype="multipart/form-data" class="frm_foto">
     <div class="form-group has-feedback">
      <center><img src="<?php echo $datos['foto'] ?>" class="img-circle" style="border:1px solid;width:160px"></center>
      </div>
      <div class="form-group is-empty is-fileinput">
              <!--<label class="control-label" for="inputFile3">File</label>-->
              <input type="file" type="file" name="archivo" id="archivo" accept="image/*" class="form-control" required/>
      </div>
      <div class="row">
        
        <div class="col-md-12" style="border:none;float:right">
          <div class="cargar" style="float:right;">  <img src="img/loader.gif" class="img-responsive"></div>
          <div class="dlg-acciones">
          <button type="submit" class="btn btn-primary btn-block btn-flat" style="float:right">Actualizar Foto</button>
          </div>
        </div>
        <!-- /.col -->
      </div>
    </form>

                        </div>




                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title d-flex justify-content-between align-items-center">
                        Información
                    </h6>
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Documento:</div>
                        <div class="col-6"><?php echo $datos['identifica']; ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Primer Nombre:</div>
                        <div class="col-6"> <?php echo $datos['nombre1']; ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Segundo Nombre:</div>
                        <div class="col-6"> <?php echo $datos['nombre2']; ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Primer Apellido:</div>
                        <div class="col-6"><?php echo $datos['apellido1']; ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Segundo Apellido:</div>
                        <div class="col-6"><?php echo $datos['apellido2']; ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Correo:</div>
                        <div class="col-6"><?php echo strtolower($datos['correo']); ?></div>
                    </div>

                </div>
            </div>
       </div>
    </div>




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
$(function(){
        $('#formulario_firma').submit(function(){
          var formData = new FormData($(".frm_firma")[0])
            $.ajax({
            url:page_root + "cambiar_firma",
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
               alert("Firma actualizada con exito");
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