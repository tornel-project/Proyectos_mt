<?php
/* CONNECCION BASE DE DATOS */
$conn = mysql_connect("localhost","usuario_ldcp","land2012"); /* Host, usuario, password */
$basedato = "landingcp";  /* Nombre de la base de datos */
mysql_select_db($basedato,$conn);
/* FIN CONECCION */
?>