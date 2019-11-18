<?php
include("header.php"); 
putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
require("otro/funciones.inc.php");
// '**************************************************';
  $GLOBALS['conexion_oracle'] = ORACLE_conectar('w','s','s');
// '**************************************************';
?>

<!DOCTYPE html>
<html lang="es">
<head>
   <!-- <meta charset="UTF-8"> -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
 
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
 <!--   <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
   
    
    <title>Obtencion de Certificados Titulos en linea</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/form.css" rel="stylesheet">
</head>
<script language="javascript" type="text/javascript">

function validar_tecla_email(e) {
  tecla = (document.all) ? e.keyCode : e.which;
    if ((tecla==13) || (tecla==40)){
	$('#error_1').hide()  ; 
	document.form1.email_v.focus(); 
  }
}
function validar_tecla_email_v(e) {
  tecla = (document.all) ? e.keyCode : e.which;
   if ((tecla==13) || (tecla==40)){
	$('#error_1').hide()  ; 
	document.form1.carrera.focus(); 
  }
}


function display_mensajes() {
    $('#error_1').hide()  ; 
    $('#error_0').hide()  ; 
    $('#mensaje_1').hide() ;
}
//--------------------------------------------------------
//  validaciones antes de enviar
//--------------------------------------------------------
function validar_boton() {
// largo rut	
 var str = document.form1.rut.value;
 var n = str.length;
 //if ((n < 9) || n> 10 ){
if (n < 9) {
	   document.getElementById('error_1').innerHTML = '<strong>Error:</strong> Ingrese un Rut v&aacute;lido!';
	   $('#error_1').show()  ; 
	   $('#error_0').hide()  ; 
           $('#mensaje_1').hide() ;
	    //
	   document.form1.nombre_completo.disabled = true;
           document.form1.email.disabled = true;
           document.form1.email_v.disabled = true;
	   document.form1.boton1.disabled = true;
	   //
	   document.form1.rut.focus(); 
	    return false;
 }
  
 //------------------------------------------------------
 //correo 1
//-----------------------------------------------------
   var correo_ll = document.form1.email.value;
   var largo_mail = correo_ll.length;
 //
 if (largo_mail == 0)  {
	 document.getElementById('error_1').innerHTML = '<strong>Error: </strong>Debe ingresar un correo de contacto.';
	 $('#error_1').show()  ; 
	 $('#error_0').hide()  ; 
         $('#mensaje_1').hide() ;
	 //    
	 document.form1.email.focus();
	 return false;
 }  
  
   if (largo_mail < 6)  {
	 document.getElementById('error_1').innerHTML = '<strong>Error: </strong>Debe ingresar un correo de contacto valido.';
	 $('#error_1').show()  ; 
     $('#error_0').hide()  ; 
     $('#mensaje_1').hide() ;
	 //     
	 document.form1.email.focus();
	 return false;
 }  

 // busca caracteres especiales
   if (document.form1.email.value!==''){ 
       var dde= document.form1.email.value;
	   var ge = dde.indexOf("@");
	   var te = dde.indexOf(".");
	   if (ge == -1)  {
		  document.getElementById('error_1').innerHTML = '<strong>Error:</strong> Falta la @ en el correo de contacto.';
	      $('#error_1').show()  ; 
	      $('#error_0').hide()  ; 
          $('#mensaje_1').hide() ;
	     //     
	     document.form1.email.focus(); 
	     return false;
	   }
	   if (te == -1)  {
		 document.getElementById('error_1').innerHTML = '<strong>Error:</strong> Falta el punto en el correo de contacto.';
	      $('#error_1').show()  ; 
          $('#error_0').hide()  ; 
          $('#mensaje_1').hide() ;
	     //     
	     document.form1.email.focus(); 
	     return false;
	   }
  }
 //-----------------------------
 //largo correo verificación
 //-----------------------------
  var correo2 = document.form1.email_v.value;
  var largo_mail2 = correo2.length;
   if (largo_mail2 == 0) {
	  document.getElementById('error_1').innerHTML = '<strong>Error:</strong> Debe ingresar el Correo de validaci&oacute;n.';
	  $('#error_1').show()  ; 
      $('#error_0').hide()  ; 
      $('#mensaje_1').hide() ;
	 //        
	 document.form1.email_v.focus();
	 return false;
  }   
  if (largo_mail2 < 6) {
	  document.getElementById('error_1').innerHTML = '<strong>Error:</strong> Correo de verificaci&oacute;n NO v&aacute;lido!.';
	  $('#error_1').show()  ; 
	  $('#error_0').hide()  ; 
      $('#mensaje_1').hide() ; 
	  //
	 document.form1.email_v.focus();
	 return false;
  }   
 if (document.form1.email_v.value!==''){ 
       var dde2= document.form1.email_v.value;
	   var ge2 = dde2.indexOf("@");
	   var te2 = dde2.indexOf(".");
	   if (ge2 == -1)  {
 		 document.getElementById('error_1').innerHTML = '<strong>Error:</strong> Falta ingresar la @ en el correo de verificaci&oacute;n.';
	     $('#error_1').show()  ; 
 	     $('#error_0').hide()  ; 
         $('#mensaje_1').hide() ; 
		 //
	     document.form1.email_v.focus(); 
	     return false;
	   }
	   if (te2 == -1)  {
 		 document.getElementById('error_1').innerHTML = '<strong>Error:</strong> Falta el punto en el correo de verificaci&oacute;n.';
	     $('#error_1').show()  ; 
         $('#error_0').hide()  ; 
         $('#mensaje_1').hide() ; 
		 //
	     document.form1.email_v.focus(); 
	     return false;
	   }
  }
 
 
 // compara correos
  if  (document.form1.email.value!= document.form1.email_v.value){
	  document.getElementById('error_1').innerHTML = '<strong>Error:</strong> Correo de verificaci&oacute;n no es igual a correo de contacto, revise.';
	  $('#error_1').show()  ; 
	  $('#error_0').hide()  ; 
      $('#mensaje_1').hide() ; 
	  //
	  document.form1.email.focus();
	  return false;
 } 
 // curso
  if  (document.form1.carrera.value== ''){
	  document.getElementById('error_1').innerHTML = '<strong>Error: </strong>Debe seleccionar el curso en el que se incribe.';
	  $('#error_1').show()  ; 
	  $('#error_0').hide()  ; 
          $('#mensaje_1').hide() ; 
	  //
	  document.form1.carrera.focus();
	  return false;
 } 
 
 // generaCertificado() ;

 
}
//--------------------------------------------------------------------------------------------------
// validaciones en campos individuales
//--------------------------------------------------------------------------------------------------
//---------------------
// validacion del rut
//---------------------
function validar_rut() {
  var str = document.form1.rut.value;
  var n = str.length;
//  if ((n < 9) || n> 10 ){
  if (n < 9) {
	   document.getElementById('error_1').innerHTML = '<strong>Error:</strong> Ingrese un Rut v&aacute;lido!';
	   $('#error_1').show()  ; 
	   $('#error_0').hide()  ; 
           $('#mensaje_1').hide() ;
	    //
	document.form1.nombre_completo.value = "";
	document.form1.nombre_completo.disabled = true;
        document.form1.email.disabled = true;
        document.form1.email_v.disabled = true;
	document.form1.boton1.disabled = true;
	   //
	   limpia_campos();
	   document.form1.rut.focus(); 
	    return false;
  }

   if (document.form1.rut.value==''){
	    document.getElementById('error_1').innerHTML = '<strong>Error: </strong>Debe ingresar un rut v&aacute;lido!';
	   $('#error_1').show()  ; 
	   $('#error_0').hide()  ; 
           $('#mensaje_1').hide() ;
	    //
	   //
	   document.form1.nombre_completo.value = "";
	   document.form1.nombre_completo.disabled = true;
           document.form1.email.disabled = true;
           document.form1.email_v.disabled = true;
	   document.form1.boton1.disabled = true;
	   //
	   limpia_campos();
	   document.form1.rut.focus(); 
	   return false;
   }
   
   if (document.form1.rut.value!==''){ 
       var dd= document.form1.rut.value;
	   var g = dd.indexOf("-");
	   var t = dd.indexOf(".");
	   if (g == -1)  {
		 document.getElementById('error_1').innerHTML = '<strong>Error:</strong> Debe ingresar guion y digito verificador';
		 $('#error_1').show()  ; 
                 $('#error_0').hide()  ; 
                 $('#mensaje_1').hide() ;
		 document.form1.nombre_completo.value = "";
		 deshabilitar_campos()
                 $("#rut").focus();
		 //
		document.form1.nombre_completo.value = "";
                document.form1.nombre_completo.disabled = true;
                document.form1.email.disabled = true;
                document.form1.email_v.disabled = true;
		document.form1.boton1.disabled = true;
	     //
		 limpia_campos();
	     document.form1.rut.focus(); 
	     return false;
	   }
	   if (t !== -1)  {
		document.getElementById('error_1').innerHTML = '<strong>Error:</strong> Rut NO debe contener puntos.';
                $('#error_1').show()  ; 
                $('#error_0').hide()  ; 
                $('#mensaje_1').hide() ;
	    //
		 //
		 document.form1.nombre_completo.value = "";
                 document.form1.nombre_completo.disabled = true;
                 document.form1.email.disabled = true;
                 document.form1.email_v.disabled = true;
		 document.form1.boton1.disabled = true;
		 limpia_campos();
	     //
	     document.form1.rut.focus(); 
	     return false;
	   }
   }
   habilitar_campos();
   validaformulario();
   obtieneCarrera();
}
//----------------------------
// celular
//----------------------------
function validar_celular(){
  }
//-------------------
// telefono
//-------------------
function validar_telefono() {
}
//----------------------------
// email contacto
//----------------------------
function validar_email1() {
  var llargo_email1 = document.form1.email.value;
  var largo = llargo_email1.length;
   if (largo == 0){
	  document.getElementById('error_1').innerHTML = '<strong>Error:</strong> Debe ingresar Correo de contacto.';
	  $('#error_1').show()  ; 
	  $('#error_0').hide()  ; 
      $('#mensaje_1').hide() ; 
	  //
	 document.form1.email.focus();
	 return false;
  }
  if (largo < 6){
	 document.getElementById('error_1').innerHTML = '<strong>Error:</strong> Correo de contacto incorrecto!.';
	  $('#error_1').show()  ; 
	  $('#error_0').hide()  ; 
      $('#mensaje_1').hide() ; 
	  // 
	 document.form1.email.focus();
	 return false;
  }
  
  if (document.form1.email.value!==''){ 
       var dde= document.form1.email.value;
	   var ge1 = dde.indexOf("@");
	   var te1 = dde.indexOf(".");
	   if (ge1 == -1)  {
		 document.getElementById('error_1').innerHTML = '<strong>Error:</strong> Falta la @ en el correo de contacto.';
	     $('#error_1').show()  ; 
	     $('#error_0').hide()  ; 
         $('#mensaje_1').hide() ; 
	     // 
	     document.form1.email.focus(); 
	     return false;
	   }
	   if (te1 == -1)  {
		 document.getElementById('error_1').innerHTML = '<strong>Error:</strong> Falta el punto en el correo de contacto.';
	     $('#error_1').show()  ; 
	     $('#error_0').hide()  ; 
         $('#mensaje_1').hide() ; 
	     // 
	     document.form1.email.focus(); 
	     return false;
	   }
  }
}
//----------------------------
// email verificación
//----------------------------
function validar_email2() {
  var llargo_email2 = document.form1.email_v.value;
  var largo2 = llargo_email2.length;
  
   if (largo2 == 0){
	 document.getElementById('error_1').innerHTML = '<strong>Error:</strong> Debe ingresar correo de verificaci&oacute;n.';
	 $('#error_1').show()  ; 
     $('#error_0').hide()  ; 
     $('#mensaje_1').hide() ; 
	 // 
	 document.form1.email_v.focus();
	 return false;
  }
  if (largo2 < 6){
	 document.getElementById('error_1').innerHTML = '<strong>Error:</strong> Correo de verificacion NO v&aacute;lido.';
	 $('#error_1').show()  ; 
	 $('#error_0').hide()  ; 
     $('#mensaje_1').hide() ; 
	 //  
	 document.form1.email_v.focus();
	 return false;
  }
  if (document.form1.email_v.value!==''){ 
       var dde2= document.form1.email_v.value;
	   var ge2 = dde2.indexOf("@");
	   var te2 = dde2.indexOf(".");
	   if (ge2 == -1)  {
		 document.getElementById('error_1').innerHTML = '<strong>Error:</strong> Falta ingresar la @ en el correo de verificaci&oacute;n.';
	     $('#error_1').show()  ;
		 $('#error_0').hide()  ;  
         $('#mensaje_1').hide() ; 
	     //  
	     document.form1.email_v.focus(); 
	     return false;
	   }
	   if (te2 == -1)  {
		 document.getElementById('error_1').innerHTML = '<strong>Error: </strong>Falta el punto en el correo de verificaci&oacute;n.';
	     $('#error_1').show()  ; 
	     $('#error_0').hide()  ; 
	     $('#mensaje_1').hide() ; 
	     //  
	     document.form1.email_v.focus(); 
	     return false;
	   }
	   
  }

  if  (document.form1.email.value!= document.form1.email_v.value){
	  document.getElementById('error_1').innerHTML = '<strong>Error:</strong> Correo de verificaci&oacute;n no es igual al de contacto, revise.';
	  $('#error_1').show()  ; 
	  $('#error_0').hide()  ; 
	  $('#mensaje_1').hide() ; 
	  //
	  document.form1.email_v.focus();
	  return false;
 } 

}

//---------------------
// habilita campos
//---------------------
function habilitar_campos() {
   document.form1.email.disabled = false;
   document.form1.email_v.disabled = false;
   document.form1.boton1.disabled = false;
}

function deshabilitar_campos() {
   document.form1.nombre_completo.value = "";
   document.form1.email.disabled = true;
   document.form1.email_v.disabled = true;
   document.form1.boton1.disabled = true;
}

//---------------------
// limpia campos
//---------------------
function limpia_campos() {
   document.form1.nombre_completo.value = "";
   document.form1.email.value = "";
   document.form1.email_v.value = "";
   document.form1.carrera.value = "";
}

//--------------------------
// revisa si es titulado
//--------------------------
 function validaformulario(){ 
   var data = new FormData($('#form1')[0]);
   var url = "valida_titulado.php";
   $.ajax({
        url:url,
        type:'POST',
        contentType:false,
        data:data,
        processData:false,
        cache:false,
         success:  function (response) {
		 if ((response=='1') || (response=='4'))  { 
		   $('#error_0').show()  ; 
		   $('#error_1').hide()  ; 
		   $('#mensaje_1').hide() ;
		   document.form1.nombre_completo.value = "";
		   deshabilitar_campos()
	       $("#rut").focus();
                 return false;
		 }else{
                    $('#error_0').hide()  ; 
                    $('#error_1').hide() ;
		    $('#mensaje_1').hide() ;  
		    $('#nombre_completo').val( response);
               // va a pedir celular si  
              if($("#celular").val() == ""){
                 $("#celular").focus();
                 return false;
              }
		   
	     }
         }
               ,fail:function(response){alert('Error al llamar programa');}
         
        }) ; //fin ajax
   }

//-----------------------------------------
// obtiene carrera del titulado, para desplegar.
//------------------------------------------
 function obtieneCarrera(){ 
   var data = new FormData($('#form1')[0]);
   var url = "obtienecarrera.php";
   $.ajax({
        url:url,
        type:'POST',
        contentType:false,
        data:data,
        processData:false,
        cache:false,
         success:  function (response) {
             
        $('.selector-tit select').html(response).fadeIn()     
        $('#error_1').hide() ;
        $('#error_0').hide()  ; 
        $('#mensaje_1').html(response);
        $('#mensaje_1').show() ;
         }
               ,fail:function(response){alert('Error al llamar programa');}
         
        }) ; //fin ajax
   }   
//------------------------------------------
   
</script>
<body oncopy="return false;">
<div class="container">
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
            
           <!-- ---------------------------------------------PANTALLA-------------------------------------------------------------------------------------- -->
       <!--mtc      <img src="img/alumni.png" alt="Alumni Virginio G&oacute;mez" class="img-responsive center-block" width="300px"> -->
    		<div class="panel panel-default">
			  	<div class="panel-heading">
			    	<h3 class="panel-title">Obtencion de Certificados Titulos en linea</h3>
			 	</div>
			  	<div class="panel-body">
		         
            <form action="resumen.php" method="post" name="form1" id="form1" enctype="multipart/form-data" >
                    <fieldset>
			<div class="form-group">
			    <input class="form-control" placeholder="Rut: 99999999-9" name="rut"  id="rut" value ="" autocomplete="off" type="text"  autofocus 
                            onClick="javascript:display_mensajes();" onFocus="javascript:limpia_campos();"
                            onChange="javascript:validar_rut();" required >
			</div>
                        
                                        <div class="form-group">
			    		    <input class="form-control" placeholder="Nombre Completo" name="nombre_completo" id="nombre_completo" type="text" disabled="true">
			    		</div>
                        <div class="form-group">
			    			<input class="form-control" placeholder="Escribe aqu&iacute; tu correo"  name="email" id="email" type="email" disabled="true" autocomplete="off" minlength="6" maxlength="40" 
                            onChange="javascript:validar_email1();"  
                            onKeyPress="javascript:validar_tecla_email(event);" required>
			    		</div>
                        <div class="form-group">
			    			<input class="form-control" placeholder="Repite aqu&iacute; tu correo" name="email_v" id="email_v" type="email" disabled="true" autocomplete="off" minlength="6" maxlength="40"
                            onChange="javascript:validar_email2();"  
                            onKeyPress="javascript:validar_tecla_email_v(event);" required>
			    		</div>
                        <div class="form-group">
                        
					
                            <div class="selector-tit" placeholder="Titulo">
                                <select name="carrera" id="carrera" class="form-control">
                                 <option disabled selected>Selecciona titulo</option>   
                                </select>
                            </div>
                        </div>                   
	    		<input class="btn btn-lg btn-info btn-block"  name="boton1" id="boton1" type="submit" value="Enviar" disabled="true" onClick="javascript:validar_boton(form1);">
		</fieldset>
                 
            </form>
                    <br><p style=" color:#39F;font-size:10px;  ">(*)El correo es necesario debido que enviara copia del certificado</p>
			    </div>
			</div>
		</div>
	</div>
</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>