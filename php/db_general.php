<?php
class db_base
{
	protected $link_identifier;
    
    private  $key = "dGTADd9y87_uy1y476$#@4";
    
    /*function encrypt($input) {
        //mcrypt_encrypt    	
        $output = base64_encode(openssl_encrypt(MCRYPT_RIJNDAEL_256, md5($Key), $input, MCRYPT_MODE_CBC, md5(md5($Key))));
        return $output;
    }
 
    function decrypt($input) {
    	//mcrypt_decrypt
        $output = rtrim(openssl_decrypt(MCRYPT_RIJNDAEL_256, md5($Key), base64_decode($input), MCRYPT_MODE_CBC, md5(md5($Key))), "\0");
        return $output;
    }*/

function encrypt($data) {
    $encryption_key = base64_decode($key);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}
 
function decrypt($data) {
    $encryption_key = base64_decode($key);
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
}

	function select_limit($sql,$numrows,$offset=0)
	{
		$sql.=" LIMIT $numrows OFFSET $offset";
		return $this->query($sql);
	}
	
	function select_row($sql) 
	{
		$result=$this->select_limit($sql,1,0);
		if($row=$this->fetch_assoc($result) )
		{
			return $row;
		}
		else
		{
			return NULL;
		}
	}
	
	function select_one($sql) 
	{
		
		$result=$this->select_limit($sql,1,0);
		if($row=$this->fetch_row($result) )
		{
			return $row[0];
		}
		else
		{
			return NULL;
		}
	}
	
	function fetch_all($result)
	{
		$r=array();
		while($row=$this->fetch_assoc($result))
		{
			$r[]=$row;
		}
		return $r;	
	}
	
	function select_all($sql) 
	{
		
		$result=$this->query($sql);
		$r=array();
	
		while($row=$this->fetch_assoc($result))
		{
			$r[]=$row;
		}
		return $r;	
	}
	
 
	function make_insert($table,$array,$empty_is_null=true)
	{
		$campos="";
		$valores="";
		foreach ($array as $campo => $valor)
		{
			$valor = $this->escape_string($valor);
			$campos .= "$campo,";
			if( ($valor=="NULL" || $valor==NULL) || ($empty_is_null==true && trim($valor)=="") )
			{
				$valores .= "NULL,";
			}
			else
			{
				$valores .= "'$valor',";
			}
		}
		$campos=trim($campos, ", ");
		$valores=trim($valores, ", ");
		$sql="insert into $table ($campos) values($valores)";
		return $sql;
	}
	
	function insert($table,$array,$empty_is_null=true)
	{
		$sql=$this->make_insert($table,$array,$empty_is_null);
		$this->query($sql);
	}
	function get_insert($table,$array,$empty_is_null=true)
	{
		$sql=$this->make_insert($table,$array,$empty_is_null);
		return ($sql);
	}
	
	function make_update($table,$array,$empty_is_null=true)
	{
		$sql="update " . $table . " set  ";
		foreach ($array as $campo => $valor)
		{
			$valor = $this->escape_string($valor);
			if( ($valor=="NULL" || $valor==NULL) || ($empty_is_null==true && trim($valor)=="") )
			{
				$sql.= $campo. " = NULL, ";
			}
			else
			{
				$sql.= $campo. " = '" . $valor . "', ";
			}
		}
		$sql=trim($sql,", "); //Permire borrar la ultima coma (,) y los espacios  que quedan al final	
		return $sql;
	}
	
	function count_rows($sql)
	{
		$pos=strripos($sql,"from ");
		if($pos!==false)
		{
			$sql=substr($sql,$pos);
		}		
 
		$pos=strripos($sql,"order by ");
		if($pos!==false)
		{
			$sql=substr($sql,0,$pos);
		}
		$sql="select count(*) " . $sql;
 
		return $this->select_one($sql);	
	}
	
	function list_rows($sql,$titulo=true)
	{
		$rs=@$this->query_assoc($sql);
		
		$fila="";
		$num=@$this->num_fields($rs);
		//Imprimir titulos
		if($titulo==true)
		{
			for($i=0;$i<$num;$i++)
				$fila.=@$this->field_name($rs,$i) . "\t";
			echo trim($fila,"\t") . "\n";
		}
		//Imprimir datos
		while( $rw=@$this->fetch_row($rs) )
		{
			$fila="";
			for($i=0;$i<$num;$i++) {
				$fila.=$rw[$i] . "\t";
			}
			echo trim($fila,"\t") . "\n";
		}	
	}
	
	function query_json_table($sql)
	{
		$data=$this->select_all($sql);
		return  json_encode($data) ;
	}
	
	function select_json($sql)
	{
		return $this->query_json_table($sql);
	}

	function exportar_excel($sql)
	{
	 
		$rs=$this->query($sql);
	 
		echo "<table style='border-collapse:collapse' border='1'>";
		echo "<tr>";
 
		while($f = $this->fetch_field($rs) )
		{
			echo "<th>".  $f->name. "</th>";
		}
		echo "</tr>";
		
		while( $rw= $this->fetch_row($rs) )
		{
			echo "<tr>";
			for($i=0;$i<count($rw);$i++)
			{
				echo "<td>".  $rw[$i]. "</td>";			
			}
			echo "</tr>";
		
		}
		echo "</table>";
		
	}
	
	function exportar_csv($sql)
	{
		$rs=$this->query($sql);
		while($f = $this->fetch_field($rs) )
		{
			echo $f->name. ";";
		}
		echo "\n";
		while( $rw= $this->fetch_row($rs) )
		{
			for($i=0;$i<count($rw);$i++)
			{
				echo   $rw[$i]. ";";			
			}
			echo "\n";
		}
	}
	
	function fill_select ($sql,$blanco=false)
	{ 
		if($blanco==true)
			echo '<option value=""></option>';
			
		$rs = @$this->query($sql);	
		while( $rw = @$this->fetch_row($rs) )
		{
			echo '<option value="'. $rw[0] .'">'. $rw[1] . '</option>';
		}
	}

	function update($tabla,$datos,$id)
   {
    $valores="";
    $ids="";

    foreach ($datos as $key => $val) {
        $val = $this->escape_string($val);
        $valores.=$key."="."'".$val."'".",";
    }
    foreach ($id as $key => $val) {
    	$val = $this->escape_string($val);
        $ids=$key."=".$val."";
    }

    $valores=trim($valores,", "); //ultima coma
    $sql= 'UPDATE '.$tabla.' SET '.$valores.' WHERE '.$ids.'';
    $this->query($sql);
   }

   function get_update($tabla,$datos,$id)
   {
    $valores="";
    $ids="";

    foreach ($datos as $key => $val) {
    	$val = $this->escape_string($val);
        $valores.=$key."="."'".$val."'".",";
    }
    foreach ($id as $key => $val) {
    	$val = $this->escape_string($val);
        $ids=$key."=".$val."";
    }

    $valores=trim($valores,", "); //ultima coma
    $sql= 'UPDATE '.$tabla.' SET '.$valores.' WHERE '.$ids.'';
    return $sql;
   }

   	function SubirArchivo($elemento,$tamano_p,$tipo_archivo=false,$carpeta)
	{
			$file = $_FILES[$elemento]['name'];
			$file2 = $_FILES[$elemento]['tmp_name'];
			$tamano = $_FILES[$elemento]['size'];
			$tipo = $_FILES[$elemento]['type'];
			
			$tamano=$tamano/1048576;			
			$archivo = $file; 
			
			if (empty($archivo) or $archivo=='null') {
			return array('error' =>true ,'mensaje'=> "Ingrese archivo");  //valido si viene archivo
			exit(0);}
			
			if ($tamano>$tamano_p) {return array('error' =>true ,'mensaje'=> "Tamaño del archivo: ".$file." no esta permitido");  // si el tamaño se excede
			exit(0);}


				$trozos = explode(".", $archivo); 
				$extension = end($trozos);
				
				$nombre =$elemento.date('Y-m-d').time();
				
				if(!is_dir($carpeta)) 
				mkdir($carpeta, 0777);
				
				$file = $nombre.".".$extension;
				//comprobamos si el archivo ha subido
				if ($file && move_uploaded_file($_FILES[$elemento]['tmp_name'],$carpeta.$file))
				{
				//sleep(3);//retrasamos la petición 3 segundos
				   $direccion=$carpeta.$file;
				   return  array('error' =>false ,'mensaje'=>$direccion);

				}else
				{
				    return  array('error' =>true ,'mensaje'=> "El archivo: ".$elemento." no se pudo subir");
				}
	

	}


function enviarEmail_365_new($asunto,$mensaje,$cabeza,$correos)
{
	$asunto = $asunto;
    $nombre ='Sistema de Información PAI.';
    $mensaje= $mensaje;
    require_once('phpmailer/PHPMailerAutoload.php'); // PARA LISTA
        $mail = new PHPMailer;
      
          $mail->IsSMTP();
          $mail->SMTPKeepAlive = true; 
          $mail->SMTPAuth = true;
          $mail->SMTPSecure = "tls";
          $mail->Host = "smtp.office365.com";
          $mail->IsHTML(true);
      //indico el puerto que usa Gmail
          $mail->Port = 587;

      //indico un usuario / clave de un usuario de gmail
          $mail->Username = "planeacion@anla.gov.co";
          $mail->Password = "Ct931217*/2022";
       
       //de:
          $mail->From = "planeacion@anla.gov.co";
        
        //titulo:
          $mail->FromName = 'Asignación de Tarea.';
          $mail->Subject = $asunto;
          $mail->CharSet = 'UTF-8';

$envio = array();
$no_envio = array();

foreach ($correos as $key => $val) {
     
	
	$mail->MsgHTML($val['mensaje']);
	
	// Activo condificacción utf-8
     if (!empty($val['correo'])){
         
        $mail->addAddress($val['correo'], $nombre);        

            if($mail->Send())
               {
                 $envio[]=$val;
               }
            else
              {
                 $no_envio[]=$val; //." No envio error ".$mail->ErrorInfo;
                 //echo $mail->ErrorInfo;
              }
        $mail->clearAddresses();
      }

} // fin for each

//$mail->clearAddresses();
//$mail->clearAttachments(); 
    $mail->SmtpClose(); 
    return (array('enviados' =>$envio,'no_enviados'=>$no_envio));
}

}
?>