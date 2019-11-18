<?php
putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
require("otro/funciones.inc.php");
// '**************************************************';
  $GLOBALS['conexion_oracle'] = ORACLE_conectar('w','s','s');
 
// '**************************************************';
$largo=strlen($_POST['rut']);
$rut_p=substr($_POST['rut'],0,($largo-2));
$rut_consulta = addslashes($rut_p);
$dv= substr($_POST['rut'],-1);
if ($dv=='k'){
    $dv='K';
    
}
$SQL2 ="SELECT AA.ANTA_RUT TRUT, 
        upper(AL.ALUM_NOMBRES||' '||AL.ALUM_PATERNO||' '||AL.ALUM_MATERNO) NOMBRE,
        AA.ANTA_DV_RUT DV ,
        TO_CHAR(AL.alum_fecha_titulacion, 'YYYY') FECHA, 
        AL.alum_carr_codigo CARRERA,
        C.carr_nombre NOMBRECARRERA,
        AL.alum_sem_titulacion SEM_TITULACION, 
        TO_CHAR(AA.ANTA_FECHA_NAC, 'DD/MM/YYYY') FECHA_NAC
        FROM 
            SISTARES.V_TITULADOS  TI, 
            SISTARES.ALUMNOS  AL, 
            SISTARES.ANTECEDENTES_ALUMNOS  AA, 
            SISTARES.CARRERAS  C
            WHERE AL.alum_antalu_rut=AA.ANTA_RUT 
            AND(AL.ALUM_ANTALU_RUT=" .$rut_consulta.")
            AND (TI.alum_antalu_rut=AL.alum_antalu_rut)
            AND  ((TI.ALUM_MATRICULA=AL.ALUM_MATRICULA)
            AND (TI.ALUM_ANTALU_RUT=AA.ANTA_RUT)) 
            AND C.carr_codigo=AL.ALUM_CARR_CODIGO
            ORDER BY TI.ALUM_ANTALU_RUT ASC, AL.alum_sem_titulacion DESC";
        	$v_titulados = array();
	        ORACLE_ejecutar_select($GLOBALS['conexion_oracle'], $SQL2, $v_titulados);
			if ($v_titulados[0]['TRUT'] == 0) {
  			   echo "1"; 
			   return;
			}else{
                            if ($v_titulados[0]['DV'] != $dv) {
				echo "4"; 
			        return;
                            }else{
                            echo $v_titulados[0]['NOMBRE'];
                            }
                        } 

?>
