function iniciar_todo() {
	listado_corrientes();
	//tablas_graficas();
}

function listado_corrientes() {
	

	$.ajax({

	    url:BASE_URL+'listado_corrientes',

	    type: 'POST',

	    dataType: "json",

	    data: {},

	success: function(data) {

          $("#recibe_corrientes").html(data.corrientes);

          $("#corrientes_seleccionados").html(data.seleccionados);

          tablas_graficas();

	   }

	});





}



function cambiar_fecha_inicio_corte() {

	var dialog = bootbox.dialog({
         message: '<p class="text-center">Recalculando valores...</p>',
         closeButton: false
       });


	var fecha = $("#fecha_inicio_corte").val();

	$.ajax({
	    url:BASE_URL+'cambiar_fecha_inicio_corte',
	    type: 'POST',
	    dataType: "json",
	    data: {fecha:fecha},
	success: function(data) {
         if (data.error==true) {
           msg($('.respuesta'),data.a,'error');
           $("#fecha_inicio_corte").val(fecha);
         }else{           
           $("#rango_fechas_texto").html(data.msg);iniciar_todo();
         }
         dialog.modal('hide');
	   }

	});





}



function cambiar_fecha_fin_corte() {

	var dialog = bootbox.dialog({
         message: '<p class="text-center">Recalculando valores...</p>',
         closeButton: false
    });


	var fecha = $("#fecha_fin_corte").val();
	$.ajax({

	    url:BASE_URL+'cambiar_fecha_fin_corte',
	    type: 'POST',
	    dataType: "json",
	    data: {fecha:fecha},
	success: function(data) {

        if (data.error==true) {
           msg($('.respuesta'),data.a,'error');
           $("#fecha_fin_corte").val(fecha);
         }else{           
           $("#rango_fechas_texto").html(data.msg);iniciar_todo();
         }
         dialog.modal('hide');


	   }

	});


}




function select_corriente(valor) {

	$.ajax({
	    url:BASE_URL+'seleccionar_corriente',
	    type: 'POST',
	    dataType: "json",
	    data: {id:valor},
	success: function(data) {
		if (data.error == true)
            {
                msg($(".respuesta"),data.msg,"error");
            }else{
            	msg($(".respuesta"),data.msg,"exito");
            }
         
         	listado_corrientes();  

	   }

	});	



}



function jaz_mgs(msg,w='250px',t=8000) {

	var msg_jaz = "<div style='width:"+w+";height:30px;border:none;overflow:hidden'> <div style='width:10%;border:none;float:left'><img src='img/pie.gif' style='width:100%'></div> <div style='width:89%;;float:left;padding-top:5px;padding-left:3px'> "+msg+"</div>     </div>";
	alertify.closeLogOnClick(true)
    alertify.delay(2000);
    alertify.logPosition("bottom right");
    alertify.success(msg_jaz);

}

function jaz_mgs_terminos(msg,w='250px',t=150000) {

	var msg_jaz = "<div style='width:"+w+";height:30px;border:none;overflow:hidden'> <div style='width:10%;border:none;float:left'><img src='img/pie.gif' style='width:100%'></div> <div style='width:89%;;float:left;padding-top:5px;padding-left:3px'> "+msg+"</div>     </div>";
    //alert(t);
	alertify.closeLogOnClick(true)
    alertify.delay(2000);
    alertify.logPosition("bottom right");
    alertify.success(msg_jaz);

}



// FUNCIONE SGENERALES


function tablas_graficas() {

	// SOLO SE EJECUTA SI ESTA EN ESA VISTA

	if ($("#inicio_datos").length) { datos_inicio();graficas_inicio(); };


}

 function datos_inicio() {
     

	$.ajax({

	    url:BASE_URL+'datos_inicio',
	    type: 'POST',
	    dataType: "json",
	    data: {},
	success: function(data) {
				
				$("#total_expedientes_e").html(data.todos);
				$("#total_expedientes_s").html(data.todos_s);
				$("#total_expedientes_evaluacion").html(data.evaluacion);
				$("#total_expedientes_seguimiento").html(data.seguimientos);
				$("#total_expedientes_en_terminos").html(data.en_terminos);
				$("#total_expedientes_fuera_terminos").html(data.fuera_de_terminos);
				$("#total_expedientes_archivados").html(data.archivados);
				$("#total_expedientes_usuario").html(data.usuario);
				$("#total_expedientes_en_terminos_seguimiento").html(data.en_terminos_seguimiento);
				$("#total_expedientes_fuera_terminos_seguimiento").html(data.fuera_de_terminos_seguimiento);

	   }

	});


}
 function graficas_inicio() {
    

	grafica_expedientes_corrientes_mes();
    grafica_expedientes_corrientes();

    grafica_expedientes_corrientes_mes_seguimiento();
    grafica_expedientes_corrientes_seguimiento();

}
