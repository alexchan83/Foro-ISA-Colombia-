<?php 
/* 	
If you see this text in your browser, PHP is not configured correctly on this webhost. 
Contact your hosting provider regarding PHP configuration for your site.
*/

require_once('form_throttle.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	if (formthrottle_too_many_submissions($_SERVER["REMOTE_ADDR"]))
	{
		echo '{"MusePHPFormResponse": { "success": false,"error": "Too many recent submissions from this IP"}}';
	} 
	else 
	{
		emailFormSubmission();
	}
} 

function emailFormSubmission()
{
	$to = 'asistente@isacolombia.org';
	$subject = 'Envío de Formulario Contacto';
	
	$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/><title>' . htmlentities($subject,ENT_COMPAT,'UTF-8') . '</title></head>';
	$message .= '<body style="background-color: #ffffff; color: #000000; font-style: normal; font-variant: normal; font-weight: normal; font-size: 12px; line-height: 18px; font-family: helvetica, arial, verdana, sans-serif;">';
	$message .= '<h2 style="background-color: #eeeeee;">Envío de nuevo formulario</h2><table cellspacing="0" cellpadding="0" width="100%" style="background-color: #ffffff;">'; 
	$message .= '<tr><td valign="top" style="background-color: #ffffff;"><b>Nombre:</b></td><td>' . htmlentities($_REQUEST["custom_U1245"],ENT_COMPAT,'UTF-8') . '</td></tr>';
	$message .= '<tr><td valign="top" style="background-color: #ffffff;"><b>Correo electrónico:</b></td><td>' . htmlentities($_REQUEST["Email"],ENT_COMPAT,'UTF-8') . '</td></tr>';
	$message .= '<tr><td valign="top" style="background-color: #ffffff;"><b>Mensaje:</b></td><td>' . htmlentities($_REQUEST["custom_U1240"],ENT_COMPAT,'UTF-8') . '</td></tr>';
	$message .= '<tr><td valign="top" style="background-color: #ffffff;"><b>Celular:</b></td><td>' . htmlentities($_REQUEST["custom_U1256"],ENT_COMPAT,'UTF-8') . '</td></tr>';
	$message .= '<tr><td valign="top" style="background-color: #ffffff;"><b>Teléfono oficina:</b></td><td>' . htmlentities($_REQUEST["custom_U1262"],ENT_COMPAT,'UTF-8') . '</td></tr>';
	$message .= '<tr><td valign="top" style="background-color: #ffffff;"><b>Empresa:</b></td><td>' . htmlentities($_REQUEST["custom_U1268"],ENT_COMPAT,'UTF-8') . '</td></tr>';

	$message .= '</table><br/><br/>';
	$message .= '<div style="background-color: #eeeeee; font-size: 10px; line-height: 11px;">Formulario enviado desde el sitio web: ' . htmlentities($_SERVER["SERVER_NAME"],ENT_COMPAT,'UTF-8') . '</div>';
	$message .= '<div style="background-color: #eeeeee; font-size: 10px; line-height: 11px;">Dirección IP del visitante: ' . htmlentities($_SERVER["REMOTE_ADDR"],ENT_COMPAT,'UTF-8') . '</div>';
	$message .= '</body></html>';
	$message = cleanupMessage($message);
	
	$formEmail = cleanupEmail($_REQUEST['Email']);
	$headers = 'From:  asistente@isacolombia.org' . "\r\n" . 'Reply-To: ' . $formEmail .  "\r\n" .'X-Mailer: Adobe Muse 7.2.232 with PHP/' . phpversion() . "\r\n" . 'Content-type: text/html; charset=utf-8' . "\r\n";
	
	$sent = @mail($to, $subject, $message, $headers);
	
	if($sent)
	{
		echo '{"FormResponse": { "success": true}}';

	}
	else
	{
		echo '{"MusePHPFormResponse": { "success": false,"error": "Failed to send email"}}';
	}
}

function cleanupEmail($email)
{
	$email = htmlentities($email,ENT_COMPAT,'UTF-8');
	$email = preg_replace('=((<CR>|<LF>|0x0A/%0A|0x0D/%0D|\\n|\\r)\S).*=i', null, $email);
	return $email;
}

function cleanupMessage($message)
{
	$message = wordwrap($message, 70, "\r\n");
	return $message;
}
?>
