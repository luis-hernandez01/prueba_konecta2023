<script type="text/javascript">

    $(document).ready(function() {
        $("input:text, select").change(function() {
            calcular_ancho();
        });
    });

    function generar() {
        $.post(page_root + "generar", $("#f").values(), function(data) {
            try {
                var r = jQuery.parseJSON(data);
                alert(r.msg);
                if (r.error == false) {
                    //document.getElementById("f").reset();

                }
            } catch (ex) {
                alert(data);
            }
        });
    }

    function verificar() {

        $.post(page_root + "verificar", $("#f").values(), function(data) {
            $("#div-verificar").html(data);
        });

    }

    function seleccionar_tipo_acceso(v) {
        if (v == 7) {
            $("#div-roles").show();
        } else {
            $("#div-roles").hide();
        }

    }

    function cargar_campos() {
        $("#div-campos").html("<b>Cargando...</b>");
        $.post(page_root + "cargarCampos", $("#f").values(), function(data) {
            $("#div-campos").html(data);
        });
    }

    function calcular_ancho() {
        var p = $("#tamano").val();
        var o = $("#orientacion").val();
        var mizq = $("#mizq").val();
        var mder = $("#mder").val();

        var ancho = 0;
        if (o == "P") {
            ancho = 612;
        } else if (p == "LETTER") {
            ancho = 792;
        } else if (p == "LEGAL") {
            ancho = 1008;
        }
        var a = 0;
        $(".input-ancho").each(function(i, e) {
            a += parseInt($(e).val());
        });

        ancho = ancho - mizq - mder - 30;
        $("#total_ancho").html(a + "/" + ancho);
        
        // case 'LETTER': {$format = array(612.00,792.00); break;}
        // case 'LEGAL': {$format = array(612.00,1008.00); break;}
    }
</script>


<div style="width: 90%; margin: auto;overflow:auto">
    <div class="ui-state-active" style="height:26px; line-height:26px; border-top-left-radius:10px; border-top-right-radius:10px; border-bottom:none">
        <div style="text-align:center; font-weight:bold; font-size:12pt"> 
            <span>ASISTENTE DE CREACIÓN DE REPORTES</span> 

        </div>

    </div>

    <form id="f" method="post"  target="_blank"
          action="<?php echo PAGE_ROOT . "vistaPrevia" ?>"  
          class="ui-dialog-content ui-widget-content" style="padding: 10px">

        <div id="f1">
            <table style="width: 100%"> 

                <tr class='ui-widget-header'>
                    <th colspan="3">CONFIGURACIÓN DEL MENU</th>
                </tr>

                <tr>
                    <td class="tdi">Menú principal</td>
                    <td class="tdc">:</td>
                    <td class="tdd">
                        <select id="menu_principal" name="menu_principal">
                            <?php
                            llenar_combo("SELECT menu, nombre FROM admin_menu WHERE ruta IS NULL ORDER BY nombre", true);
                            ?>
                        </select>
                    </td>  
                </tr>                


                <tr>
                    <td class="tdi">URL Menú</td>
                    <td class="tdc">:</td>
                    <td class="tdd">
                        <input type="text" name="menu" id="menu" value="" onblur="verificar()"/>
                    </td>  
                </tr>    


                <tr>
                    <td class="tdi">Titulo menu</td>
                    <td class="tdc">:</td>
                    <td class="tdd">
                        <input type="text" name="titulo_menu" id="titulo_menu" value=""/>
                    </td>  
                </tr>    

                <tr>
                    <td class="tdi">Titulo formulario</td>
                    <td class="tdc">:</td>
                    <td class="tdd">
                        <input type="text" name="titulo_formulario" id="titulo" value=""/>
                    </td>  
                </tr>

                <tr>
                    <td class="tdi">Tipo de acceso</td>
                    <td class="tdc">:</td>
                    <td class="tdd">
                        <select id="tipo_acceso" name="tipo_acceso" onchange="seleccionar_tipo_acceso(this.value);">
                            <?php
                            llenar_combo("SELECT codigo, descripcion FROM admin_acceso ", false, '7');
                            ?>
                        </select>
                    </td>  
                </tr>

                <tr>
                    <td class="tdi">Ruta</td>
                    <td class="tdc">:</td>
                    <td class="tdd">
                        <input type="text" name="ruta" id="ruta" value="" onblur="verificar()"/>
                    </td>  
                </tr>


            </table>
        </div>

        <div id="div-verificar">

        </div>

        <table style="width: 100%"> 

            <tr class='ui-widget-header'>
                <th colspan="3">CONFIGURACIÓN DE LA PAGINA</th>
            </tr>

            <tr>
                <td class="tdi">Tamaño papel</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <select id="tamano" name="tamano">
                        <option value="LETTER">CARTA</option>
                        <option value="LEGAL">OFICIO</option>
                    </select>
                </td>  
            </tr> 

            <tr>
                <td class="tdi">Orientación</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <select id="orientacion" name="orientacion">
                        <option value="P">VERTICAL</option>
                        <option value="L">HORIZONTAL</option>
                    </select>
                </td>  
            </tr>   

            <tr>
                <td class="tdi">Margen izquierdo</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <input type="text" name="mizq" id="mizq" value="20"/>
                </td>  
            </tr>    

            <tr>
                <td class="tdi">Margen derecho</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <input type="text" name="mder" id="mder" value="20"/>
                </td>  
            </tr>    

            <tr>
                <td class="tdi">Margen inferior</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <input type="text" name="minf" id="minf" value="45"/>
                </td>  
            </tr>  


        </table>        

        <div id="div-roles" >
            <table style="width: 100%"> 
                <tr class='ui-widget-header'>
                    <th colspan="2">AGREGAR PERMISOS PARA LOS SIGUIENTES ROLES</th>
                </tr>
            </table>
            <div style="overflow: auto; max-height: 120px">
                <table style="width: 100%"> 
                    <?php
                    $sql = "SELECT * FROM admin_rol ORDER BY nombre";
                    $rs = $db->query($sql);
                    while ($rw = $db->fetch_assoc($rs)) {
                        echo "<tr>";
                        echo "<td style='width:20px'>";
                        echo "<input type='checkbox' name='rol[{$rw[id]}]' id='rol_{$rw[id]}' checked='checked' value='S' />";
                        echo "</td>";
                        echo "<td>";
                        echo "<label for='rol_{$rw[id]}'>$rw[nombre]</label>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>

                </table>
            </div>
        </div>



        <table style="width: 100%; border-collapse: collapse"> 
            <tr class='ui-widget-header'>
                <th style="text-align: left; width: 50px">NO</th>
                <th style="text-align: left">NOMBRE CAMPO</th>
                <th style="text-align: left">TITULO</th>
                <th style="text-align: left">TIPO DE OBJETO</th>
                <th style="text-align: center">OBLIGATORIO</th>
                <th style="text-align: center">LONGITUD</th>
            </tr>
            <?php for ($i = 1; $i <= 10; $i++) { ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td>
                        <input type="text" name="campo[<?php echo $i ?>]" style="width: 120px"/>
                    </td>

                    <td>
                        <input type="text" name="titulo[<?php echo $i ?>]" style="width: 220px"/>
                    </td>

                    <td>
                        <select name="tipo[<?php echo $i ?>]"  style="width: 120px">
                            <option value="t">Texto</option>
                            <option value="c" selected="selected">Combo</option>
                        </select>
                    </td>

                    <td style="text-align: center">
                        <input type="checkbox" name="obligatorio[<?php echo $i ?>]"  value="S"/>
                    </td>

                    <td style="text-align: center">
                        <input type="text" name="longitud[<?php echo $i ?>]" 
                               style="width: 50px; text-align: center" value="30"/>
                    </td>                

                </tr>           

            <?php } ?>
        </table>        

        <br/><br/>

        <table style="width: 100%; border-collapse: collapse"> 
            <tr class='ui-widget-header'>
                <th>ESCRIBIR LA CONSULTA SQL</th>
            </tr>
            <tr>
                <td>
                    <textarea name="sql" style="width: 99.4%" rows="10"></textarea>

                </td>
            </tr>

            <tr>
                <td style="text-align: right">  
                    <input  type="button" value="Cargar campos" onclick="cargar_campos()" class="btn btn-block btn-primary"/>
                </td>
            </tr>
        </table>          

        <br/><br/>
        <div id="div-campos">

        </div>



    </form>

    <div class="acciones" style="margin-top:10px;overflow:hidden;border:none">

<input type="button" name="accion" value="Limpiar" onclick="$('#f').get(0).reset()" class="btn btn-block btn-warning" style="margin-top:5px;margin-left:10px;width:80px;float:right"/>
<input type="button" name="accion" value="Vista previa" onclick="$('#f').get(0).submit()" class="btn btn-block btn-primary" style="margin-left:10px;width:80px;float:right"/>
<input type="button" name="accion" value="Generar" onclick="generar()" class="btn btn-block btn-success" style="margin-left:10px;width:80px;float:right"/>

    </div>
</div>