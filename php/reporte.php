<?php

class TCPDF_REPORTE extends TCPDF {
	var $l1,$l2,$l3,$l4;
	var $r1,$r2,$r3,$r4;
	
	public function SetLeftData($ld1="",$ld2="",$ld3="",$ld4="")
	{
		$this->l1=$ld1;
		$this->l2=$ld2;
		$this->l3=$ld3;
		$this->l4=$ld4;
	}
	
	public function SetRightData($rd1="",$rd2="",$rd3="",$rd4="")
	{
		$this->r1=$rd1;
		$this->r2=$rd2;
		$this->r3=$rd3;
		$this->r4=$rd4;
	}
	
	
    public function Header() 
    {
    	//global $l1,$l2,$l3,$l4;
		//global $r1,$r2,$r3,$r4;
		$l1=$this->l1;
		$l2=$this->l2;
		$l3=$this->l3;
		$l4=$this->l4;


		$r1=$this->r1;
		$r2=$this->r2;
		$r3=$this->r3;
		$r4=$this->r4;
			
		
		$line_width=$this->getPageWidth();
		$margins=$this->getMargins();
 
		$top=30; //margen de arriba
		$this->SetDrawColor(0);
		$this->SetLineWidth(1);
		$this->RoundedRect($margins['left'],$top,  $line_width-  $margins['right'] - $margins['left'], 60, 2, '1111'); //espacion entre el reporte, curvas del cabezote
		//$this->SetLineWidth($line_height);
				
		$this->Image("img/logo.png",$margins['left']+5, $top+3, 47);
		$this->SetFont('times', '', 10);
		$this->MultiCell( $line_width-  $margins['right'] - $margins['left']-59,40,"{$l1}\n{$l2}\n{$l3}\n{$l4}",0,"L",0,1,
			$margins['left']+56,$top+3);
		$this->MultiCell( $line_width-  $margins['right'] - $margins['left']-59,40,"{$r1}\n{$r2}\n{$r3}\n{$r4}",0,"R",0,1,$margins['left']+56,$top+3);
    }	
    
 
    public function Footer()  
    {
		$fecha=strftime(" %d de %B del %Y - %H:%M:%S");
		$fecha=strtoupper ($fecha);

		$pagina_actual=$this->getAliasNumPage();
		$paginas_total=$this->getAliasNbPages();
		
		$this->SetDrawColor(0);
 		$this->Cell(200,0,$fecha,"T",0,"L");
 		$this->Cell(0,0," Pagina $pagina_actual de $paginas_total","T",0,"R");
    }
}





?>