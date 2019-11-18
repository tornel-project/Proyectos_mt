<?php
// version a.cabezas
include("header.php");
putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
require("otro/funciones.inc.php");
header("Content-Type: text/html;charset=utf-8");
// '**************************************************';
$GLOBALS['conexion_oracle'] = ORACLE_conectar('w', 's', 's');
// '**************************************************';
$rut = $_POST['rut'];
$largo = strlen($_POST['rut']);
$rut_p = substr($_POST['rut'], 0, ($largo - 2));
$rut_consulta = addslashes($rut_p);
$carrera = $_POST['carrera'];
$email = $_POST['email'];

$probar="12aaaaa 22ssssss 32hhhh 4244444 528888 62dddddddddd 72 82 2ggggg9 102222222  ja ja ja ja ja ja ja ja ja ja ja u y u m p o m";
$largo_probar= strlen($probar);
//print_r($largo_probar);

$ejemplo = count(explode(" ", $probar));
print_r($ejemplo);

$SQL0 = "SELECT count(*) contador FROM 
                    SISTARES.V_TITULADOS  TI, 
                    SISTARES.ALUMNOS  AL, 
                    SISTARES.ANTECEDENTES_ALUMNOS  AA, 
                    SISTARES.CARRERAS  C,
                    SISTARES.TITULOS TIT
                    WHERE AL.alum_antalu_rut=AA.ANTA_RUT 
                    AND C.carr_codigo=TIT.titu_cod_carrera 
                    AND(AL.ALUM_ANTALU_RUT=" . $rut_consulta . ")
                    AND (TI.alum_antalu_rut=AL.alum_antalu_rut)
                    AND  ((TI.ALUM_MATRICULA=AL.ALUM_MATRICULA)
                    AND (TI.ALUM_ANTALU_RUT=AA.ANTA_RUT)) 
                    AND C.carr_codigo=AL.ALUM_CARR_CODIGO 
                    and c.carr_codigo=" . $carrera . " ";

$v_contador= array();
ORACLE_ejecutar_select($GLOBALS['conexion_oracle'], $SQL0, $v_contador);
$valida_alumno = $v_contador[0]['CONTADOR'];
if ($valida_alumno==0){
    header("Location: index.php");
}else{ 

$SQL2 = "    SELECT ECERT_PRECIO FROM SISTARES.E_CERTIFICADOS WHERE ECERT_ID=1";
$v_precio = array();
ORACLE_ejecutar_select($GLOBALS['conexion_oracle'], $SQL2, $v_precio);
$precio_certi = $v_precio[0]['ECERT_PRECIO'];

$SQL = "   SELECT AA.ANTA_RUT TRUT, 
                   upper(AL.ALUM_NOMBRES||' '||AL.ALUM_PATERNO||' '||AL.ALUM_MATERNO) NOMBRE,
                   AA.ANTA_DV_RUT DV ,
                   AL.alum_matricula matricula,
                   AL.alum_fecha_titulacion,
                   TO_CHAR(AL.alum_fecha_titulacion, 'DD-MM-YYYY') FECHA_TITULACION, 
                   AL.alum_carr_codigo CARRERA,
                   C.carr_nombre NOMBRECARRERA,
                   C.carr_nombre_l NOM_CARR_LAR,
                   AL.alum_sem_titulacion SEM_TITULACION,
                   TI.TTITULO NOMBRETITULO,
                   AL.ALUM_DECRETO_TITULACION DECRETO,
                   TO_CHAR(AA.ANTA_FECHA_NAC, 'DD/MM/YYYY') FECHA_NAC
                    FROM 
                    SISTARES.V_TITULADOS_TODOS  TI, 
                    SISTARES.ALUMNOS  AL, 
                    SISTARES.ANTECEDENTES_ALUMNOS  AA, 
                    SISTARES.CARRERAS  C
                    WHERE AL.alum_antalu_rut=AA.ANTA_RUT 
                    and al.alum_decreto_titulacion=ti.tdecreto
                    AND(AL.ALUM_ANTALU_RUT=" . $rut_consulta . ")
                    AND (TI.TRUT=AL.alum_antalu_rut)
                    AND  ((TI.TMATRICULA=AL.ALUM_MATRICULA)
                    AND (TI.TRUT=AA.ANTA_RUT)) 
                    AND C.carr_codigo=AL.ALUM_CARR_CODIGO 
                    and c.carr_codigo=" . $carrera . "                     
                    ORDER BY C.carr_nombre_l DESC";

$v_carreras = array();

ORACLE_ejecutar_select($GLOBALS['conexion_oracle'], $SQL, $v_carreras);

foreach ($v_carreras as $t_curso) {
}
// inserta solicitud de certificado
            $SQLg="INSERT INTO SISTARES.ECERT_DETALLE_COMPRA
                   (E_COD_COMPRA, E_COD_TRAN, E_DET_MATRICULA, E_MONTO_PAGO, E_ESTADO_COMPRA, E_FECHA_SOLICITUD, E_RUT_SOLICITA, E_EMAIL_SOLICITA)
                   VALUES
                   (1, 1, ".$t_curso['MATRICULA'].", ".$precio_certi.",0 ,sysdate,".$rut_consulta.",'".$email."')";
            
                   $devuelve = array();
            	   ORACLE_ejecutar_select($GLOBALS['conexion_oracle'],$SQLg, $devuelve);

                  // print_r($devuelve);
                   
                   if (count($devuelve ) != 0) {
                  // print_r('INSERT OK');//
                       }else{
                  // print_r('NADA');//
                   }
?>


<script>
//-----------------------------------------
// valida si hay cupos antes de inscribir
//------------------------------------------
    function validacupos() {
        var data = new FormData($('#form2')[0]);
        var url = "valida_cupos.php";
        $.ajax({
            url: url,
            type: 'POST',
            contentType: false,
            data: data,
            processData: false,
            cache: false,
            success: function (response) {
                $('#error_1').hide();
                $('#error_0').hide();
                $('#mensaje_1').html(response);
                $('#mensaje_1').show();
            }
            , fail: function (response) {
                alert('Error al llamar programa');
            }

        }); //fin ajax
    }
</script>    
<html>
    <head>
        <!-- <meta charset="UTF-8"> -->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
        

        <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
        <!--   <meta name="viewport" content="width=device-width, initial-scale=1"> -->
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />


        <title>Resumen de compra</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/form.css" rel="stylesheet">
    </head>
    <body  oncopy="return false;">
        <div class="row vertical-offset-100">
            <div class="col-md-6 col-md-offset-3">
                <!--  mensajes de validación datos pantalla -->
                <div class="alert alert alert-success" role="alert" id="mensaje_1" style="display:none">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Felicidades!</strong> Inscripci&oacute;n realizada exitosamente.El xx de xxxx te enviar&aacute;n tu usuario y contrase&ntilde;a al correo que indicaste.Consultas a alumni@virginiogomez.cl
                </div>

                <!--  mensajes de validación datos pantalla -->
                <div class="alert alert alert-danger" role="alert" id="error_1" style="display:none">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Error:datos digitados</strong>
                </div>

                <!--  validacion titulado -->
                <div class="alert alert alert-danger" role="alert" id="error_0" style="display:none">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Error:</strong> Rut no corresponde a un titulado del Instituto Profesional Virginio G&oacute;mez. Cualquier consulta escriba a <strong>alumni@virginiogomez.cl</strong>
                </div>
            </div>


            <div class="container p-4">
                <div class="row"> 
                    <div class="col"> 
                        <div class="card card-body">
                            <form action="obtiene_certificado.php" name="form2" id="form2" method="POST">
                                <div class="form-group">				
                                    <h4 class="alert alert-info"> Resumen de compra certificado de titulo:</h4>
                                    <br>
                                </div>
                                <table class="table" name="tabla">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Nombre Titulado</th>
                                            <th scope="col">Rut</th>
                                            <th scope="col">Titulo</th>
                                            <th scope="col">Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($v_carreras as $t_curso) { ?>							  		
                                            <tr>
                                                <th scope="row"><?php utf8_decode(print($t_curso['NOMBRE'])); ?></th>
                                                <th scope="row"><?php print($rut); ?></th>
                                                <th scope="row"><?php print($t_curso['NOMBRETITULO']); ?></th>
                                                <th scope="row">$<?php print($precio_certi); ?></th>

                                            </tr>	
                                    <?php } ?>
                                    </tbody>
                                    <tr scope="row" colspan="4">
                                        <th></th>
                                    </tr>   
                                    <tr>
                                        <th scope="row"></th>
                                        <th scope="row" colspan="2"><br><button type="submit" class="alert alert-danger" aling="center">Comprar Certificado de Titulo</button></th>
                                        <th scope="row"></th>
                                    </tr>	
                                </table>
                                <input type="hidden" name="rut1" id="rut" size="20" value="<?php print($rut); ?>">   
                                <input type="hidden" name="carrera1" id="carrera" size="5" value="<?php print($t_curso['CARRERA']); ?>">
                                <input type="hidden" name="matricula" id="carrera" size="5" value="<?php print($t_curso['MATRICULA']); ?>"> 
                                <input type="hidden" name="decreto" id="decreto" size="10" value="<?php print($t_curso['DECRETO']); ?>"> 
                                <input type="hidden" name="email" id="email" size="10" value="<?php print $email ?>">     


                            </form>
                        </div>	
                    </div>

                </div>

            </div>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
            <script src="js/bootstrap.min.js"></script>
    </body>
</html>
<?php 
}
?>

