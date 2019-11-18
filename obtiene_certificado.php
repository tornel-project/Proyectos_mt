<?php
putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
require("otro/funciones.inc.php");
require('/var/www/new/intranet/fpdf/fpdf.php');
header("Content-Type: text/html;charset=utf-8");

$ruta='/var/www/new/intranet';
// '**************************************************';
  $GLOBALS['conexion_oracle'] = ORACLE_conectar('w','s','s');
// '**************************************************'; 
$rut=$_POST['rut1'];  
$carrera=$_POST['carrera1'];  
$matricula=$_POST['matricula'];  
$largo=strlen($_POST['rut1']);
$rut_p=substr($_POST['rut1'],0,($largo-2));
$rut_consulta = addslashes($rut_p);
$decreto=$_POST['decreto'];  
$email=$_POST['email'];  

function obtiene_mes($mes ){  
    
        if (($mes == '01' ) ) {
                $nom_mes ="Enero"; 
                    }
            if (($mes == '02' ) ) {
                $nom_mes ="Febrero"; 
                    }	
            if (($mes == '03' ) ) {
                $nom_mes ="Marzo"; 
                    }
            if (($mes == '04' ) ) {
                $nom_mes ="Abril"; 
                    }	
            if (($mes == '05' ) ) {
                $nom_mes ="Mayo"; 
                    }
            if (($mes == '06' ) ) {
                $nom_mes ="Junio";	
                    }	
            if (($mes == '07' ) ) {
                $nom_mes ="Julio"; 
                    }	
            if (($mes == '08' ) ) {
                $nom_mes = "Agosto"; 
                    }	
            if (($mes == '09' ) ) {
                $nom_mes ="Septiembre"; 
                    }
            if (($mes == '10' ) ) {
                $nom_mes ="Octubre"; 
                    }
            if (($mes == '11' ) ) {
                $nom_mes ="Noviembre"; 
                    }
            if (($mes == '12' ) ) {
                $nom_mes ="Diciembre"; 
            }	
   return $nom_mes; 
}

$rut2= getPuntosRut($rut);

function getPuntosRut( $rut ){
	$rutTmp = explode( "-", $rut );
	return number_format( $rutTmp[0], 0, "", ".") . '-' . $rutTmp[1];
}


class PDF extends FPDF
{
//Cabecera de página
function Header()
{
    //Logo
    $this->Ln(10);
    $this->Image('/var/www/new/intranet/imagenes/pdf/Logo_VG_con_R_2017.jpg',50,10,100);
    //$this->Image('/var/www/new/intranet/imagenes/pdf/Firma-SG_v1.jpg',75,10,50);
    $this->SetFont("Arial", "B", 20);
    $this->Ln(36);
    $this->Cell(0, 5, $contador."    CERTIFICADO DE TITULO " , 0, 0, "C");
    $this->Ln(15);
}

//Pie de página
function Footer()
{
    //Posición: a 1,5 cm del final
        $decreto=$_POST['decreto'];
        $this->SetY(-100);
	// X,Y,ANCHO
        $this->SetY(100);
        $this->SetY(220);
	$f_CARGO_VICE ='RECTOR                  ';
	$f_VICERRECTOR = 'RENÉ LAGOS CUITIÑO';
	$this->Image('/var/www/new/intranet/imagenes/pdf/Firma_RL.jpg', 135, 180,40, 'left');
        
	//$x_POS = $this->GetX();   
	//$y_POS = $this->GetY();
	//$this->SetXY($x_POS,$y_POS);


	$this->SetFont("Arial", "b", 12);
	$this->Cell(170, 4,  utf8_decode($f_VICERRECTOR)  , 0, 1, "R");
	$this->SetFont("Arial", "", 11);
	$this->Cell(170, 4,  utf8_decode($f_CARGO_VICE)  , 0, 0, "R");
        $this->Ln(8);
        
  	$this->SetFont("Arial", "", 8);
    $DETALLE_FOOTER="La Institución o persona ante quien se presente este certificado,podrá verificar su autenticidad por medio del n° de decreto en la direccion web:www.virginiogomez.cl/validador o escaneando el codigo QR";
    $this->MultiCell(0, 4, utf8_decode($DETALLE_FOOTER));
    //$this->MultiCell(0, 0, "validar en: https://intranet.virginiogomez.cl/intranet/pdf/certificados/cmodulos". $NRO_RUT . ".pdf");
    $this->Cell(185, 3,'Folio: '.$decreto, 0, 0, "R");
            $this->Cell(30, 2,  utf8_decode($x_POS.'-'.$y_POS)  , 0, 0, "R");

    // Posición: a 1,5 cm del final
    $this->SetY(-12);
    $this->Image('/var/www/new/e_certificado/qr.jpg', 170, 245,30, 'left');
   // $this->Image('/var/www/new/e_certificado/cna-2017-2020.jpg', 170, 265,30, 'C');
}
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);

        $SQL = "   SELECT AA.ANTA_RUT TRUT, 
                   AL.ALUM_NOMBRES||' '||AL.ALUM_PATERNO||' '||AL.ALUM_MATERNO NOMBRE,
                   AA.ANTA_DV_RUT DV ,
                   AL.alum_matricula matricula,
                   AL.alum_fecha_titulacion,
                   TO_CHAR(AL.alum_fecha_titulacion, 'DD-MM-YYYY') FECHA_TITULACION, 
                   AL.alum_carr_codigo CARRERA,
                   C.carr_nombre NOMBRECARRERA,
                   C.carr_nombre_l NOM_CARR_LAR,
                   AL.alum_sem_titulacion SEM_TITULACION,
                   TI.TTITULO TITULO,
                   AL.ALUM_DECRETO_TITULACION DECRETO,
                   TO_CHAR(AA.ANTA_FECHA_NAC, 'DD/MM/YYYY') FECHA_NAC,
                   AA.ANTA_SEXO sexo
                    FROM 
                    SISTARES.V_TITULADOS_TODOS  TI, 
                    SISTARES.ALUMNOS  AL, 
                    SISTARES.ANTECEDENTES_ALUMNOS  AA, 
                    SISTARES.CARRERAS  C
                    WHERE AL.alum_antalu_rut=AA.ANTA_RUT 
                    AND(AL.ALUM_ANTALU_RUT=" . $rut_consulta . ")
                    AND (TI.TRUT=AL.alum_antalu_rut)
                    AND  ((TI.TMATRICULA=AL.ALUM_MATRICULA)
                    AND (TI.TRUT=AA.ANTA_RUT)) 
                    AND C.carr_codigo=AL.ALUM_CARR_CODIGO 
                    and c.carr_codigo=" . $carrera . "                     
                    ORDER BY C.carr_nombre_l DESC";
                $v_certificado = array();
                ORACLE_ejecutar_select($GLOBALS['conexion_oracle'], $SQL, $v_certificado);
                foreach ($v_certificado as $det_modulo) {         
                 }
                $dato = explode("-", $v_certificado[0]['FECHA_TITULACION']); 
       //dia
        $dia=$dato[0]; 
        //mes
        $mes_num=$dato[1];  
        //año
        $ano=$dato[2]; 
        
        $mes= obtiene_mes($mes_num); //obtiene nombre mes.
            	
//--------------------------------
        $SQL = "SELECT                
                TO_CHAR(SYSDATE, 'DD-MM-YYYY') FECHA
                FROM
                DUAL";
                $v_fechahoy = array();
                ORACLE_ejecutar_select($GLOBALS['conexion_oracle'], $SQL, $v_fechahoy);
                $dato= explode("-", $v_fechahoy[0]['FECHA']);            
        //dia
        $dia_hoy=$dato[0]; 
        //mes
        $mes_hoy=$dato[1];  
        //año
        $ano_hoy=$dato[2]; 
        $mes_hoy_desc= obtiene_mes($mes_hoy); //obtiene nombre mes.      
        $decreto=ltrim($det_modulo['DECRETO'],0);
//--------------------------------
            $SQLu="UPDATE SISTARES.ECERT_DETALLE_COMPRA
                   SET E_ESTADO_COMPRA=1
                   WHERE E_DET_MATRICULA=" . $matricula . "
                   AND E_RUT_SOLICITA=" . $rut_consulta . "
                   AND E_EMAIL_SOLICITA='" . $email . "'";
                   $actualiza = array();
            	   ORACLE_ejecutar_select($GLOBALS['conexion_oracle'],$SQLu, $actualiza);        		  
                   if (count($actualiza ) != 0) {
                       }else{
                   }                     
//---------------------------------
//$detalle1="CERTIFICO QUE POR DECRETO N° ".$det_modulo['DECRETO']." DE RECTORIA DE FECHA ".$dia." DE ".$nom_mes." DE ".$ano." CONFIRIO EL TITULO PROFESIONAL DE "
  //      .$det_modulo['TITULO']." A DON ".$det_modulo['NOMBRE']." CEDULA DE IDENTIDAD N° ".$rut; 
$pdf->MultiCell(190, 10,utf8_decode(''), 0,2,'');
$pdf->SetFont('Arial','' , 14);          
$pdf->Cell(85, 15,utf8_decode('CERTIFICO QUE POR DECRETO N°'), 0,0,'');
$pdf->SetFont('Arial','BU',14);
$pdf->Cell(60, 15,utf8_decode('            '.$decreto.'          '), 0,0,'c');
$pdf->SetFont('Arial', '', 14);          
$pdf->Cell(35, 15,utf8_decode('DE RECTORIA'),0,1,'');
$pdf->Cell(30, 15,utf8_decode('DE FECHA'),0,0,'');
$pdf->SetFont('Arial','BU',14);
$pdf->Cell(150, 15,utf8_decode('                                 '.$dia.' de '.$mes.' de '.$ano.'                                  '), 0,1,'c');
$pdf->SetFont('Arial', '', 14);          
$pdf->Cell(40, 15,utf8_decode('CONFIRIO EL'),0,0,'');
$pdf->SetFont('Arial','BU',14);
$pdf->Cell(140, 15,utf8_decode('                               TITULO PROFESIONAL                              '), 0,1,'c');
$pdf->SetFont('Arial', '', 14);          
$pdf->Cell(15, 15,utf8_decode('DE'), 0,0,'');
$pdf->SetFont('Arial','BU',14);
$pdf->Cell(165, 15,utf8_decode('                '.$det_modulo['TITULO'].'              '), 0,1,'c');
$pdf->SetFont('Arial', '', 14);
IF($det_modulo['SEXO']=='M'){
$pdf->Cell(25, 15,utf8_decode('A DON'), 0,0,'');
}else{
$pdf->Cell(25, 15,utf8_decode('A DOÑA'), 0,0,'');
}
$pdf->SetFont('Arial','BU',14);
$pdf->Cell(165, 15,utf8_decode('                '.$det_modulo['NOMBRE'].'               '), 0,1,'c');
$pdf->SetFont('Arial', '', 14);          
$pdf->Cell(70, 15,utf8_decode('CEDULA DE IDENTIDAD N°'), 0,0,'');
$pdf->SetFont('Arial','BU',14);
$pdf->Cell(100, 15,utf8_decode('                            '.$rut2.'                              '), 0,1,'c');

$pdf->MultiCell(180, 8,utf8_decode(''), 0,2,'');
$pdf->SetFont('Arial', 'B', 14);          
$pdf->MultiCell(180,5,utf8_decode('CONCEPCION, '.$dia_hoy.' de '.$mes_hoy_desc.' de '.$ano_hoy), 0,2,'');
$pdf->SetY(-1);

$archivo_certificado = "/var/www/new/e_certificado/pdf/".$rut_consulta.'_'.$det_modulo['DECRETO'].".pdf";
$pdf->Output($archivo_certificado,F);
$archivo="https://intranet.virginiogomez.cl/e_certificado/pdf/".$rut_consulta.'_'.$det_modulo['DECRETO'].".pdf";
header("Location: " . $archivo);
$email=$email;
//$pdf->Output();
//----------------------------Envia correo------------------- //
                        $asunto3="Compra Exitosa Certificado Titulo Digital";
                    	$correo_remitente = 'soporte-alumni@virginiogomez.cl';
                    	$correo_oculto = 'mtornel@virginiogomez.cl';
                        $nombre_remitente = 'Red Alumni Instituto Profesional Virginio G�mez';
                        $mensaje3='             
                        Estimado(a).<br/> 
                        Se genero certificado de titulo, para descargarlo acceder a la siguiente 
                        ruta:
                        <p>https://intranet.virginiogomez.cl/e_certificado/pdf/'.$rut_consulta.'_'.$det_modulo['DECRETO'].'.pdf<p> 
                        <img src="https://www.virginiogomez.cl/images/logo.jpg" > <br/><br /><br /> ';
                        $mensaje=$mensaje3.
                    	$headers3 = "From: ". $nombre_remitente . " <".$correo_remitente.">\r\n";
                        $headers3 .= "BCC: ". strip_tags($correo_oculto) . "\r\n"; 
                        $headers3 .= "CC: ". strip_tags($correo_remitente) . "\r\n"; 
                        $headers3 .= "MIME-Version: 1.0\r\n";
                    	$headers3 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                       // $mail->AddStringAttachment($fichero, 'fichero.pdf', 'base64', 'application/pdf');
                        $mailsent3 = mail($email, $asunto3, $mensaje3, $headers3); 

?>