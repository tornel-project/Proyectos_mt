<?php
function administrador_intranet_usuarios() {
?>
<script language="javascript" type="text/javascript">
<!--
function buscar_usuario(f) {
	if (f.consulta.value == '') {
		alert('Falta contenido a buscar.');
		f.consulta.focus();
		return false;
	}
	f.submit();
}
-->
</script>
<p class="titulo">Usuarios</p>
<form name="formulario_buscar_usuario" id="formulario_buscar_usuario" action="<?php echo $_SERVER['PHP_SELF']?>" method="get">
<input type="hidden" name="modulo" value="administrador">
<input type="hidden" name="opcion" value="intranet_buscar_usuario">
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="tabla">
	<tr>
		<td>Buscar:</td>
		<td>
			<select class="lista"  name="criterio" id="criterio">
				<option value="usuario">Usuario</option>
				<option value="rut">R.U.T.</option>
				<option value="nombre">Nombre</option>
				<option value="apellido">Apellido</option>
			</select>
		</td>
	    <td>Grupo:</td>
        <td><select class="lista"  name="grupo" id="grupo">
		<option value="0">Todos</option>
<?php
		if (INTRANET_usuario_pertenece_a_grupo($_SESSION['usuario'], 1)) {
			$SQL = "SELECT
						*
					FROM
						GRUPOS
					ORDER BY
						GRUP_NOMBRE";
		}
		if (INTRANET_usuario_pertenece_a_grupo($_SESSION['usuario'], 8)) {
			$SQL = "SELECT
						*
					FROM
						GRUPOS
					WHERE
						GRUP_ES_ADMINISTRADOR = 0
					ORDER BY
						GRUP_NOMBRE";
		}
		$vista_grupos_intranet = array();
		MYSQL_ejecutar_select($GLOBALS['conexion_mysql'], $SQL, $vista_grupos_intranet);
		for($i = 0; $i < count($vista_grupos_intranet); $i++) {
?>
<option value="<?php echo $vista_grupos_intranet[$i]['GRUP_NUMERO']?>"><?php echo $vista_grupos_intranet[$i]['GRUP_NOMBRE']?></option>
<?php
		}
?>

		    </select>
		</td>
		<td>
			<input name="consulta" type="text" class="cuadro_texto"    id="consulta" value="%" size="20">
		</td>

	    <td><input type="button" class="boton"  value="BUSCAR" onClick="javascript:buscar_usuario(formulario_buscar_usuario);"></td>
	</tr>
</table>
</form>

<p>
</p>
<?php
}
?>
