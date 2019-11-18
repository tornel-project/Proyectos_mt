<?php
function ORACLE_conectar($servidor, $usuario, $contrasena) {
    $servidor = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 152.74.180.189)(PORT = 1521)))(CONNECT_DATA=(SID=IPVG)))";
    if (($conexion = @ocilogon('usuario_web', 'acceso_web', $servidor)) == FALSE) {
        return -1;
    }
    return $conexion;
}

function ORACLE_desconectar($conexion) {
    @ocilogoff($conexion);
    return TRUE;
}

function ORACLE_ejecutar_select($conexion, $SQL, &$vista) {
	if (($stm = @ociparse($conexion, $SQL)) == FALSE) {
        return FALSE;
	}
	if (@ociexecute($stm) == FALSE) {
return FALSE;
	}
	while(@ocifetchinto($stm, $fila, OCI_ASSOC | OCI_RETURN_NULLS)) {
		array_push($vista, $fila);
	}
    return TRUE;
}

function ORACLE_ejecutar_noselect($conexion, $SQL) {
	if (($stm = @ociparse($conexion, $SQL)) == FALSE) {
        return FALSE;
	}
	if (@ociexecute($stm) == FALSE) {
        return FALSE;
	}
    return TRUE;
}

function ORACLE_ejecutar_procedimiento_entrada($conexion, $procedimiento) {
    $exec = "BEGIN ".$procedimiento."; END;";
	if (($stm = @ociparse($conexion, $exec)) == FALSE) {
        return NULL;
	}
	if (@ociexecute($stm) == FALSE) {
        return NULL;
	}
    return TRUE;
}

function ORACLE_ejecutar_procedimiento_salida($conexion, $procedimiento, &$salida) {
    $exec = "BEGIN ".$procedimiento."; END;";
	if (($stm = @ociparse($conexion, $exec)) == FALSE) {
        return NULL;
	}
	$parametros = split(",", substr(strstr($procedimiento, "("), 1, strlen(strstr($procedimiento, "(")) - 2));
	foreach ($parametros as $parametro) {
		$parametro = trim($parametro);
		if (substr($parametro, 0, 1) == ":") {
			@ocibindbyname($stm, $parametro, $salida[substr($parametro, 1)], 100);
		}
	}
	if (@ociexecute($stm) == FALSE) {
        return NULL;
	}
    return TRUE;
}

function ORACLE_reportar_error($tipo, $SQL) {
	//echo $SQL;
}
?>
