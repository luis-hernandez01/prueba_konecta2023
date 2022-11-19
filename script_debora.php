

<script type="text/javascript" >

$(document).ready(function(e) {


$('.js-example-basic-multiple').select2({
  placeholder: 'Seleccione varias opciones...',
  theme: "bootstrap4"
});
$('.js-example-basic-single').select2({
  placeholder: 'Seleccione una opción...',
  theme: "bootstrap4"
});

$('.select_auto').select2({
  placeholder: 'Seleccione una opción...',
  theme: "bootstrap4"
});

$('.select_auto_multiple').select2({
  placeholder: 'Seleccione varias opciones...',
  theme: "bootstrap4"
});

//$('.select_auto').SumoSelect({search: true, searchText: 'Seleccione...'});
$(".placeholder").html('Seleccione...');
$(".placeholder").html('Seleccione...');
$("select").addClass("form-control");
$("input[type='text']").addClass("form-control");
$("input[type='date']").addClass("form-control");
$("input[type='number']").addClass("form-control");
$("input[type='email']").addClass("form-control");
$("table").addClass("table table-hover");
$("#form-busqueda").hide();


<?php
//Ocultar botones a los que no se tiene permiso
$sql = "SELECT 
                        a.accion, a.requiere_permiso, pa.id AS permiso
                FROM
                        admin_accion a
                                LEFT JOIN
                        admin_permiso_accion pa ON a.id = pa.accion and pa.rol='$_SESSION[usuario_rol]'
                WHERE
                        a.menu = '" . MENU . "' AND a.requiere_permiso='S'";
$rs = $db->query($sql);
while ($rw = $db->fetch_assoc($rs)) {
    if ($rw['permiso'] == "")
    {
        echo '$(".accion-' . $rw['accion'] . '").hide();';
    }
}
?>

            //Manejar ruta de menú  y menú activo
<?php
$rw = $db->select_row("SELECT * FROM admin_menu WHERE menu='" . MENU . "'");
$ruta = $rw['nombre'];
if ($rw['padre'] != "") {
    $rw = $db->select_row("SELECT * FROM admin_menu WHERE menu='$rw[padre]'");
    $ruta = $rw['nombre'] . " <b>/</b> " . $ruta;

    echo '$(".menur").removeClass("active");
          $(".menur_collapse").removeClass("show");';
    echo '$(".menup-' . $rw['menu'] . '").addClass("active");';
    echo '$(".menupc-' . $rw['menu'] . '").addClass("show");';
    echo '$(".menupc-' . $rw['menu'] . '").attr("aria-expanded","true");';
}
?>
            

            $(".menu-"+menu).addClass("active");
            $("#ubicacion").html("<b>Usted esta en:</b> <?php echo $ruta ?>");
        });


    </script>



    <script type="text/javascript">



function tablesorte(id,pageLength=25,order=0,forma_orden="asc") {
  var nFilas = $('#'+id).length;

  if (nFilas<=1) {
      
       if($("#"+id).hasClass('dataTable')) {

       }else{
         
        $.fn.dataTable.ext.errMode = 'none'; 
                
          $('#'+id).DataTable( {
            "scrollX": true,
            "fixedHeader": true,
            "responsive": true,
            "language": {
            "url": "js/assets/spanish.json"
          },
            "pageLength": pageLength,
            "order": [[ order, forma_orden ]],     
            dom: 'Bfrtip',
            buttons: [
                {
                   extend: 'excel',
                   title: 'Reporte'
                },
                {
                  extend: 'csv',
                  title: 'Reporte'
                }
                //'copy', 'csv', 'excel', 'pdf', 'print'
              ]
          } );
        
         //setTimeout(function(){ $(".buttons-copy").html('<span>Copiar</span>');
         //$(".buttons-print").html('<span>Imprimir</span>'); }, 700); 
         
       }  
       
       
  }
   
  }






</script>


