<?php // version a.cabezas
putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
require("otro/funciones.inc.php");
// '**************************************************';
  $GLOBALS['conexion_oracle'] = ORACLE_conectar('w','s','s');
// '**************************************************';
$largo=strlen($_POST['rut']);
$rut_p=substr($_POST['rut'],0,($largo-2));
$rut_consulta = addslashes($rut_p);

           $SQL_1 ="select count(*) cantidad 
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
                    AND C.carr_codigo=AL.ALUM_CARR_CODIGO";
                $v_cantidad = array();
	        ORACLE_ejecutar_select($GLOBALS['conexion_oracle'], $SQL_1, $v_cantidad);
               
                
           $SQL =" select AL.alum_carr_codigo CARRERA,
                   C.carr_nombre_l NOM_CARR_LAR
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
                    ORDER BY C.carr_nombre_l DESC  ";
                $v_carreras = array();
	        ORACLE_ejecutar_select($GLOBALS['conexion_oracle'], $SQL, $v_carreras);
                    if ($v_cantidad[0]['CANTIDAD'] == 0) {
  			   echo '<option value="0">No tiene Titulos para solicitar</option>';
			   return;      
                    }else{ 
                           echo '<option value="0">Seleccionar Titulo</option>';
                           foreach ($v_carreras as $t_curso){
                           echo '<option value="'.$t_curso["CARRERA"].'">'.$t_curso["NOM_CARR_LAR"].'</option>';                   
                           }    
                    }
		

