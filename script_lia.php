    <script src="<?php echo WEB_ROOT ?>js/heaven/rollups/aes.js"></script>
    <script type="text/javascript" src="<?php echo WEB_ROOT ?>js/jquery_ui/jquery-ui.js"></script>
    <script type="text/javascript" src="<?php echo WEB_ROOT ?>js/jquery/validation.js"></script> 
    <script type="text/javascript" src="<?php echo WEB_ROOT ?>js/heaven/general.js"></script>
    <script type="text/javascript" src="<?php echo WEB_ROOT ?>js/heaven/grid.js"></script>
    <script type="text/javascript" src="<?php echo WEB_ROOT ?>js/heaven/jquery.extra.js?t=1"></script>
   
    

     <link rel="stylesheet" href="<?php echo WEB_ROOT ?>plantilla/vendors/select2/css/select2.min.css" type="text/css">
     <script src="<?php echo WEB_ROOT ?>plantilla/vendors/select2/js/select2.min.js"></script>
     <script src="<?php echo WEB_ROOT ?>plantilla/assets/js/examples/select2.js"></script>
    

    <script type="text/javascript" src="<?php WEB_ROOT ?>js/heaven/formulario_basico.js"></script>   
    <script src="<?php WEB_ROOT ?>js/heaven/toastDemo.js"></script>
    <script src="<?php WEB_ROOT ?>js/heaven/desktop-notification.js"></script>
    <script src="<?php WEB_ROOT ?>js/heaven.js"></script>


    <script src="<?php WEB_ROOT ?>js/heaven/bb/billboard.min.js"></script>
    <script src="<?php WEB_ROOT ?>js/heaven/bb/billboard.pkgd.min.js"></script>
    <script src="<?php WEB_ROOT ?>js/heaven/bb/billboardjs-plugin-bubblecompare.min.js"></script>

    <script src="<?php WEB_ROOT ?>js/heaven/bb/billboardjs-plugin-stanford.min.js"></script>
    <script src="<?php WEB_ROOT ?>js/heaven/bb/billboardjs-plugin-textoverlap.min.js"></script>
    <link rel="stylesheet" href="<?php echo WEB_ROOT ?>js/heaven/bb/billboard.min.css" type="text/css">
 
    
  
    <script src="<?php echo WEB_ROOT ?>plantilla/vendors/dataTable/jquery.dataTables.min.js"></script>
    <script src="<?php echo WEB_ROOT ?>plantilla/vendors/dataTable/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo WEB_ROOT ?>plantilla/vendors/dataTable/dataTables.responsive.min.js"></script>

    
    <script type="text/javascript" src="<?php echo WEB_ROOT ?>js/datatable/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="<?php echo WEB_ROOT ?>js/datatable/buttons.flash.min.js"></script>
    <script type="text/javascript" src="<?php echo WEB_ROOT ?>js/datatable/jszip.min.js"></script>          
    <script type="text/javascript" src="<?php echo WEB_ROOT ?>js/datatable/pdfmake.min.js"></script>
    <script type="text/javascript" src="<?php echo WEB_ROOT ?>js/datatable/vfs_fonts.js"></script>
    <script type="text/javascript" src="<?php echo WEB_ROOT ?>js/datatable/buttons.html5.min.js"></script>
    <script type="text/javascript" src="<?php echo WEB_ROOT ?>js/datatable/buttons.print.min.js"></script> 

    <script src="<?php WEB_ROOT ?>js/multi_select/jquery.sumoselect.js"></script>
    <link href="<?php WEB_ROOT ?>js/multi_select/sumoselect.css" rel="stylesheet" />

   


    <script type="text/javascript">
        $(document).ready(function(e) {
           $("input, select, textarea").change(function(e){
              $(e.target).removeClass("error");  
           });

           set_token();

           $('.js-example-basic-multiple').select2({
              placeholder: 'Seleccione varias opciones...',
           });
           $('.js-example-basic-single').select2({
              placeholder: 'Seleccione una opción...',
           });
           
           $('.select_auto').select2({
              placeholder: 'Seleccione una opción...',
           });
           
           $('.select_auto_multiple').select2({
             placeholder: 'Seleccione varias opciones...',
           });
            
            $('.select_auto2').SumoSelect({search: true, searchText: 'Seleccione...'});
        });


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
            "url": "js/datatable/spanish.json"
          },
            "pageLength": pageLength,
            "order": [[ order, forma_orden ]],     
            dom: 'Bfrtip',
            buttons: [
                {
                   extend: 'excel',
                   title: 'Exportar Excel'
                }
              ]
          });
         
       }     
    }

    setTimeout(function() {
      $(".dt-button").addClass('btn');
      $(".dt-button").addClass('btn-outline-success');
    }, 100);
   
  }

    </script> 