<?php
function traduce_nota($nota) {
    if ($nota == 0) {
	return "";
    }
    if (($nota == "200") || ($nota == "800")) {
	return "NCR";
    }
    if ($nota == "NCR") {
	return 200;
    }
    if ($nota == "600") {
	return "ER";
    }	
    if ($nota == "400") {
	return "CR";
    }	
    return $nota;
}

function INTRANET_eliminar_usuario_activo($usuario) {
	$SQL = "DELETE FROM
				USUARIOS_ACTIVOS
			WHERE
				USAC_USUA_NUMERO = " . $usuario;
	MYSQL_ejecutar_noselect($GLOBALS['conexion_mysql'], $SQL);
}

function INTRANET_actualizar_usuario_activo() {
	if ($_SESSION['usuario'] != "") {
		$SQL = "UPDATE 
					USUARIOS_ACTIVOS
				SET
					USAC_ANTIGUEDAD = CURRENT_TIMESTAMP()
				WHERE
					USAC_USUA_NUMERO = " . $_SESSION['usuario'];
		MYSQL_ejecutar_noselect($GLOBALS['conexion_mysql'], $SQL);
	}
	$SQL = "DELETE FROM
				USUARIOS_ACTIVOS
			WHERE
				(CURRENT_TIMESTAMP() - USAC_ANTIGUEDAD) > 1000 ";
	MYSQL_ejecutar_noselect($GLOBALS['conexion_mysql'], $SQL);
}

function INTRANET_agregar_usuario_activo($usuario) {
	$SQL = "DELETE FROM
				USUARIOS_ACTIVOS
			WHERE
				USAC_USUA_NUMERO = " . $usuario;
	MYSQL_ejecutar_noselect($GLOBALS['conexion_mysql'], $SQL);
	$SQL = "INSERT INTO
				USUARIOS_ACTIVOS
			(
				USAC_USUA_NUMERO,
				USAC_ANTIGUEDAD
			)
			VALUES
			(
				" . $usuario . ",
				CURRENT_TIMESTAMP()
			)";
	MYSQL_ejecutar_noselect($GLOBALS['conexion_mysql'], $SQL);
}

function INTRANET_rut_usuario($usuario) {
	$SQL = "SELECT
				*
			FROM
				USUARIOS
			WHERE
				USUA_NUMERO = " . $usuario;
	$vista_usuario = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_usuario);
	return $vista_usuario[0]['USUA_RUT'];
}

function INTRANET_registrar_visita() {
	$get = "";
	foreach ($_GET as $get_clave => $get_valor) {
		$get .= "[" . $get_clave . "]\r\n" . $get_valor . "\r\n";
	}
	$post = "";
	foreach ($_POST as $post_clave => $post_valor) {
		$post .= "[" . $post_clave . "]\r\n" . $post_valor . "\r\n";
	}
	$files = "";
	foreach ($_FILES as $files_name => $files_valor) {
		foreach ($files_valor as $file_clave => $file_valor) {
			$files .= "[" . $files_name. "_" . $file_clave . "]\r\n" . $file_valor . "\r\n";
		}
	}
	if (!isset($_GET['opcion'])) {
		$_GET['opcion'] = "";
	}
	if (!isset($_SESSION['usuario'])) {
		$_SESSION['usuario'] = "";
	}
	$SQL = "INSERT INTO
				REGISTRO_VISITAS
			(
				REVI_FECHA,
				REVI_HORA,
				REVI_USUARIO,
				REVI_IP,
				REVI_USER_AGENT,
				REVI_MODULO,
				REVI_OPCION,
				REVI_GET,
				REVI_POST,
				REVI_FILES
			)
			VALUES
			(
				CURRENT_DATE(),
				CURRENT_TIME(),
				'" . INTRANET_nombre_usuario($_SESSION['usuario']) . "',
				'" . $_SERVER['REMOTE_ADDR'] . "',
				'" . $_SERVER['HTTP_USER_AGENT'] . "',
				'" . $_GET['modulo'] . "',
				'" . $_GET['opcion'] . "',
				'" . $get . "',
				'" . $post . "',
				'" . $files. "'
			)";
	MYSQL_ejecutar_noselect($GLOBALS['conexion_mysql'], $SQL);
}

function INTRANET_extension_documento($documento) {
	$SQL = "SELECT 
				* 
			FROM 
				DOCUMENTOS 
			WHERE 
				DOCU_NUMERO = " . $documento;
	$vista_documento = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_documento);	
	$archivo = pathinfo($vista_documento[0]['DOCU_DOCUMENTO']);
	return $archivo['extension'];
}

function INTRANET_tipo_mime_documento($documento) {
	$SQL = "SELECT 
				* 
			FROM 
				DOCUMENTOS 
			WHERE 
				DOCU_NUMERO = " . $documento;
	$vista_documento = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_documento);	
	return strtolower($vista_documento[0]['DOCU_TIPO_MIME']);
}

function INTRANET_icono_documento($documento) {
	if (INTRANET_tipo_documento($documento) == "D") {
?>
<img src="intranet/imagenes/documentos/carpeta.jpg" border="0" alt="">
<?php
	} else {
		switch (strtolower(INTRANET_extension_documento($documento))) {
			case "doc":
				$icono = "word.jpg";
				break;
			case "rar":
				$icono = "winrar.jpg";
				break;
			case "zip":
				$icono = "winzip.jpg";
				break;
			case "xls":
				$icono = "excel.jpg";
				break;
			case "pdf":
				$icono = "acrobat.jpg";
				break;
			case "exe":
				$icono = "aplicacion.jpg";
				break;
			case "txt":
				$icono = "texto.jpg";
				break;
			case "ppt":
			case "pps":
				$icono = "powerpoint.jpg";
				break;
			case "rm":
				$icono = "real.jpg";
				break;
			case "avi":
				$icono = "windowsmedia.jpg";
				break;
			case "mov":
				$icono = "quicktime.jpg";
				break;
			case "java":
				$icono = "java.jpg";
				break;
			case "c":
				$icono = "c.jpg";
				break;
			case "cpp":
				$icono = "cpp.jpg";
				break;
			case "h":
				$icono = "h.jpg";
				break;
			case "jpg":
			case "gif":
				$icono = "imagen.jpg";
				break;
			default:
				$icono = "archivo.jpg";
		}
?>
<img src="intranet/imagenes/documentos/<?php echo $icono?>" border="0">
<?php
	}
}

function INTRANET_archivo_documento($documento) {
	$SQL = "SELECT 
				* 
			FROM 
				DOCUMENTOS 
			WHERE 
				DOCU_NUMERO = " . $documento;
	$vista_documento = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_documento);	
	return $vista_documento[0]['DOCU_ARCHIVO'];
}

function INTRANET_descendencia_directorio($directorio, &$arreglo) {
	$SQL = "SELECT
				*
			FROM
				DOCUMENTOS
			WHERE
				DOCU_PADRE = " . $directorio;
	$vista_documentos = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_documentos);	
	foreach ($vista_documentos as $documento) {
		array_push($arreglo, $documento['DOCU_NUMERO']);
		if ($documento['DOCU_TIPO'] == "D") {
			INTRANET_descendencia_directorio($documento['DOCU_NUMERO'], $arreglo);
		}
	}
}

function INTRANET_tipo_documento($documento) {
	$SQL = "SELECT 
				* 
			FROM 
				DOCUMENTOS 
			WHERE 
				DOCU_NUMERO = " . $documento;
	$vista_documento = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_documento);	
	if (isset($vista_documento[0]['DOCU_TIPO'])) {
		return $vista_documento[0]['DOCU_TIPO'];
	} else {
		return "";
	}
}

function INTRANET_cambiar_permisos_documento($documento) {
		$SQL = "DELETE FROM
					PERMISOS_DOCUMENTOS
				WHERE
					PEDO_DOCU_NUMERO = " . $documento;
		MYSQL_ejecutar_noselect($GLOBALS['conexion_mysql'], $SQL);
		if (isset($_POST['lectura'])) {
			foreach ($_POST['lectura'] as $grupo => $valor) {
				$SQL = "INSERT INTO
							PERMISOS_DOCUMENTOS
						(
							PEDO_GRUP_NUMERO,
							PEDO_DOCU_NUMERO,
							PEDO_PERMISO
						)
						VALUES
						(
							" . $grupo . ",
							" . $documento . ",
							'L'
						)";
				MYSQL_ejecutar_noselect($GLOBALS['conexion_mysql'], $SQL);
			}
		}
		if (isset($_POST['escritura'])) {
			foreach ($_POST['escritura'] as $grupo => $valor) {
				$SQL = "INSERT INTO
							PERMISOS_DOCUMENTOS
						(
							PEDO_GRUP_NUMERO,
							PEDO_DOCU_NUMERO,
							PEDO_PERMISO
						)
						VALUES
						(
							" . $grupo . ",
							" . $documento . ",
							'E'
						)";
				MYSQL_ejecutar_noselect($GLOBALS['conexion_mysql'], $SQL);
			}
		}
		$SQL = "UPDATE 
					DOCUMENTOS
				SET
					DOCU_FECHA = CURRENT_TIMESTAMP()
				WHERE
					DOCU_NUMERO = " . $documento;
		MYSQL_ejecutar_noselect($GLOBALS['conexion_mysql'], $SQL);
}

function INTRANET_obtener_padre($documento) {
	$SQL = "SELECT
				*
			FROM
				DOCUMENTOS
			WHERE
				DOCU_NUMERO = " . $documento;
	$vista_padre = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_padre);
	return $vista_padre[0]['DOCU_PADRE'];
}

function INTRANET_nombre_documento($numero) {
	$SQL = "SELECT
				*
			FROM
				DOCUMENTOS
			WHERE
				DOCU_NUMERO = " . $numero;
	$vista_documento = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_documento);
	return $vista_documento[0]['DOCU_DOCUMENTO'];
}
// byp, agregue seccion (15-abr-2010) ----------------------------------------
function INTRANET_numero_documento($documento, $directorio, $secc,$asignatura) {
	$SQL = "SELECT
				*
			FROM
				DOCUMENTOS
			WHERE
				DOCU_DOCUMENTO = '" . $documento . "' AND
				DOCU_PADRE = " . $directorio." AND
				DOCU_ASIGNATURA = ".$asignatura." AND
				DOCU_SECCION = ".$secc;
	$vista_documento = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_documento);
	return $vista_documento[0]['DOCU_NUMERO'];
}
//----------------------------------------------------------------
function INTRANET_usuario_tiene_permiso($usuario, $documento, $permiso) {
	if (INTRANET_usuario_pertenece_a_grupo($_SESSION['usuario'], 1)) {
		return TRUE;
	}
	//CFRD
	if (INTRANET_usuario_pertenece_a_grupo($_SESSION['usuario'], 3)) {
		return TRUE;
	}
	// FIN CFRD
	if (INTRANET_usuario_pertenece_a_grupo($_SESSION['usuario'], 11)) {	
		return TRUE;
	}
	if (($documento == "1") && ($permiso == "L")) {
		return TRUE;
	}
	$SQL = "SELECT
				*
			FROM
				DOCUMENTOS
			WHERE
				DOCU_USUA_NUMERO = " . $usuario . " AND
				DOCU_NUMERO = " . $documento;	
	$vista_documento = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_documento);
	if (count($vista_documento) > 0) {
		return TRUE;
	} else {
		$SQL = "SELECT
					*
				FROM
					PERMISOS_DOCUMENTOS
				WHERE
					PEDO_DOCU_NUMERO = " . $documento . " AND
					PEDO_PERMISO = '" . $permiso . "'";
		$vista_permisos = array();
		MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_permisos);
		foreach ($vista_permisos as $permiso) {
			if (INTRANET_usuario_pertenece_a_grupo($_SESSION['usuario'], $permiso['PEDO_GRUP_NUMERO'])) {
				return TRUE;
			}
		}
	}
	return FALSE;
}

function INTRANET_ruta_completa($directorio) {
	$carpetas = array();
	while (true) {
		if ($directorio == "1") {
			break;
		}
		$SQL = "SELECT
					*
				FROM
					DOCUMENTOS
				WHERE
					DOCU_NUMERO	= " . $directorio;
		$vista_padre = array();
		MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_padre);
		$carpeta['numero'] = $vista_padre[0]['DOCU_NUMERO'];
		$carpeta['nombre'] = $vista_padre[0]['DOCU_DOCUMENTO'];
		array_push($carpetas, $carpeta);
		$directorio = $vista_padre[0]['DOCU_PADRE'];
	}
	array_push($carpetas, array('numero' => 1, 'nombre' => "Documentos"));
	$carpetas = array_reverse($carpetas);
	foreach ($carpetas as $carpeta) {
		//if ($carpeta['numero'] != $_GET['directorio']) {
?>/<a href="<?php echo $_SERVER['PHP_SELF']?>?modulo=intranet&amp;opcion=documentos&amp;documento=<?php echo $carpeta['numero']?>&amp;op=2"><?php echo $carpeta['nombre']?></a><?php
		//} else {
		//	echo $carpeta['nombre'];
		//}
	}
}

function INTRANET_ruta_completa_jc($directorio) {
	$carpetas = array();
	while (true) {
		if ($directorio == "1") {
			break;
		}
		$SQL = "SELECT
					*
				FROM
					DOCUMENTOS
				WHERE
					DOCU_NUMERO	= " . $directorio;
		$vista_padre = array();
		MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_padre);
		$carpeta['numero'] = $vista_padre[0]['DOCU_NUMERO'];
		$carpeta['nombre'] = $vista_padre[0]['DOCU_DOCUMENTO'];
		array_push($carpetas, $carpeta);
		$directorio = $vista_padre[0]['DOCU_PADRE'];
	}
	array_push($carpetas, array('numero' => 1, 'nombre' => "Documentos"));
	$carpetas = array_reverse($carpetas);
	foreach ($carpetas as $carpeta) {
		//if ($carpeta['numero'] != $_GET['directorio']) {
?>/<?php echo $carpeta['nombre']?><?php
		//} else {
		//	echo $carpeta['nombre'];
		//}
	}
}
function INTRANET_existe_archivo($padre, $nombre) {
	$SQL = "SELECT 
				* 
			FROM
				DOCUMENTOS
			WHERE
				DOCU_DOCUMENTO = '" . $nombre . "' AND
				DOCU_PADRE = " . $padre;
	$vista_documento = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_documento);
//	echo ' padre:',$padre;
//	echo ' nom:',$nombre;
//	echo ' enc:',count($vista_documento);
	
	if (count($vista_documento) > 0) {
		return TRUE;
	} else {
		return FALSE;
	}
}

// funcion creada por byp 7-abril-2010
function INTRANET_existe2_archivo($padre, $nombre, $secc) {
	$SQL = "SELECT 
				* 
			FROM
				DOCUMENTOS
			WHERE
				DOCU_DOCUMENTO = '" . $nombre . "' AND
				DOCU_PADRE = '" . $padre . "' AND
				DOCU_SECCION = " . $secc;
	$vista2_documento = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista2_documento);
//	echo ' padre:',$padre;
//	echo ' nom:',$nombre;
//	echo ' enc:',count($vista_documento);
	
	if (count($vista_documento) > 0) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function INTRANET_existe_documento($documento) {
	$SQL = "SELECT 
				* 
			FROM
				DOCUMENTOS
			WHERE
				DOCU_NUMERO = " . $documento;
	$vista_documento = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_documento);
	if (count($vista_documento) > 0) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function INTRANET_nombre_completo_usuario($usuario) {
	$SQL = "SELECT
				USUA_NOMBRES,
				USUA_PATERNO,
				USUA_MATERNO
			FROM
				USUARIOS
			WHERE
				USUA_NUMERO = " . $usuario;
	$vista_usuario = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_usuario);
	return $vista_usuario[0]['USUA_NOMBRES'] . " " . $vista_usuario[0]['USUA_PATERNO'] . " " . $vista_usuario[0]['USUA_MATERNO'];
}

function INTRANET_nombre_usuario($usuario) {
	$SQL = "SELECT
				USUA_USUARIO
			FROM
				USUARIOS
			WHERE
				USUA_NUMERO = " . $usuario;
	$vista_usuario = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_usuario);
	if (isset($vista_usuario[0]['USUA_USUARIO'])) {
		return $vista_usuario[0]['USUA_USUARIO'];
	} else {
		return "";
	}
}

function INTRANET_nombre_grupo($grupo) {
	$SQL = "SELECT
				GRUP_NOMBRE
			FROM
				GRUPOS
			WHERE
				GRUP_NUMERO = " . $grupo;
	$vista_grupo = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_grupo);
	return $vista_grupo[0]['GRUP_NOMBRE'];
}

function INTRANET_grupos_usuario($usuario) {
	$SQL = "SELECT 
				GRUP_NUMERO 
			FROM
				GRUPOS,
				USUARIOS_GRUPOS
			WHERE
				USGR_GRUP_NUMERO = GRUP_NUMERO AND
				USGR_USUA_NUMERO = " . $usuario . "
			ORDER BY
				GRUP_NOMBRE";
	$vista_grupos = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_grupos);
	$vector_grupos = array();
	for($i = 0; $i < count($vista_grupos); $i++) {
		array_push($vector_grupos, $vista_grupos[$i]['GRUP_NUMERO']);
	}
	return $vector_grupos;
}

function INTRANET_existe_usuario($usuario) {
	$SQL = "SELECT
				*
			FROM
				USUARIOS
			WHERE
				USUA_USUARIO = '" . $usuario . "'";
	$vista_usuario = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_usuario);
	if (count($vista_usuario) > 0) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function INTRANET_eliminar_usuario($usuario) {
	$SQL = "DELETE FROM
				USUARIOS
			WHERE
				USUA_NUMERO = " . $usuario;
	MYSQL_ejecutar_noselect($GLOBALS['conexion_mysql'], $SQL);
	$SQL = "DELETE FROM
				USUARIOS_GRUPOS
			WHERE
				USGR_USUA_NUMERO = " . $usuario;
	MYSQL_ejecutar_noselect($GLOBALS['conexion_mysql'], $SQL);
	$SQL = "DELETE FROM
				EXALUMNOS
			WHERE
				EXAL_USUA_NUMERO = " . $usuario;
	MYSQL_ejecutar_noselect($GLOBALS['conexion_mysql'], $SQL);
}

function INTRANET_agregar_usuario($rut, $usuario, $grupo, $contrasena, $paterno, $materno, $nombres) {
	$SQL = "INSERT INTO 
				USUARIOS
			(
			USUA_RUT,
			USUA_USUARIO,
			USUA_CONTRASENA,
			USUA_PATERNO,
			USUA_MATERNO,
			USUA_NOMBRES,
			USUA_FECHA_CREACION
			) 
			VALUES 
			(
			'" . $rut . "',
			'" . $usuario . "',
			'" . md5($contrasena) . "',
			'" . $paterno . "',
			'" . $materno . "',
			'" . $nombres . "',
			NOW()
			)";
	MYSQL_ejecutar_noselect($GLOBALS['conexion_mysql'], $SQL);
	$SQL = "INSERT INTO
				USUARIOS_GRUPOS
			(
				USGR_USUA_NUMERO,
				USGR_GRUP_NUMERO,
				USGR_PRINCIPAL
			)
			VALUES
			(
				" . INTRANET_numero_usuario($usuario) . ",
				" . $grupo . ",
				1
			)";
	MYSQL_ejecutar_noselect($GLOBALS['conexion_mysql'], $SQL);
}
// agregada el 25-oct-2010
function INTRANET_agregar_usuario_docente($rut, $usuario, $grupo, $contrasena, $paterno, $materno, $nombres, $email) {
	$SQL = "INSERT INTO USUARIOS
			(
			USUA_RUT,
			USUA_USUARIO,
			USUA_CONTRASENA,
			USUA_PATERNO,
			USUA_MATERNO,
			USUA_NOMBRES,
			USUA_CORREO_ELECTRONICO,
			USUA_FECHA_CREACION
			) 
			VALUES 
			(
			'" . $rut . "',
			'" . $usuario . "',
			'" . md5($contrasena) . "',
			'" . $paterno . "',
			'" . $materno . "',
			'" . $nombres . "',			
			'" . $email . "',
			NOW()
			)";
	MYSQL_ejecutar_noselect($GLOBALS['conexion_mysql'], $SQL);
	$SQL = "INSERT INTO
				USUARIOS_GRUPOS
			(
				USGR_USUA_NUMERO,
				USGR_GRUP_NUMERO,
				USGR_PRINCIPAL
			)
			VALUES
			(
				" . INTRANET_numero_usuario($usuario) . ",
				" . $grupo . ",
				1
			)";
	MYSQL_ejecutar_noselect($GLOBALS['conexion_mysql'], $SQL);
}
//
function INTRANET_agregar_usuario_personal($rut, $usuario, $grupo, $contrasena, $correo, $paterno, $materno, $nombres) {
	$SQL = "INSERT INTO 
				USUARIOS
			(
			USUA_RUT,
			USUA_USUARIO,
			USUA_CONTRASENA,
			USUA_CORREO_ELECTRONICO,
			USUA_PATERNO,
			USUA_MATERNO,
			USUA_NOMBRES,
			USUA_FECHA_CREACION
			) 
			VALUES 
			(
			'" . $rut . "',
			'" . $usuario . "',
			'" . md5($contrasena) . "',
			'" . $correo . "',
			'" . $paterno . "',
			'" . $materno . "',
			'" . $nombres . "',
			NOW()
			)";
	MYSQL_ejecutar_noselect($GLOBALS['conexion_mysql'], $SQL);
	$SQL = "INSERT INTO
				USUARIOS_GRUPOS
			(
				USGR_USUA_NUMERO,
				USGR_GRUP_NUMERO,
				USGR_PRINCIPAL
			)
			VALUES
			(
				" . INTRANET_numero_usuario($usuario) . ",
				" . $grupo . ",
				1
			)";
	MYSQL_ejecutar_noselect($GLOBALS['conexion_mysql'], $SQL);
}

function INTRANET_buscar_rut_usuario($rut, $grupo) {
	$SQL = "SELECT 
				* 
			FROM 
				USUARIOS
			WHERE 
				USUA_RUT = '" . $rut . "'";
	if ($grupo != "") {
		if ($grupo != 3) {
			$SQL = "SELECT 
						USUA_RUT,
						USGR_GRUP_NUMERO 
					FROM 
						USUARIOS,
						USUARIOS_GRUPOS
					WHERE 
						USUA_RUT = '" . $rut . "' AND
						USGR_USUA_NUMERO = USUA_NUMERO AND
						USGR_PRINCIPAL = 1 AND
						USGR_GRUP_NUMERO = " . $grupo;
		} else {
			$SQL = "SELECT 
						USUA_RUT,
						USGR_GRUP_NUMERO 
					FROM 
						USUARIOS,
						USUARIOS_GRUPOS
					WHERE 
						USUA_RUT = '" . $rut . "' AND
						USGR_USUA_NUMERO = USUA_NUMERO AND
						USGR_GRUP_NUMERO = " . $grupo;
		}
	}
	$vista_usuario_intranet = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_usuario_intranet);
	if (count($vista_usuario_intranet) > 0) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function INTRANET_buscar_rut_usuario_personal($rut, $grupo) {
	$SQL = "SELECT 
				* 
			FROM 
				USUARIOS
			WHERE 
				USUA_RUT = '" . $rut . "'";
	if ($grupo != "") {

			$SQL = "SELECT 
						USUA_RUT,
						USGR_GRUP_NUMERO 
					FROM 
						USUARIOS,
						USUARIOS_GRUPOS
					WHERE 
						USUA_RUT = '" . $rut . "' AND
						USGR_USUA_NUMERO = USUA_NUMERO AND
						USGR_GRUP_NUMERO = " . $grupo;
		
	}
	$vista_usuario_intranet = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_usuario_intranet);

	if (count($vista_usuario_intranet) > 0) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function INTRANET_construir_nombre_usuario($nombre, $apellido) {


 	$val1	= array("á", "é", "í", "ó", "ú", "Ñ", "ñ", " ", "Á", "É", "Í", "Ó", "Ú", "ü");
 	$val2	= array("a", "e", "i", "o", "u", "n", "n", "", "a", "e", "i", "o", "u", "u");
	for($i=0;$i<count($val1);$i++)
		{
			$val1[$i]	=  utf8_decode($val1[$i]);
		}
	$cadena	= strtolower(trim($nombre) . "." . trim($apellido));
	$nombre_usuario_intranet = str_replace($val1, $val2, $cadena);
	
	$SQL = "
		SELECT
			USUA_NUMERO
		FROM
			USUARIOS
		WHERE
			USUA_USUARIO = '" . $nombre_usuario_intranet . "'
	";
  	if ($_SERVER['REMOTE_ADDR'] == "152.74.180.203") {
		print $nombre_usuario_intranet;
 	}
	
	$vista_usuario_intranet = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_usuario_intranet);
	if (count($vista_usuario_intranet) == 0) {
		return $nombre_usuario_intranet;
	}
	for ($i = 1; $i < 100000; $i++) {
		$SQL = "
			SELECT
				USUA_NUMERO
			FROM
				USUARIOS
			WHERE
				USUA_USUARIO = '" . $nombre_usuario_intranet . $i . "'
		";
		$vista_usuario_intranet = array();
		MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_usuario_intranet);
		if (count($vista_usuario_intranet) == 0) {
			return $nombre_usuario_intranet . $i;
		}
	}
	return $nombre_usuario_intranet;
}
function INTRANET_grupo_principal_usuario($usuario) {
	$SQL = "SELECT
				USGR_GRUP_NUMERO
			FROM
				USUARIOS_GRUPOS
			WHERE
				USGR_USUA_NUMERO = " . $usuario . " AND
				USGR_PRINCIPAL = 1";
	$vista_grupo_usuario = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_grupo_usuario);
	return $vista_grupo_usuario[0]['USGR_GRUP_NUMERO'];
}

function INTRANET_numero_usuario($usuario) {
	$SQL = "SELECT
				USUA_NUMERO
			FROM
				USUARIOS
			WHERE
				USUA_USUARIO = '" . $usuario . "'";
	$vista_usuario_intranet = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_usuario_intranet);
	return $vista_usuario_intranet[0]['USUA_NUMERO'];
}

function INTRANET_numero_usuario_rut($rut) {
	$SQL = "SELECT
				USUA_NUMERO
			FROM
				USUARIOS
			WHERE
				USUA_RUT = " . $rut;
	$vista_usuario_intranet = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_usuario_intranet);
	if (count($vista_usuario_intranet) > 0) {
		return $vista_usuario_intranet[0]['USUA_NUMERO'];
	} else {
		return "";
	}
}

function INTRANET_usuario_pertenece_a_grupo($usuario, $grupo) {
	$SQL = "SELECT 
				* 
			FROM 
				USUARIOS_GRUPOS 
			WHERE 
				USGR_USUA_NUMERO = " . $usuario . " AND 
				USGR_GRUP_NUMERO = " . $grupo;
// 	echo $SQL;
	$vista_usuario_grupo = array();
	MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_usuario_grupo);
	if (count($vista_usuario_grupo) > 0) {
		return TRUE;
	} else {
		return FALSE;
	}
}

// CFRD
function INTRANET_nombre_asignatura($asign) {
	$SQL = "SELECT
			ASIG_NOMBRE
		FROM
			ASIGNATURAS" . $GLOBALS['sufijo_oracle'] . "
		WHERE
			ASIG_CODIGO = " . $asign;
// 	echo $SQL;
	$asignatura	= array();
	ORACLE_ejecutar_select($GLOBALS['conexion_oracle'], $SQL, $asignatura);
	return $asignatura[0][ASIG_NOMBRE];
}



?>
