<?php
if($_POST){

	$sendto = $_POST['sendto'];
	$replyto = $_POST['replyto'];
	$replytoname = $_POST['replytoname'];
	$subject = $_POST['subject'];
	$message = $_POST['message'];

	require 'libs/phpmailer/5.2.22/PHPMailerAutoload.php';
	$mail = new PHPMailer;

	$mail->SMTPDebug = 3;                               // Enable verbose debug output

	$mail->isSMTP();     								  // Set mailer to use SMTP


	$mail->setFrom('maarten@bewustverbruiken.be', 'Opwielekes Webmaster');

	foreach($sendto as $email){
	   $mail->AddAddress($email);
	}

//	$mail->addAddress($sendto);     // Add a recipient
	$mail->addReplyTo($replyto, $replytoname);

	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = $subject;
	$mail->Body    = nl2br($message);

	if(!$mail->send()) {
		echo 'Contact message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		echo 'Contact message has been sent';
	}
}
?>
