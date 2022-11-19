<?php
require_once("externos.php");


$sql="UPDATE admin.sesion set fin=now() WHERE id='$_SESSION[sesion_id]'";
 $db->query($sql);

?>