
<script type="text/javascript">
var menu="<?php echo $_GET['_menu'] ?>";

$(document).ready(function()
{
	$("#formulario").validate({
			errorLabelContainer: $("#formulario div.error"),
			submitHandler: function() {return false;},
			rules: 
			{
				clave1:{required:true, minlength:1},
				clave2:{required:true, minlength: 5},
				clave3:{required:true, minlength: 5} 
			}
	});
});


function cambiar_clave()
{
	if( !validar("formulario") ) return;
	$.post("?_accion=cambiar&_tipo=json&_menu=" + menu, $("#formulario").values(), 
		function(data) {
		var r = jQuery.parseJSON(data);
		alert(r.msg);
		if(r.error==false) 
		{
			//window.location.href="?";
			window.location.replace("?_menu=01&_accion=ver&_tipo=pagina")
		}	
		});
}
</script>



<br/><br/>
<div id="div_form" class="div_form" style="width:500px; margin:auto">
 
	<form id="formulario" action="">
		<table cellpadding="0" style="width:100%">
			<tbody>
        <tr class="ui-widget-header">
            <th  colspan="3">
             	CAMBIAR CLAVE
            </th>
        </tr>
				<tr>
					<td class="tdi" style="width:90px">Clave actual</td>
					<td class="tdc">:</td>
					<td class="tdd">
	 					<input type="password" name="clave1" id="clave1" maxlength="16" title="Clave actual"/>
					</td>
				</tr>
				
				<tr>
					<td class="tdi">Nueva clave</td>
					<td class="tdc">:</td>
					<td class="tdd">
	 					<input type="password" name="clave2" id="clave2" maxlength="12" title="Nueva clave"/>
					</td>
				</tr>
				<tr>
					<td class="tdi">Repetir nueva clave</td>
					<td class="tdc">:</td>
					<td class="tdd">
	 					<input type="password" name="clave3" id="clave3" maxlength="12" title="Repetir nueva clave" />
					</td>
				</tr>
				<tr>
					<td colspan="3" style="text-align:right;padding:3px">
                    	
						<input type="button" value="Cambiar" style="width:75px;text-transform:none" 
                        	onclick="cambiar_clave();" class="boton"/>
					</td>
				</tr>
			</tbody>
 		</table>
        <div class="error"></div>
	</form>
	
</div>

