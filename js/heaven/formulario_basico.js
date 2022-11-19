// INGENIERO - FABIO GARCIA ALVAREZ ( 1077461284 )
function formulario(dialogo_flotante, ancho_dialgo)
{
    var obj = this; //Para hacer referencia al objecto principal(this) dentro de las funcniones internas		
    this.ancho_dialgo = ancho_dialgo;
    this.dialogo_flotante = dialogo_flotante;

    $("#btn_cancelar").click(function() {
        obj.ocultar_dialogo();
    });
    this.validar = function() {
        return true;
        //return validar("formulario");
    };

    this.validar_agregar = function() {
        return this.validar();
    };
    this.validar_modificar = function() {
        return this.validar();
    };

    this.iniciar_agregar = function() {
        return true;
    };
    this.iniciar_modificar = function() {
        return true;
    };


    this.mostrar_dialogo = function(titulo)
    {
        $('#btn_aceptar').unbind('click');
        if (this.dialogo_flotante == true)
        {
            $("#myModal").modal("show");
            $(".modal-title").html(titulo);
            $(".js-example-basic-single").select2('val','');  
            //dialogo('dialogo', titulo, this.ancho_dialgo);
        }
        else
        {
            var t = $(".titulo-formulario").text().trim();
            t = (t == "") ? titulo : titulo + " (" + t + ")";
            $("#formulario").prepend("<div class='ui-widget-header titulo2'>" +
                    t.toUpperCase() + "</div>");
            $("#formulario").css("width", this.ancho_dialgo + "px");
            $("#formulario").css("margin", "auto");

            //$("#dialog").show();
            $("#myModal").modal("show");
            $("#grid").parent().hide();
        }
    }


    this.ocultar_dialogo = function()
    {
        $('#btn_aceptar').unbind('click');
        if (this.dialogo_flotante == true) {
            //$('#dialog').dialog('destroy');
            $("#myModal").modal("hide");
        } else {
            $("#dialog div.titulo2").remove();
            $("#myModal").modal("hide");
            //$("#dialog").hide();
            $("#grid").parent().show();
        }
    }

    this.limpiar = function() {

        $("#formulario .select_auto").each(function(){
           $("#"+this.id).select2('destroy');
        });

        document.getElementById("formulario").reset();
        $('.select_auto').select2({ placeholder: 'Seleccione una opción...', });
       
       // $(".error").removeClass("error"); //Limpiar errores de validación
       // $("tr.soporte").remove(); // Codigo agregado para sistema de investigacion       
    }

    /* *********************************************************************************************************** */
    this.agregar = function()
    {
        obj.limpiar();
        obj.mostrar_dialogo('Agregar');
        desbloquear_entradas("formulario");

        //Ejecutar lo siguiente al darle click en el boton Aceptar
        $("#btn_aceptar").click(function()
        {
            if (obj.validar_agregar() == false)
                return; //Llamar a la funcion de validaci�n
            //Mostrar barra de progreso
            $("#formulario").append("<div class='progreso'> <img src='" + web_root + "img/pb.gif' /> <br/> Agregando... </div>");
            //Ocultar botones
            $("#formulario input:button").hide();

            $.ajax({
                type: "POST",
                url: page_root + "agregar",
                data: $("#formulario").values(),
                success: function(data)
                {
                    $("#formulario input:button").show(); //Mostrar botones
                    $("#formulario .progreso").remove(); //Eliminar barra de progrreso

                    try
                    {
                        var r = jQuery.parseJSON(data);
                        for (ind in r.bad_fields) //Marcar campos con error
                        {
                            $("#formulario *[name=" + r.bad_fields[ind] + "]").addClass("error");
                        }

                        alert(r.msg);
                        if (r.error == false)
                        {
                            obj.ocultar_dialogo();

                            var tr = grid.addRow(r.row, 0); //Agregar la nueva fila al principio
                            $(tr).addClass("recentAdd"); //Ponerle color a la nueva fila mediante clases
                            tr.setAttribute("id", 'tr_'+r.row.id);
                        }
                    }
                    catch (ex)
                    {
                        alert("Error desconocido");
                    }

                }
            });
        });

    };

    /* *********************************************************************************************************** */
    this.asignar = function()
    {
        this.limpiar();        
        var id = grid.getSelectedValues(0);
        $.get(page_root + "asignar", "id=" + id, function(data) {

            try {
                asignar_json("formulario", data);

                //Agregar soportes.
                var json = jQuery.parseJSON(data);
                for (indSop in json.soportes) {
                    var sop = json.soportes[indSop];
                    agregar_archivo(sop.id, sop.descripcion, "");
                }
                return data;
            }
            catch (ex)
            {
                alert("Error desconocido");
            }


        });

    };

    /* *********************************************************************************************************** */
    this.mostrar = function(id)
    {
        if($("#tr_"+id).hasClass('selected')) {}else{$("#tr_"+id).trigger("click");}
        if (grid.getSelectedCount() == 0)
        {
            alert("Debe seleccionar primero el registro a mostrar.");
        }
        else
        {
            this.mostrar_dialogo("Mostrar");
            $('#btn_aceptar').click(function() {
                $('#dialog').dialog('destroy')
            });
            this.asignar();
            bloquear_entradas("formulario");
        }
    };

    /* *********************************************************************************************************** */
    this.modificar = function(id)
    {
        if($("#tr_"+id).hasClass('selected')) {}else{$("#tr_"+id).trigger("click");}
        if (grid.getSelectedCount() == 0)
        {
            alert("Debe seleccionar primero el registro a modificiar.");
        }
        else
        {
            desbloquear_entradas("formulario");
            bloquear_no_modifibles("formulario");

            if (this.iniciar_modificar() != true)
                return;

            obj.mostrar_dialogo("Modificar");
            obj.asignar(false);


            $("#btn_aceptar").click(function()
            {
                if (obj.validar_agregar() == false)
                    return; //Llamar a la funcion de 

                if (!confirm("Realmente desea modificar el registro seleccionado?"))
                    return;
                //Mostrar barra de progreso
                $("#formulario").append("<div class='progreso'> <img src='" + web_root + "img/pb.gif' /> <br/> Modificando... </div>");
                //Ocultar botones
                $("#formulario input:button").hide();

                $.ajax({
                    type: "POST",
                    url: page_root + "modificar",
                    data: $("#formulario").values(),
                    success: function(data)
                    {
                        $("#formulario input:button").show(); //Mostrar botones
                        $("#formulario .progreso").remove(); //Eliminar barra de progrreso

                        try
                        {
                            var r = jQuery.parseJSON(data);
                            for (ind in r.bad_fields) //Marcar campos con error
                            {
                                $("#formulario *[name=" + r.bad_fields[ind] + "]").addClass("error");
                            }

                            alert(r.msg);

                            if (r.error == false)
                            {
                                obj.ocultar_dialogo();

                                var tr = grid.getSelectedRow(0); //Fila seleccionada
                                var tr2 = grid.addRow(r.row, tr); //Agregar la nueva despues de la fila seleccionada
                                $(tr2).addClass("recentAdd"); //Ponerle color a la nueva fila mediante clases
                                tr2.setAttribute("id", 'tr_'+id);
                                grid.removeRow(tr); //Eliminar la fila antigua (fila seleccionada)
                                setTimeout(function() { $('[data-toggle="tooltip"]').tooltip({ container: 'body' }); }, 1000);
                            }
                        }
                        catch (ex)
                        {
                            alert("Error desconocido");
                        }

                    }
                });
            });
        }

    };

    /* *********************************************************************************************************** */
    this.eliminar = function(id)
    {
        if($("#tr_"+id).hasClass('selected')) {}else{$("#tr_"+id).trigger("click");}
        if (grid.getSelectedCount() == 0)
        {
            alert("Debe seleccionar primero el registro a eliminar.");
        }
        else
        {
            this.mostrar_dialogo("Eliminar");
            this.asignar();

            bloquear_entradas("formulario");

            $("#btn_aceptar").click(function()
            {
                if (!confirm("Realmente desea eliminar el registro seleccionado?"))
                    return;

                $("#formulario").append("<div class='progreso'> <img src='" + web_root + "img/pb.gif' /> <br/> Eliminando... </div>");
                //Ocultar botones
                $("#formulario input:button").hide();

                $.ajax({
                    type: "POST",
                    url: page_root + "eliminar",
                    data: $("#formulario").values(),
                    success: function(data)
                    {
                        $("#formulario input:button").show(); //Mostrar botones
                        $("#formulario .progreso").remove(); //Eliminar barra de progrreso
                        try {
                            var r = jQuery.parseJSON(data);
                            alert(r.msg);
                            if (r.error == false)
                            {
                                obj.ocultar_dialogo();

                                var tr = grid.getSelectedRow(0) //Fila seleccionada
                                grid.removeRow(tr); //Eliminar la fila seleccionada
                            }
                        } catch (ex) {
                            alert("Error desconocido");
                        }
                    }
                });
            });
        }
    };

    this.buscar = function() {
        var filtros = $("#form-busqueda").values();
        grid.url = page_root + 'listar?' + filtros;
        grid.moveFirst();
    };
}
 