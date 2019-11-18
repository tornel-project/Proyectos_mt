<?php
require("funciones_mysql.inc.php");
require("funciones_oracle.inc.php");
require_once("funciones_intranet.inc.php");
//require_once("funciones_intranet_pautas.inc.php");
//require_once("funciones_intranet_informes_gestion.inc.php");
//require_once("funciones_sistares.inc.php");
//require_once("funciones_foro.inc.php");
//require_once("Mail.php");

function enviar_correo($para, $de, $asunto, $mensaje) {
	$de = str_replace(array("�", "�", "�", "�", "�"), array("a", "e", "i", "o", "u"), $de);
	if (strlen(strstr($de, "<")) > 0) {
		$de = "\"" . substr($de, '<', strpos($de, '<')) . "\" " . strstr($de, "<");
	}
	//$de = htmlentities($de);
	$mail = Mail::factory("smtp", array("host" => "192.168.2.2"));
	$r = $mail->send($para, array("From" => $de, "Subject" => $asunto, "Content-type" => "text/html"), nl2br($mensaje));
	if ($r !== true) {
		$mail->send("intranet@virginiogomez.cl", array("From" => "intranet@virginiogomez.cl", "Subject" => "Error enviar_correo()", "Content-type" => "text/html"), nl2br(var_export($r, true)));
	}
	return $r;
}

function enviar_correo_exa($para, $fromname1, $de,$asunto, $mensaje) {
 jimport('joomla.mail.helper');
 $mailer   =& JFactory::getMailer();

 $sender   =$de;
 $fromname =$fromname1;

 $mailer->addRecipient($para);
 $mailer->setSender(array($sender,utf8_encode($fromname)));
 $mailer->addReplyTo(array($sender,utf8_encode($fromname)));
 $mailer->setSubject(utf8_encode($asunto));
 $mailer->setBody(utf8_encode($mensaje));
 $mailer->IsHTML(false);
 $rs = $mailer->Send();
 if ($rs !== true) {
  $sender   ="intranet@virginiogomez.cl";
  $fromname ="Administrador Web";

 $mailer->addRecipient("intranet@virginiogomez.cl");
 $mailer->setSender(array($sender,utf8_encode($fromname)));
 $mailer->addReplyTo(array($sender,utf8_encode($fromname)));
 $mailer->setSubject(utf8_encode($asunto));
 $mailer->setBody(utf8_encode($mensaje));
 $mailer->IsHTML(true);
 $mailer->Send();
 }
return $rs;
}

function encripta($texto) {
    $largo_texto = strlen($texto);
    $texto_encriptado = "";
    $clave_encriptacion = rand(0, 255);
    $relleno = rand(0, 5);

    if ($largo_texto > 0) {
	$texto_encriptado = $texto_encriptado . sprintf("%02x", $clave_encriptacion);
	$texto_encriptado = $texto_encriptado . sprintf("%02x", $relleno ^ $clave_encriptacion);
	$texto_encriptado = $texto_encriptado . sprintf("%02x", $largo_texto ^ $clave_encriptacion);
	for ($i = 0; $i < $largo_texto; $i++) {
	    $texto_encriptado = $texto_encriptado . sprintf("%02x", ord($texto[$i]) ^ $clave_encriptacion);
	}
	for ($i = 0; $i < $relleno; $i++) {
	    $texto_encriptado = $texto_encriptado . sprintf("%02x", rand(0, 255));
	}
    }
    return $texto_encriptado;
}

function IPVG_MENSAJE($mensaje) {
?>
<div id="mensajedeaviso"><?php echo $mensaje?></div>
<?php
}
?>
