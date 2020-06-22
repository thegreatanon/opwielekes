<?php


	include(__DIR__ . '/vars.php');


	require_once(__DIR__ . "/api/pdoconnect.php");
	require_once(__DIR__ . "/api/Services/SettingsService.php");
	$accounts = SettingsService::getAccounts();

	$message = 'this is a test' . PHP_EOL;
	foreach ($accounts as $account) {
		$message .=  $account['AccountCode'] . PHP_EOL;
		if ($account['AccountCode'] == "dem") {
			$message .=  'this is the one' . PHP_EOL;

		}

	}

	$sendto = ['mdepypere@gmail.com'];
	$sendcc = '';
	$replyto = 'mdepypere@gmail.com';
	$replytoname = 'cron';
	$subject = 'Automatic test email';
	$sendername = 'sent by cron';

	require 'libs/phpmailer/5.2.22/PHPMailerAutoload.php';
	$mail = new PHPMailer;

	$mail->SMTPDebug = 3;                               // Enable verbose debug output

	$mail->isSMTP();     								  // Set mailer to use SMTP

	$mail->Host = $mailhost;						  // Specify main and backup SMTP servers
	$mail->Port = $mailport; 									  // TCP port to connect to
	$mail->SMTPAuth = $mailsmtpauth;                               // Enable SMTP authentication
	$mail->Username = $mailusername;                // SMTP username
	$mail->Password = $mailpassword;                        // SMTP password
	$mail->SMTPSecure = $mailsmtpecure;

	$mail->setFrom($mailfromaddress, $sendername);

	foreach($sendto as $email){
	   $mail->AddAddress($email);
	}

	if (!empty($sendcc)){
		//foreach($sendcc as $emailcc){
		$mail->AddCC($sendcc);
		//}
	}

	$mail->addReplyTo($replyto, $replytoname);

	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = $subject;
	$mail->Body    = nl2br($message);

	if(!$mail->send()) {
		echo 'Contact message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
		//echo json_encode(['success' => false, 'jsontext' => $mail->ErrorInfo]);
	} else {
		echo 'Contact message has been sent';
	}

?>
