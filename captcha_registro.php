<?php
require_once("externos.php");
if (isset($_SESSION['usuario'])) {
	header("location: " .'inicio');
    echo "ERROR"; exit();
}
// genero el codigo 
$ranStr = md5(microtime()); 
$ranStr = strtoupper(substr($ranStr, 0, 6));
//le asigno a la session el valor de mi captcha
$_SESSION['cap_code_registro'] = $ranStr;
//creo la imagen con php
$newImage = imagecreatefromjpeg("img/cap_bg.jpg"); 
$txtColor = imagecolorallocate($newImage, 0, 0, 0);
imagestring($newImage, 5, 5, 5, $ranStr, $txtColor);
header("Content-type: image/jpeg");
imagejpeg($newImage);
?>


