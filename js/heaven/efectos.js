
$(document).ready(function () {
    //Disable cut copy paste
   /* $('body').bind('cut copy paste', function (e) {
        e.preventDefault();
    });*/
   
    //Disable mouse right click
    $("body").on("contextmenu",function(e){
        return false;
    });
});


 var hash_global;
$(document).ready(function() {
  set_login();
});



       $(document).ready(function() {
           $("#restaurar_clave").hide();
           $("#restaurar_usuario").hide();
           $("#login").show();
       });

       function activar_login() {
          $("#restaurar_clave").hide();
          $("#restaurar_usuario").hide();
          $("#login").show();
       }

       function activar_usuario() {
          $("#restaurar_clave").hide();
          $("#restaurar_usuario").show();
          $("#login").hide();
       }

        function activar_restaurar() {
          $("#restaurar_clave").show();
          $("#restaurar_usuario").hide();
          $("#login").hide();
       }

function msg(cont,texto,tipo){

    $('.textomsg',cont).html(texto);
    $(cont).addClass('alert');
    if(tipo=='error'){
        $(cont).addClass('alert-danger');
    }else if(tipo=='exito'){
        $(cont).addClass('alert-success');
    }

    $(cont).show();
    $(cont).fadeIn(3000).delay(4500).fadeOut(2500);
    //var top = $(cont).position().top  + 500;
    //$(window).scrollTop(top);
}

$(function(){

        $('#formulario').submit(function(){
           iniciar_sesion();
           return false;         
        }); 

    return false;

})
function iniciar_sesion() {
    
    
    var c = $("#clave").val();
    var u = $("#usuario").val();
    var validador = $("#validador").val();    
    var clave =  CryptoJS.AES.encrypt(c, hash_global).toString();
    var usuario =  CryptoJS.AES.encrypt(u, hash_global).toString();

    $.ajax({
           url:page_root + "iniciar",
           type: "POST",
           async: false,
           dataType: 'JSON',
           data: {clave:clave,usuario:usuario,validador:validador},
        beforeSend: function (xhr){ 
           xhr.setRequestHeader('Authorization',hash_global); 
        },
        success: function(data){      
            try{
                var r = data;
                if (r.error == true)
                {
                    msg($('.respuesta'),r.msg,'error');
                    $("#validador").val('');
                
                }else{
                    window.location.href = web_root;
                }

            }catch (ex) {
               alert('Error desconocido.');
            }

        },error: function(msg) {
               alert('ATENCION, envia de nuevo tu informacion.');
        }
    });

}









$(function(){

    $('#restaurar_clave').submit(function(){
        $.ajax({
            url:page_root + "restaurar_clave",
            type: "POST",
            dataType: 'JSON',
            data: $("#restaurar_clave").serialize(),
            beforeSend: function (xhr){ 
             xhr.setRequestHeader('Authorization',hash_global); 
            },
            success: function(data){
             try{

                var r = data;
                if (r.error == true){
                    msg($('.respuesta'),r.msg,'error');
                    $("#validador_restaurar").val('');
                }else{
                    msg($('.respuesta'),r.msg,'exito');
                }
             }catch(ex){
                alert('Error desconocido.');
             }
            },
            error: function(msg) {
              alert('ATENCION, envia de nuevo tu informacion.');
            }
        });
        
        return false;

    })

}) 



$(function(){

   $('#registro_usuario').submit(function(){
        $.ajax({
              url:page_root + "registro",
              type: "POST",              
              dataType: 'JSON',              
              data: $("#registro_usuario").serialize(),
             beforeSend: function (xhr){ 
               xhr.setRequestHeader('Authorization',hash_global); 
             },
            success: function(data){

             try{
                var r = data;
                if (r.error == true){
                    msg($('.respuesta'),r.msg,'error');
                    $("#validador_registro").val('');
                }else{
                    msg($('.respuesta'),r.msg,'exito');
                    document.getElementById("registro_usuario").reset();
                    cancelar_registro();
                }

             }catch (ex) {
                alert('Error desconocido.'+ex);
             }

            },error: function(msg) {
              alert('ATENCION, envia de nuevo tu informacion.');
            }
        });

            return false;

    })

}) 

function set_login() {
    $.ajax({
        url: page_root+'set_login',
        type: 'POST',
        dataType: 'json',
        data: {},
    })
    .done(function(r) {
      hash_global = CryptoJS.MD5(r.data+"/()0-(**fg@A").toString();
    });
    
}


