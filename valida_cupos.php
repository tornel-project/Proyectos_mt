<?php // version a.cabezas
putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
require("otro/funciones.inc.php");
// '**************************************************';
  $GLOBALS['conexion_oracle'] = ORACLE_conectar('w','s','s');
// '**************************************************';
/*
$curso  = $_POST['curso_s'];
$celular = $_POST['celular'];
$email = $_POST['email'];
$fono_fijo = $_POST['fono_fijo'];
$salida2['MENSAJE2']=null;*/
$nombre_remitente='mtornel@virginiogomez.cl';
$correo_remitente='mtornel@virginiogomez.cl';
        
//----------------------------------------------
$SQLh ="select to_char(sysdate,'YYYYMMDDHH24MI') as HOY
        from dual";
$v_hoy = array();
ORACLE_ejecutar_select($GLOBALS['conexion_oracle'], $SQLh, $v_hoy); 
$FECHA_HOY= $v_hoy[0]['HOY'];
//------------------------------------------
                    //----------------------------18-4-2018 ------------------- //
                       
                     	$asunto3="Confirmaci�n Inscripci�n M�dulos Virtuales";
                    	$correo_remitente = 'soporte-alumni@virginiogomez.cl';
                    	$correo_oculto = 'mtornel@virginiogomez.cl';
                        $nombre_remitente = 'Red Alumni Instituto Profesional Virginio G�mez';
                        $mensaje3='             
                        Estimado(a).<br/> 
                        En nombre del Instituto Profesional Virginio G�mez le damos la bienvenida al curso <strong>'.'"'. $glosa_curso.'"</strong>
                        Sus claves de acceso ser�n enviadas entre el '. $f_ini.' y '. $f_fin.', al correo que nos ha indicado.<br/>
                        Consultas a <strong>alumni@virginiogomez.cl.</strong><br />
                        Le saluda con toda atenci�n,<br /><br />
                        <strong>Red Alumni</strong><br /><br />
                        <img src="https://www.virginiogomez.cl/images/logo.jpg" > <br/><br /><br /> ';
                    	$headers3 = "From: ". $nombre_remitente . " <".$correo_remitente.">\r\n";
                        $headers3 .= "BCC: ". strip_tags($correo_oculto) . "\r\n"; 
                        $headers3 .= "CC: ". strip_tags($correo_remitente) . "\r\n"; 
                        $headers3 .= "MIME-Version: 1.0\r\n";
                    	$headers3 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                        $mailsent3 = mail($email, $asunto3, $mensaje3, $headers3);                  
                    //-----------------------------------------------//



