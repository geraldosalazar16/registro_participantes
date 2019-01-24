<?php

include $_SERVER["DOCUMENT_ROOT"] . "/imnc/jwt_imnc/common/sendgridlib/sendgrid-php.php";

$SENDGRID_API_KEY = '<sendgrid api key>';

//$sendgrid = new SendGrid(trim($SENDGRID_API_KEY), array('raise_exceptions' => false));
$sendgrid = new SendGrid(trim($SENDGRID_API_KEY));
$email = new SendGrid\Email();

class SendMail 
{
	
	function __construct()
	{
	}

	function send($enviar_a, $mensaje){
		$SENDGRID_API_KEY = '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';
		$sendgrid = new SendGrid(trim($SENDGRID_API_KEY));
		$email = new SendGrid\Email();

		$email->addTo($enviar_a);
		$email->setFrom('acruz@imnc.org.mx');
		$email->setHtml($mensaje);
		$sendgrid->send($email);
	}
}


class MailError 
{
	
	function __construct()
	{
	}

	function send($proyecto, $ruta_a_script, $dberror, $query, $enviar_a){
		$SENDGRID_API_KEY = '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';
		$sendgrid = new SendGrid(trim($SENDGRID_API_KEY));
		$email = new SendGrid\Email();

		$mensaje = "<br> En la ruta " . $ruta_a_script . "<br><br>";
		$mensaje .= $dberror . "<br><br>";
		$mensaje .= $query;

		$email->addTo($enviar_a);
		$email->setFrom('acruz@imnc.org.mx');
		$email->setSubject('Error en proyecto ' . $proyecto);
		$email->setText('ERROR');
		$email->setHtml($mensaje);
		$sendgrid->send($email);
	}
}

$mailerror = new MailError();

//Enviar correo en texto plano o HTML
/*
$email->addTo("polo.ruiz.theone@gmail.com");
$email->setFrom('norepla@example.mx');
$email->setSubject('Asunto');
$email->setText('Texto plano');
$email->setHtml('<strong>Texto en HTML<strong>');
$sendgrid->send($email);
*/


//Enviar correo desde un template
/*
$TEMPLATE_ID = '<id del template>'
$email->addTo("polo.ruiz.theone@gmail.com");
$email->setFrom('norepla@example.mx');
$email->setSubject('Asunto');
$email->setText('Texto plano');
$email->setHtml('<strong>Texto en HTML<strong>');
$email->setTemplateId($TEMPLATE_ID);
$sendgrid->send($email);
*/

?>