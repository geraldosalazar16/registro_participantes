<?php

include $_SERVER["DOCUMENT_ROOT"] . "/certificando-web/backend/common/sendgridlib/sendgrid-php.php";


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
		$email->setFrom('jesus.popocatl@dhttecno.com');
		$email->setHtml($mensaje);
		$sendgrid->send($email);
	}
}

$mail = new SendMail();
//$mail->send($email, $mensaje);
?>