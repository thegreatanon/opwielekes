<?php

require_once(__DIR__ . "/TableService.php");
require_once(__DIR__ . "/TableEnum.php");
require_once(__DIR__ . '/../../vars.php');

class EmailsService
{
	public static function newEmailLog($data, $accountcode=null) {
		global $DBH;
        if (isset($data->KidID) && isset($data->Recipient) && isset($data->DateTime) && isset($data->Auto)  && isset($data->RenewalFee)  && isset($data->TransactionID)  && isset($data->Template) ) {
            try {
                $STH = $DBH->prepare("INSERT INTO " . TableService::getTable(TableEnum::EMAILLOGS, $accountcode) . "
								(KidID, Recipient, DateTime, Auto, RenewalFee, TransactionID, Template) VALUES (:KidID, :Recipient, :DateTime, :Auto, :RenewalFee, :TransactionID, :Template)");
								$STH->bindParam(':KidID', $data->KidID);
								$STH->bindParam(':Recipient', $data->Recipient);
								$STH->bindParam(':DateTime', $data->DateTime);
								$STH->bindParam(':Auto', $data->Auto);
								$STH->bindParam(':RenewalFee', $data->RenewalFee);
								$STH->bindParam(':TransactionID', $data->TransactionID);
								$STH->bindParam(':Template', $data->Template);
                $STH->execute();
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in nieuwe emaillog..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in nieuwe emaillog..."];
        }
	}

	public static function sendEmail($data, $accountcode=null) {
		if ( isset($data->sendername) && isset($data->sendto) && isset($data->sendcc) && isset($data->replyto)
			&& isset($data->replytoname) && isset($data->subject) && isset($data->message)) {

			global $mailvars;

			require_once(__DIR__ .'/../../libs/phpmailer/5.2.22/PHPMailerAutoload.php');
			$mail = new PHPMailer;
	  	$mail->CharSet = 'UTF-8';
			$mail->SMTPDebug = 3;                               // Enable verbose debug output
			$mail->isSMTP();     								  // Set mailer to use SMTP
			$mail->Host = $mailvars["mailhost"];						  // Specify main and backup SMTP servers
			$mail->Port = $mailvars["mailport"]; 									  // TCP port to connect to
			$mail->SMTPAuth = $mailvars["mailsmtpauth"];                               // Enable SMTP authentication
			$mail->Username = $mailvars["mailusername"];                // SMTP username
			$mail->Password = $mailvars["mailpassword"];                        // SMTP password
			$mail->SMTPSecure = $mailvars["mailsmtpecure"];

			//$mail->Sender = 'admin@opwielekes.be'; //$mailvars["mailfromaddress"]; // for bounces
			$mail->setFrom($mailvars["mailfromaddress"], $data->sendername);

			foreach($data->sendto as $email){
				 $mail->AddAddress($email);
			}
			if (!empty($data->sendcc)){
				//foreach($sendcc as $emailcc){
				$mail->AddCC($data->sendcc);
				//}
			}
			$mail->addReplyTo($data->replyto, $data->replytoname);
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = $data->subject;
			$mail->Body    = nl2br($data->message);

			if(!$mail->send()) {
				return ["status" => -1, "error" =>  $mail->ErrorInfo];
				//echo 'Contact message could not be sent.';
				//echo 'Mailer Error: ' . $mail->ErrorInfo;
				//echo json_encode(['success' => false, 'jsontext' => $mail->ErrorInfo]);
			}
		} else {
			return ["status" => -1, "error" => "Onvoldoende parameters in verzend email..."];
		}
	}

	public static function newEmail($data, $accountcode=null) {
		$sentlog = EmailsService::sendEmail($data, $accountcode);
		if (is_null($sentlog)) {
			//echo 'email was sent <br>';
			$sentsuccess = true;
		} else {
			echo 'Mailer Error: ' . $sentlog->error . '<br>';
			$sentsuccess = false;
			return $sentlog;
		}
		if ($sentsuccess) {
			global $DBH;
			try {
				$DBH->beginTransaction();
				$newl = EmailsService::newEmailLog($data, $accountcode);
				if ($newl["status"] == -1) {
					throw new Exception($newl["error"]);
				}
				$DBH->commit();
			} catch (Exception $e) {
				$DBH->rollBack();
				return ["status" => -1, "error" =>  $e->getMessage()];
			}
		}
	}

}
