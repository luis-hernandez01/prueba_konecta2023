<?php
$id =$_GET['q'];
$sql = "SELECT p.id, p.nombre_producto, p.referencia, p.precio, CONCAT(p.peso, u.sigla) As pesos, c.nombre AS nombre_categoria , p.stock,p.fecha_creacion FROM productos p, unidad_masa u, categoria c
WHERE p.id_unidad_masa=u.id AND p.id_categoria=c.id and p.id='$id'";

$datos = $db->select_row($sql);

?>

<div class="container-fluid">

    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">



                    <div class="container">
    <div class="row">
        <div class="col-lg-12 bg-primary p-2 text-white mt-2 text-center text-capitalize">
            Detalles del producto.
        </div>
    </div>
    <div class="row mt-4">
        <!-- <div class="col-lg-1 text-center">
            <ul class="nav nav-tabs row text-center pro-details" id="myTab" role="tablist">
                <li class="nav-item col-lg-12 mb-2">
                    <img class="img-fluid active h-100" src="https://pbs.twimg.com/media/ENktSOKU0AA9Y-6.jpg"  id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"/>
                </li>
            </ul>
        </div> -->
        <div class="col-lg-4 text-center border-right border-secondery">
            <div class="tab-content row h-100 d-flex justify-content-center align-items-center" id="myTabContent">
                <div class="tab-pane fade show active col-lg-12" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <img class="img-fluid" src="https://www.gipsycorp.com.ve/wp-content/uploads/2022/09/producto-sin-imagen-27.png" />
                </div>
                
            </div>
        </div>
        <div class="col-lg-7">
            <h5>
                <?php echo $datos['nombre_producto'] ?>
            </h5>
            <h3>
               $<?php echo $datos['precio'] ?>
            </h3>
            <p>
                <?php echo $datos['nombre_categoria'] ?>
            </p>
            <ul>
                <li class="pb-2"><b>Peso</b> <?php echo $datos['pesos'] ?></li>
                
            </ul>
            <ul>
                <li class="pb-2"><b>Stock</b> <?php echo $datos['stock'] ?></li>
                
            </ul>
            <ul>
                <li class="pb-2"><b>Refrencia</b> <?php echo $datos['referencia'] ?></li>
                
            </ul>
            <form id="formulario" method="POST" class="form-horizontal" style="margin:auto;width:95%">
                        <div class="form-row">
                        <div class="col-md-12 mb-12">
                            <div class="form-group">
                                <label for="relevancia">Cantidad</label>
                                <input type="number" class="form-control" min="1" require id="cantidad" name="cantidad"
                                value="1">
                            </div>
                        </div>
                    </div>
                    <?php if ($datos['stock']==0) { ?>
        <div class="alert alert-danger">
            <span><strong>Alerta: </strong> No hay stock disponible.</span>
        </div>
                   <?php }else{ ?>
                    <div class="row">
                          <div class="col-md-12" style="border:none;float:right">
                          
                              <div class="dlg-acciones">
                                <button type="submit"  id="btn_aceptar" class="btn btn-primary btn-block btn-flat" style="float:right">Comprar producto</button>
                              </div>
                          </div>
                      </div>
                  <?php } ?>
                          
                    </form>


        </div>
    </div>
</div>








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
      $("#formulario").submit(function(){
        var formData = new FormData($("#formulario")[0])
            $.ajax({
            url:page_root + "aceptar?q="+<?php echo $_GET[q] ?>,
            type: "POST",
              data: formData,
              dataType: "JSON",
              cache: false,
              contentType: false,
              processData: false,
            // data: $("#formulario_basico").serialize(),
            beforeSend: function(){
                 $(".cargar").css({"display":"block","font-size":"100"});
                 $(".acciones").css({"display":"none","font-size":"100"});
            },beforeSend: function(xhr){
              xhr.setRequestHeader("Authorization",TOKEN_GLOBAL); 
            },
            success: function(data){
            var r = data;
            if (r.error == true){

                for (ind in r.bad_fields)
                {
                    $("#" + r.bad_fields[ind]).addClass("error");
                }
                swal("Validar la información requerida!", r.msg, "warning");

            }else{
              swal("Exito!", r.msg, "success");
              setTimeout(function() { window.location.href = "inicio" }, 2400);
                           
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