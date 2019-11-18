<?php
include("header.php"); 
putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
require("otro/funciones.inc.php");
// '**************************************************';
  $GLOBALS['conexion_oracle'] = ORACLE_conectar('w','s','s');
// '**************************************************';
  
?>


<script language="javascript" type="text/javascript">
<!--

function fecha_valida(fecha) {
        f = fecha.split('-');
        if (f.length < 3) {
                return false;
        }
        if ((isNaN(f[0])) || (isNaN(f[1])) || (isNaN(f[2]))) {
                return false;
        }
        if ((f[1] < 1) || (f[1] > 12)) {
                return false;
        }
        if ((f[0] < 1) || (f[0] > 31)) {
                return false;
        }
        return true;
}

function agregar_noticia(f) {
	if (f.titular.value == '') {
		alert('Debe especificar un titular.');
		f.titular.focus();
		return false;
	}
	if (f.texto.value == '') {
		alert('Debe especificar un texto.');
		f.texto.focus();
		return false;
	}
 	if (!fecha_valida(f.fecha.value)) {
 		alert('Fecha no válida.\nDebe ser de la forma DD-MM-AAAA');
 		f.fecha.focus();
 		return false;
 	}
	f.submit();
}
-->
</script>
<form action="<?php  echo $_SERVER['PHP_SELF'] . "?modulo=administrador&amp;opcion=noticias_agregar_noticia";?>" method="post" enctype="multipart/form-data" name="formulario_noticia" id="formulario_noticia">
<p class="titulo">Nuevo Anuncio Intranet </p>
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="tabla">
<tr>
	<td><b>Fecha (*)</b>
	    <input name="fecha" type="text" class="cuadro_texto_virginia"   id="fecha" value="<?php 
		$fecha = getdate();
		if ($fecha['mday'] < 10) {
			echo "0" . $fecha['mday'];
		} else {
			echo $fecha['mday'];
		}
		echo "-";
		if ($fecha['mon'] < 10) {
			echo "0" . $fecha['mon'];
		} else {
			echo $fecha['mon'];
		}

		echo "-" . $fecha['year'];
		?>" size="13"></td>
</tr>
<tr>
  <td><b>Sede</b>
    <select name="sede" id="sede" class="lista">
	<?php 
		echo "<option value='1'>Todas las Sedes</option";
	?>
    </select>
    </td>
</tr>
<!--<tr>
    <td><input name="en_portada" type="checkbox" id="en_portada" value="1" />
        En portada </td>
</tr>-->
<tr>
    <td class="celdad"><b>Epigrafe</b></td>
</tr>
<tr>
    <td><input name="epigrafe" type="text" class="cuadro_texto_virginia"    id="epigrafe" size="78"></td>
</tr>
<tr>
    <td class="celdad"><b>Titular (*)</b></td>
</tr>
<tr>
    <td><input name="titular" type="text" class="cuadro_texto_virginia"    id="titular" size="78"></td>
</tr>
<tr>
    <td class="celdad"><b>Bajada o Resumen</b></td>
</tr>
<tr>
    <td><textarea class="cuadro_texto_virginia"  name="bajada_resumen" cols="80" rows="5" id="bajada_resumen"></textarea></td>
</tr>
<tr>
    <td class="celdad"><b>Texto (*)</b></td>
</tr>
<tr>
    <td><textarea class="cuadro_texto_virginia"  name="texto" cols="80" rows="10" id="texto"></textarea></td>
</tr>
<tr>
    <td><b>Imagen</b>
        <input name="imagen" type="file" class="boton"  id="imagen"></td>
</tr>
<tr>
    <td class="celdad"><b>Pie de Foto</b></td>
</tr>
<tr>
    <td><textarea class="cuadro_texto_virginia"  name="pie_foto" cols="80" rows="5" id="pie_foto"></textarea></td>
</tr>
<tr>
    <td align="center"><input type="button" class="boton"  value="Agregar" onClick="javascript:agregar_noticia(formulario_noticia);"></td>
</tr>
</table>
<p>(*) Datos obligatorios </p>
</form>
