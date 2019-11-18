<?php
function MYSQL_conectar($servidor, $usuario, $contrasena, $base_de_datos) {
	$conexion = mysql_connect($servidor, $usuario, $contrasena);
	if ($conexion == FALSE) {
		return -1;
	}
	mysql_select_db($base_de_datos, $conexion);
	return $conexion;
}

function MYSQL_desconectar($conexion) {
	mysql_close($conexion);
}

function MYSQL_ejecutar_select($conexion, $SQL, &$vista) {
	$resultado = mysql_query($SQL, $conexion);
	if ($resultado == FALSE) {
		return FALSE;
	}
	while ($fila = mysql_fetch_assoc($resultado)) {
		array_push($vista, $fila);
	}
	return TRUE;
}

function MYSQL_ejecutar_noselect($conexion, $SQL) {
	$resultado = mysql_query($SQL, $conexion);
	if ($resultado == FALSE) {
		return FALSE;
	}
	return TRUE;
}
?>
