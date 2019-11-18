<?php
require("funciones_mysql.inc.php");
require("funciones_oracle.inc.php");
require_once("funciones_intranet.inc.php");

function envia_correo_ipvg($para, $asunto, $cuerpo,$headers) {
//para el envío en formato HTML 
$headers = "MIME-Version: 1.0\r\n"; 
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

//dirección del remitente 
$headers .= "From: Soporte Tecnico Virginio Gomez <intranet@virginiogomez.cl>\r\n"; 

//ruta del mensaje desde origen a destino 
$headers .= "Return-path: intranet@virginiogomez.cl\r\n"; 

mail($para,$asunto,$cuerpo,$headers);

return $rs;
}


?>
