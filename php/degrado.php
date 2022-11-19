<?php
//Adaptación de codigo elaborado por: Linoll Moreno Mosquera
//2013-12-05
function degrado ($color_inicial, $color_fin, $valor_maximo, $valor_actual)
{

	// Podes usar la funcion hexdec para convertir de hexadecimal o decimal o poner el valor directamente en decimal, Ej: hexdec('D6D6D6')
	
	$inicio = hexdec( str_replace("#","",$color_inicial) ); // Color de donde comienza el degrado (En este caso rojo). Debe ser >= 0x000000 y <= 0xffffff
	
	$final = hexdec( str_replace("#","",$color_fin) ); // Color al donde terminara el degrado (En este calso es el color blanco). Debe ser >= 0x000000 y <= 0xffffff
	
	$pasos = $valor_maximo; // Nuemero de pasos entre un color y otro. Lo ajustas como queras. Debe ser >= 1 y <= 255
	
	$i = $valor_actual;
	// En este caso vamos a utilizar colores RGB los cuales tienen un componente rojo, verde y 
	// azul y esos componentes los vamos a escalar de forma lineal y es ahi cuando hacemos el degrado
	
	$R0 = ($inicio & 0xff0000) >> 16; // Con esta formula extraemos el valor del componente rojo de un color
	
	$G0= ($inicio & 0x00ff00) >> 8; // Con esta formula extraemos el valor del componente verde de un color
	
	$B0 = ($inicio & 0x0000ff) >> 0; // Con esta formula extraemos el valor del componente azul de un color
	
	$R1 = ($final & 0xff0000) >> 16;
	
	$G1 = ($final & 0x00ff00) >> 8;
	
	$B1 = ($final & 0x0000ff) >> 0;

  	$R = $R0 < $R1 ? (($R1 - $R0) * ($i / $pasos)) + $R0 : (($R0 - $R1) * (1 - ($i / $pasos))) + $R1; // Interpolamos el componente rojo

  	$G = $G0 < $G1 ? (($G1 - $G0) * ($i / $pasos)) + $G0 : (($G0 - $G1) * (1 - ($i / $pasos))) + $G1; // Interpolamos el componente verde

  	$B = $B0 < $B1 ? (($B1 - $B0) * ($i / $pasos)) + $B0 : (($B0 - $B1) * (1 - ($i / $pasos))) + $B1; // Interpolamos el componente azul
    
    $color = ((($R << 8) | $G) << 8) | $B; // Con esta formula unimos los componentes rojo, verde y azul para general el color
	
	return sprintf('#%06X', $color);
}
?>