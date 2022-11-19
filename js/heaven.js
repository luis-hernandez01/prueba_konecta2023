function agregar_favorito (id) {
	$.ajax({
		url: 'inicio/agregar_favorito',
		type: 'POST',
		dataType: 'json',
		data: {id:id},
	})
	.done(function(r) {
		if (r.error==true) {
           msg($(".respuesta"),r.msg,"error");
           $("#menu_"+id).removeClass('text-success');
		}else{
		   msg($(".respuesta"),r.msg,"exito");
		   $("#menu_"+id).addClass('text-success');
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}