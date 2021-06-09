<?php
if($_POST){

	include(__DIR__ . '/vars.php');

	$sendto = $_POST['sendto'];
	$sendcc = $_POST['sendcc'];
	$replyto = $_POST['replyto'];
	$replytoname = $_POST['replytoname'];
	$subject = $_POST['subject'];
	$message = $_POST['message'];
	$sendername = $_POST['sendername'];

	require 'libs/phpmailer/5.2.22/PHPMailerAutoload.php';
	$mail = new PHPMailer;
  $mail->CharSet = 'UTF-8';
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
		echo json_encode(['success' => false, 'jsontext' => $mail->ErrorInfo]);
	} else {
		echo 'Contact message has been sent';
	}
}
?>
