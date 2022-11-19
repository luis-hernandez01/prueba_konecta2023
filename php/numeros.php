<?php
	
function digitos_a_letras($v)
{
	$num[0]="cero";
	$num[1]="uno";
	$num[2]="dos";
	$num[3]="tres";
	$num[4]="cuatro";
	$num[5]="cinco";
	$num[6]="seis";
	$num[7]="siete";
	$num[8]="ocho";
	$num[9]="nuevo";
	
	$r="";
	for($i=0;$i< strlen($v); $i++)
	{
		$r=trim($r). " " . $num[ $v[$i] ];
	}
	return $r;
}

function cardinal_a_ordinal($v,$genero="M",$tag=false)
{
	$num[1]="primer";
	$num[2]="segund";
	$num[3]="tercer";
	$num[4]="cuart";
	$num[5]="quint";
	$num[6]="sext";
	$num[7]="séptim";
	$num[8]="octav";
	$num[9]="noven";
	$num[10]="décim";
	
	$r=$num[$v];
	if($tag==true && ($v ==1 || $v==3) )
	{
		//$r.= ($genero=="M"?'o':'a');
	}
	else
	{
		$r.= ($genero=="M"?'o':'a');
	}
	return $r;
}

function cardinal_a_romano($v)
{
	$num[1]="I";
	$num[2]="II";
	$num[3]="III";
	$num[4]="IV";
	$num[5]="V";
	$num[6]="VI";
	$num[7]="VII";
	$num[8]="VIII";
	$num[9]="IX";
	$num[10]="X";
	
	return $num[$v];
}

function nota_a_letra($v)
{

	$n=explode(".",$v);
 
return strtoupper( digitos_a_letras($n[0]). "." . digitos_a_letras($n[1]) );
}
?>