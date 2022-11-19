function tablero_grafica()
{

    var obj = this; //Para hacer referencia al objecto principal(this) dentro de las funcniones internas    
    this.base_url = "";
    this.etapa = "";
    this.prefijo = "";
    this.dominio = "";
    this.token = "";
    this.filtro = 0;

    this.async = false;
    this.crossDomain = false;
    this.method = "POST";
    this.processData = true;
    this.headers = {};

    // SET
    this.set_url= function(base_url){        
      this.base_url = base_url;
    };

    this.set_etapa_prefijo= function(etapa,prefijo){        
      this.etapa = etapa;
      this.prefijo = prefijo;
      obj.get_datos(prefijo);
    };

     this.set_token= function(token){        
      this.token = token;
    };



    // CONFIGURACIÓN GENERAL DE AJAX
    this.getSettings= function(datos_form,servie){     

       var settings = { 
           "async":this.async, 
           "crossDomain":this.crossDomain,
           "url": this.base_url+servie,
           "method": this.method,
           "headers": this.headers,
           "dataType": "JSON",
           "processData": this.processData,
           "data": datos_form 
       };

      return settings;
    };
    

  

  this.ver_mas_dependencia=function(data){
      obj.ver_mas_tablas(data.id,1);
   };


  this.ver_mas_procesos=function(data){
      obj.ver_mas_tablas(data.id,5);
   };


  this.ver_mas_tablas=function(data,op){
       var img_cargando = '<img src="img/cargar.gif" alt="" style="margin-left: 40%;">';
       $("#recibe_informacion_tabla").html(img_cargando);
       $("#informacion_tabla_modal").modal();
       setTimeout(function() {
        

        var settings = obj.getSettings({data: data,op:op},'detalles');
        $.ajaxSetup(settings);
        $.ajax({
            beforeSend: function(xhr){
                xhr.setRequestHeader("Authorization",obj.token); 
            },
            success: function(r){            
              if (r.error==true) {
                swal('Error', r.msg, "error");
              }else{
                
                $("#recibe_informacion_tabla").html(r.tabla);
                setTimeout(function() { tablesorte("info"); }, 600);
              }
            },
            error: function(msg) {
                swal('Error fatal', "Contactar al administrador", "error");
            }
        }); 



       }, 300);
   };


    this.graficar=function(filtro){

       var settings = this.getSettings({},'aceptar');
        $.ajaxSetup(settings);
        $.ajax({
            beforeSend: function(xhr){
                xhr.setRequestHeader("Authorization",obj.token); 
            },
            success: function(r){            
              if (r.error==true) {
                swal('Error', r.msg, "error");
              }else{
                graficar_chart('chart1',10,r.dependencias,tg.ver_mas_dependencia);
                graficar_chart('chart2',5,r.procesos,tg.ver_mas_procesos);
                //graficar_chart('chart3',3,r.grupos,obj.ver_mas);
              }
            },
            error: function(msg) {
                swal('Error fatal', "Contactar al administrador", "error");
            }
        });

    };







    



} // FIN OBJETO




