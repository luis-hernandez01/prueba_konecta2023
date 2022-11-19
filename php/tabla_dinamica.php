<script>
function mostrar_filtro(chk)
{
	if(chk.checked)
	{
		$("#tr-" + chk.value).show();
	}
	else
	{
		$("#tr-" + chk.value).hide();
	}

	var n=0;
	$(".filtro").each(function(i, e) {
        var id = e.id.replace("tr-","");
		if($(e).css("display") !="none")
		{
			n++;
		}
    });
	if(n==0)
	{
		$("#div-nota1").show();
	}
	else
	{
		$("#div-nota1").hide();
	}
}

function dlg_filtros()
{

	dialogo("dlg-filtro","SELECCIÓN DE FILTROS",550);
	$(".filtro").each(function(i, e) {
        var id = e.id.replace("tr-","");
		if($(e).css("display") !="none")
		{
			$("#f-"+id).attr("checked",true);
		}
    });
}
</script>



<?php

function generar_campos($campos)
{
	global $db;
	$select="";
	$from ="";
	$where ="";
	$where2 = "";	
	
	foreach($campos as $c)
	{
		$r = $db->select_row("SELECT * FROM cm_campo WHERE id='$c'");
		
		switch($r['tipo'])
		{
			case "ID":
			case "TEXTO":
			case "ENTERO": 
			case "DECIMAL": 
			case "FECHA": 
				$select .= "cm.$r[campo] AS `$r[titulo]`, \n";
			break; 
			case "SELECCION": 
				$select .= "$r[tabla].nombre AS `$r[titulo]`, \n";
				$from .= " LEFT JOIN $r[tabla] ON cm.$r[campo] = $r[tabla].$r[campo_pk]  \n ";
				/*
				
				$from .= ", $r[tabla]";
				$where .= " AND cm.$r[campo] = $r[tabla].$r[campo_pk]  \n";
				*/
			break; 					
		}
	}
	
	return array("select" => $select, "from" => $from, "where"=> $where, "where2" => $where2);

}


function generar_filtros($filtros)
{
	global $db;
	$select="";
	$from ="";
	$where ="";
	$where2 = "";
	
	$titulos="";
	foreach($filtros as $f => $v)
	{
		if ($v!="")
		{
			$r = $db->select_row("SELECT * FROM cm_campo WHERE id='$f'");
			switch($r['tipo'])
			{
				
				case "SELECCION": 
					if($v=="*") //Campos sin seleccionar (nulos)
					{
						$where .= " AND cm.$r[campo] IS NULL ";						
						$titulos .=  "<div><b>". strtoupper($r['titulo']). ":</b> (SIN INFORMACIÓN) </div>";
					}
					else
					{
						$where .= " AND cm.$r[campo]='$v' ";
						$t = $db->select_one("SELECT $r[campo_nombre] FROM $r[tabla] WHERE $r[campo_pk]='$v'");
						$titulos .=  "<div><b>". strtoupper($r['titulo']). ":</b> $t </div>";
					}

					break;
					
				case "ENTERO": 
				case "DECIMAL": 
					$t1="";
					$t2="";
					if($v['min']!="") 
					{
						$where .= " AND cm.$r[campo]>='$v[min]' ";
						$t1 = " MAYOR O IGUAL <b>$v[min]</b>";
					}
					
					if($v['max']!="") 
					{
						$t2  = ($t1=="") ? "":"; ";
						$t2 .= " MENOR O IGUAL <b>$v[max]</b>";
						$where .= " AND cm.$r[campo]<='$v[max]' ";
					}
										
 					if($t1!="" ||  $t2!="")
					{
						$titulos .=  "<div><b>". strtoupper($r['titulo']). ":</b> $t1 $t2 </div>";
					}
					break;
			}
 
		}
	}
	
	return array("select" => $select, "from" => $from, "where"=> $where, "where2" => $where2, "titulos" =>  $titulos);
}



function generar_tabla_filtros()
{
	global $db;
?>
    <div  id="dlg-filtro"  style="display:none">
        <div style="padding:5px">
            <div style="font-weight:bold">
                Seleccione los campos por los que desea filtrar la información
            </div>
            <table style="width:100%; border-collapse:collapse"  >
              <tr class="ui-widget-header">
                    <th style="width:30px;">
                              
                    </th>             
                    <th style="text-align:left;">
                        CAMPO
                    </th>
                    <th></th>      
                </tr>
            </table>
            
            <div style="height:200px; overflow-y:scroll; border:1px solid silver">
                <table style="width:100%; border-collapse:collapse" >
                    <?php
                        $rs = $db->query("SELECT id, titulo FROM cm_campo WHERE tipo IN('SELECCION','DECIMAL','ENTERO')");
                        while ($rw = $db->fetch_assoc($rs) )
                        {
                            $color=($color=="#fff") ? "#eee" : "#fff";
                    ?>
                    <tr style="background-color:<?php  echo $color ?>">
                        <td style="text-align:center; width:30px">
                            <input type="checkbox" id="f_<?php echo $rw['id'] ?>" value="<?php echo $rw['id'] ?>" onChange="mostrar_filtro(this)" />
                        </td> 
                                            
                        <td>
                            <?php   echo ++$i  . ". " .  $rw['titulo'] ?>
                        </td>
     
                    </tr>
     
                    <?php
                        }
                    ?>
                </table>
            </div>
            
            <div style="text-align:right; margin-top:5px; border-top:1px solid silver">
                <input type="button" value="Cerrar" onClick="$('#dlg-filtro').dialog('close')" >
            </div>
        </div>
    </div>


	<table style="width:100%" border="0" id="tabla-filtros">
        <tr class="ui-widget-header">
            <th style="height:22px; line-height:22px">
                FILTROS 
                <button type="button" class="ui-state-default ui-corner-all" 
                	onclick="dlg_filtros()"
                    style=" float:right; min-width:20px !important; height:20px !important; margin:1px !important; padding:0 !important "
                    
                      >
                	<span class="ui-icon ui-icon-search">
                </button>
       
            </th>
        </tr>
		<tr>
			<td>
            
            
            <div class="ui-widget" id="div-nota1">
                <div class="ui-state-highlight ui-corner-all" style="margin-top: 5px; padding: 0 .7em;">
                    <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
                    <strong>Nota:</strong>
                    	Haga clic en el <b>botón</b> que está en la parte derecha del título <b>filtros</b> 
                    	para seleccionar los filtros a utilizar en la búsqueda.
                    </p>
                </div>
            </div>
                        
            	<table style="width:100%">
                	<?php
						$rs = $db->query("SELECT * FROM cm_campo WHERE tipo IN('SELECCION','DECIMAL','ENTERO')");
						$n=0;
						while($rw = $db->fetch_assoc($rs))
						{
			 				$n++;
							$rw['titulo'] = $n . ". " . $rw['titulo'];
							switch($rw['tipo'])
							{
								case "ENTERO": 
								case "DECIMAL": 
									echo "<tr id='tr-$rw[id]' class='filtro' style='display:none'>";
									echo "<td class='tdi' style='width:160px'> $rw[titulo] </td>";
									echo "<td class='tdc'> : </td>";
									echo "<td class='tdd'>"; 
									$sql="SELECT IFNULL( Min($rw[campo]),0) as min, IFNULL(max($rw[campo]),0) as max FROM cm";
									$rw2=$db->select_row($sql);
									?>
                                    	<span>Desde </span>
                                        <input type="number" name="filtro[<?php echo $rw['id'] ?>][min]" 
                                        	style="width:70px" min="<?php echo $rw2['min'] ?>" max="<?php echo $rw2['max'] ?>"/>
                                            
                                            
                                        <span>Hasta </span>
                                        <input type="number"  name="filtro[<?php echo $rw['id'] ?>][max]"
                                        	style="width:70px" min="<?php echo $rw2['min'] ?>" max="<?php echo $rw2['max'] ?>"/>
                                        
                                        <span style="margin-left:40px">Rango permitido: de 
											<?php echo "<b>$rw2[min]</b> hasta <b>$rw2[max]</b>" ?> 
                                        </span> 
                                        
                                    <?php
									
									
									echo "</td>"; 
									echo "</tr>";								 
								break; 
								
								case "SELECCION": 
									echo "<tr id='tr-$rw[id]' class='filtro' style='display:none'>";
									echo "<td class='tdi' style='width:160px'> $rw[titulo] </td>";
									echo "<td class='tdc'> : </td>";
									echo "<td class='tdd'>"; 
									echo "<select id='filtro[" . $rw['id']  ."]' name='filtro[" . $rw['id']  ."]' >";
									echo "<option value=''></option>";
									//echo "<option value='*'>(SIN INFORMACIÓN)</option>";
									//$sql= "SELECT $rw[campo_pk], $rw[campo_nombre] FROM $rw[tabla] ";
						 			$sql="SELECT distinct $rw[tabla].$rw[campo_pk], $rw[tabla].$rw[campo_nombre] 
											FROM $rw[tabla], cm 
											WHERE cm.$rw[campo]=$rw[tabla].$rw[campo_pk] ORDER BY $rw[campo_nombre]";
									
									llenar_combo($sql);
									echo "</select>";
									echo "</td>"; 
									echo "</tr>";
								break; 	 		
							}                    		
 
						}
					?>	
                
                </table>
            </td>
        </tr>
	</table>
 
<?php

}
?>