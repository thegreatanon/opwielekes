<?php

	session_start();
	// LOAD DEPENDENCIES
	include_once(__DIR__ . '/vars.php');
	require_once(__DIR__ . "/api/pdoconnect.php");
	require_once(__DIR__ . "/api/Services/SettingsService.php");
	require_once(__DIR__ . "/api/Services/MembersService.php");
	require_once(__DIR__ . "/api/Services/RenewalsService.php");
	require_once(__DIR__ . "/api/Services/EmailsService.php");

	//phpinfo();

	// Every account has a variable SendReminders that enables these automatic emails that is not available through the interface
	// Reminders1, 2 and 3 can be set via the user interface and will be sent out at according to the settings
	// There is an additional Reminder0, not accessible through the user interface, that reminds all expired people
	// It is intended to be used once at initialisation of the reminders and no more afterwards

	//get mode: test vs Cron
	if( isset($_POST['mode']) ){
		$mode = $_POST['mode'];
		$testmode = true;
		$cronmode = false;
		//$remdate = new DateTime($_POST['testdate']);
		$remdate = new DateTime('today');
		new DateTime('today');
		$testaccount = $_SESSION["account"];
	} else {
		$mode = 'cron';
		$testmode = false;
		$cronmode = true;
		$remdate = new DateTime('today');
		$testaccount = 'all';
	}

	// SETTINGS
	$sendExpired = true;

	// INITIALISE VARIABLES
	if ($testmode) {
		$accounts = [$testaccount];
		$globalreport = 'Test voor automatische hernieuwingsemails <br>';
		$sendaccountreport = true;
		$sendglobalreport = false;
	} else {
		$accounts = SettingsService::getAccounts();
		$globalreport = 'Cron job launched <br>';
		$sendaccountreport = false;
		$sendglobalreport = true;
	}


	// LOOP OVER ALL ACCOUNTS
	foreach ($accounts as $account) {
		//$accountreport = 'Uitvoeringsdatum: ' . $remdate->format('Y-m-d') . PHP_EOL;
		$accountreport = '';
		$settings = SettingsService::getRenewalSettings($account['AccountCode']);
		$testrecipient = $settings['EmailCC'];
		$membershipfees = SettingsService::getMemberships($account['AccountCode']);
		if ($settings['SendReminders'] == "1") {
			// INITIALISE LISTS FOR EACH REMINDER TYPE
			$reminder0 = array();
			$reminder1 = array();
			$reminder2 = array();
			$reminder3 = array();
			// VERIFY WHICH MEMBERSHIPS SATISFY REMINDER CONDITIONS
			$accountreport .= $account['AccountName'] . ': automatische hernieuwingen ingeschakeld ' . PHP_EOL;
			$renewals = MembersService::getAllMembers($account['AccountCode']);
			foreach ($renewals as $renewal) {
				if ($renewal['KidActive'] == "1" && $renewal['KidNr'] > "0" && isValidDate($renewal['KidExpiryDatePHP'])) {
					$interval = intervalValid($renewal['KidExpiryDatePHP'],$remdate);
					if ($interval->invert == '1') {
						$accountreport .= 'Vervallen, ' . $interval->days . ' dagen: ' . $renewal['KidExpiryDatePHP'] . ' ' . $renewal['KidID'] . ' ' . $renewal['KidName'] . ' ' . $renewal['KidSurname'] . PHP_EOL;
					} else {
						$accountreport .= 'Geldig, ' . $interval->days . ' dagen: ' . $renewal['KidExpiryDatePHP'] . ' ' . $renewal['KidID'] . ' ' . $renewal['KidName'] . ' ' . $renewal['KidSurname'] .  PHP_EOL;
					}
			 		if ($interval->invert == $settings['Reminder0AfterExp'] && $interval->days > $settings['Reminder0Days'] ) {
						array_push($reminder0,$renewal);
					}
					if ($interval->invert == $settings['Reminder1AfterExp'] && $interval->days == $settings['Reminder1Days'] ) {
						array_push($reminder1,$renewal);
					}
					if ($interval->invert == $settings['Reminder2AfterExp'] && $interval->days == $settings['Reminder2Days'] ) {
						array_push($reminder2,$renewal);
					}
					if ($interval->invert == $settings['Reminder3AfterExp'] && $interval->days == $settings['Reminder3Days'] ) {
						array_push($reminder3,$renewal);
					}

				}
			}

			// PROCESS THE MEMBERS IN EACH REMINDER TYPE
			if (count($reminder0)>0 && $settings['Reminder0Send']=='1') {
				$accountreport .= '<br>Volgende lidmaatschappen zijn reeds vervallen:<br>';
				remind($reminder0, '0', $account['AccountCode']);
			}
			if (count($reminder1)>0 && $settings['Reminder1Send']=='1') {
				if ($settings['Reminder1AfterExp'] == '1') {
					$accountreport .= '<br>Ontvangen een email wegens al ' . $settings['Reminder1Days'] . ' dagen vervallen:<br>';
				} else {
					$accountreport .= '<br>Ontvangen een email wegens vervallen in ' . $settings['Reminder1Days'] . ' dagen:<br>';
				}
				remind($reminder1, '1', $account['AccountCode']);
			}
			if (count($reminder2)>0 && $settings['Reminder2Send']=='1') {
				if ($settings['Reminder2AfterExp'] == '1') {
					$accountreport .= '<br>Ontvangen een email wegens al ' . $settings['Reminder2Days'] . ' dagen vervallen:<br>';
				} else {
					$accountreport .= '<br>Ontvangen een email wegens vervallen in ' . $settings['Reminder2Days'] . ' dagen:<br>';
				}remind($reminder2, '2', $account['AccountCode']);
			}
			if (count($reminder3)>0 && $settings['Reminder3Send']=='1') {
				if ($settings['Reminder3AfterExp'] == '1') {
					$accountreport .= '<br>Ontvangen een email wegens al ' . $settings['Reminder3Days'] . ' dagen vervallen:<br>';
				} else {
					$accountreport .= '<br>Ontvangen een email wegens vervallen in ' . $settings['Reminder3Days'] . ' dagen:<br>';
				}
				remind($reminder3, '3', $account['AccountCode']);
			}
		} else {
			$accountreport .= $account['AccountName'] . ': automatische hernieuwingen niet ingeschakeld ' . PHP_EOL;
		}
		$accountreport .= '<br>';
		$globalreport .= $accountreport;
		// SEND EMAIL REPORT
		if ($sendaccountreport) {
			$adata = new stdClass();
			$adata->subject = 'Overzicht hernieuwingen op wielekes ' . $account['AccountName'];
			$adata->message = $accountreport;
			if ($testmode) {
				$adata->sendto = [$testrecipient];
			} else {
				$adata->sendto = [$settings['EmailCC']];
			}
			$adata->sendcc = '';
			$adata->replyto = 'admin@opwielekes.be';
			$adata->replytoname = 'Opwielekes webmaster';
			$adata->sendername = 'Opwielekes webmaster';
			$result = EmailsService::sendEmail($adata, $account['AccountName']);
		}
	}

	// SEND GLOBAL REPORT
	if ($sendglobalreport) {
		$rdata = new stdClass();
		$rdata->subject = 'Cron job opwielekes executed';
		$rdata->message = $globalreport;
		$rdata->sendto = ['mdepypere@gmail.com'];
		$rdata->sendcc = '';
		$rdata->replyto = 'mdepypere@gmail.com';
		$rdata->replytoname = 'cron';
		$rdata->sendername = 'sent by cron';
		$result = EmailsService::sendEmail($rdata, $account['AccountName']);
	}


function isValidDate($date) {
	if ($date == '0000-00-00') {
		return false;
	} else {
		return true;
	}
}

function intervalValid($expirydate,$remdate) {
	$expiryDateTime = new DateTime($expirydate);
	$interval = $remdate->diff($expiryDateTime);
	return $interval;
}

function remind($kids, $remindnr, $accountcode) {
	global $cronmode, $remdate, $accountreport, $settings, $testrecipient;
	// LOOP OVER MEMBERS
	foreach($kids as $key=>$kid) {
		//if ($key<48) {
		//if ($key>47) {
			$sendEmail = true;
			$accountreport .= $kid['KidExpiryDatePHP'] . ' ' . $kid['KidID'] . ' ' . $kid['KidName'] . ' ' . $kid['KidSurname'] . ': ' ;
			// GET RENEWALFEE
			$renewalfee = getRenewalFee($kid['KidNr'], $kid['ParentMembershipID']);

			// VERIFY WHETHER AN UNPAID RENEWAL EXISTS
			if ($cronmode) {
				$kidrenewals = RenewalsService::openRenewalsByKiD($kid['KidID'], $accountcode);
				if (empty($kidrenewals)) {
					$generateFinTrans = true;
					$accountreport .= 'fin aangemaakt ' . $renewalfee . '€, ';
				} else {
					$generateFinTrans = false;
					$accountreport .= 'fin bestaat al, ';

				}

				// CREATE THE RENEWAL IF NECESSARY
				if ($generateFinTrans) {
					$data = new stdClass();
					// For financial transaction
					$data->TransactionDate = $remdate->format('Y-m-d');
					$data->ParentID = $kid['ParentID'];
					$data->KidID = $kid['KidID'];
					$data->Amount = $renewalfee;
					$data->Membership = $renewalfee;
					$data->MembershipReceived = 0;
					$data->MembershipMethod = 1;
					$data->Caution = 0.00;
					$data->CautionReceived = 1;
					$data->CautionMethod = 1;
					$data->AutoRenewal = 1;
					// For renewal
					$data->CreationDate = $remdate->format('Y-m-d');
					$result = RenewalsService::newRenewalLog($data,  $accountcode);
					if (!is_null($result)) {
							// something went wrong, could not make transaction
						$sendEmail = false;
						var_dump($result);
					}
				}
			} // if $cronmode

			// SEND THE EMAIL
			if ($sendEmail) {
				$now = new DateTime('now', new DateTimeZone('Europe/Brussels'));
				$subject = $settings['Reminder' . $remindnr . 'Subject'];
				$message = $settings['Reminder' . $remindnr . 'Message'];
				$message = parseEmail($message, $kid, $renewalfee);
				// email
				$sdata = new stdClass();
				$sdata->subject = $subject;
				$sdata->message = $message;
				if ($cronmode) {
					$sdata->sendto = [$kid['ParentEmail']];
					$sdata->sendcc = $settings['EmailCC'];
					$sdata->Recipient = $kid['ParentEmail'];
					$sdata->Template = 'reminder' . $remindnr;
				} else {
					$sdata->sendto = [$testrecipient];
					$sdata->sendcc = '';
					$sdata->Recipient = $testrecipient;
					$sdata->Template = 'test reminder' . $remindnr;
				}
				$sdata->replyto = $settings['EmailReplyTo'];
				$sdata->replytoname = $settings['EmailReplyToName'];
				$sdata->sendername = $settings['SenderName'];
				// log
				$sdata->KidID = $kid['KidID'];
				$sdata->DateTime = $now->format('Y-m-d H:i:s');
				$sdata->Auto = '1';
				$sdata->RenewalFee = $renewalfee;
				$sdata->TransactionID = '0';
				// execute
				$result = EmailsService::newEmail($sdata, $accountcode);
				// something went wrong, could not write log
				if (!is_null($result)) {
					echo $result['error'] . '<br>';
				}
				$accountreport .= 'email verzonden naar ' . 	$sdata->Recipient . '<br>';
			}
		//}
	} // foreach
} // function

// THERE IS AN EQUIVALENT JS FUNCTION THAT SHOULD BE KEPT CONSISTENT
// SEE transactions.js
function getRenewalFee($kidnr, $membershipid) {
	if ($kidnr=='0') {
		return 0;
	}
	global $membershipfees;
	// assuming fees are ordered along id, not very robust
	$fees =  $membershipfees[$membershipid-1];
	if ($kidnr>4) {
		$kidnr = '4';
	}
	return $fees['MembershipK' . $kidnr];
}

function parseEmail($email, $kid, $fee) {
	global $settings;
	$email = str_replace("{{voornaam_ouder}}",$kid['ParentName'],$email);
	$email = str_replace("{{achternaam_ouder}}",$kid['ParentSurname'],$email);
	$email = str_replace("{{voornaam_kind}}",$kid['KidName'],$email);
	$email = str_replace("{{achternaam_kind}}",$kid['KidSurname'],$email);
	$email = str_replace("{{IBAN_depot}}",$settings['DefaultIBAN'],$email);
	$email = str_replace("{{bedrag_lidmaatschap}}",$fee,$email);
	//$email = str_replace("€",'&euro;',$email);
	//$email = str_replace("€",mb_encode_mimeheader('€', 'UTF-8'),$email);
	return $email;
}
//
// function generateReportMail($account, $settings, $expired, $reminder1, $reminder2, $reminder3) {
// 	$message = 'Bericht van op wielekes ' . $account['AccountName'] . '.<br>' ;
// 	if (count($expired)>0) {
// 		$message .= 'Volgende lidmaatschappen zijn reeds vervallen:<br>';
// 		foreach($expired as $renewal) {
// 			$message .= $renewal['KidExpiryDatePHP']  . ' ' . $renewal['KidName'] . ' ' . $renewal['KidSurname'] . PHP_EOL;
// 		}
// 		$message .= '<br>';
// 	}
// 	if (count($reminder1)>0 && $settings['Reminder1Send']=='1') {
// 		$message .= 'Volgende lidmaatschappen vervallen in ' . $settings['Reminder1Days'] . ' dagen:<br>';
// 		foreach($reminder1 as $renewal) {
// 			$message .= $renewal['KidExpiryDatePHP']  . ' ' . $renewal['KidName'] . ' ' . $renewal['KidSurname'] . PHP_EOL;
// 		}
// 		$message .= '<br>';
// 	}
// 	if (count($reminder2)>0 && $settings['Reminder2Send']=='1') {
// 		$message .= 'Volgende lidmaatschappen vervallen in ' . $settings['Reminder2Days'] . ' dagen:<br>';
// 		foreach($reminder2 as $renewal) {
// 			$message .= $renewal['KidExpiryDatePHP']  . ' ' . $renewal['KidName'] . ' ' . $renewal['KidSurname'] . PHP_EOL;
// 		}
// 		$message .= '<br>';
// 	}
// 	if (count($reminder3)>0 && $settings['Reminder3Send']=='1') {
// 		$message .= 'Volgende lidmaatschappen vervallen in ' . $settings['Reminder3Days'] . ' dagen:<br>';
// 		foreach($reminder3 as $renewal) {
// 			$message .= $renewal['KidExpiryDatePHP']  . ' ' . $renewal['KidName'] . ' ' . $renewal['KidSurname'] . PHP_EOL;
// 		}
// 		$message .= '<br>';
// 	}
// 	return $message;
// }
//
// function sendCronEmail($mailvars, $subject, $message) {
// 	$sendto = ['mdepypere@gmail.com'];
// 	$sendcc = '';
// 	$replyto = 'mdepypere@gmail.com';
// 	$replytoname = 'cron';
// 	$sendername = 'sent by cron';
//
// 	require 'libs/phpmailer/5.2.22/PHPMailerAutoload.php';
// 	$mail = new PHPMailer;
//
// 	$mail->SMTPDebug = 3;                               // Enable verbose debug output
//
// 	$mail->isSMTP();     								  // Set mailer to use SMTP
//
// 	$mail->Host = 	$mailvars["mailhost"];						  // Specify main and backup SMTP servers
// 	$mail->Port = $mailvars["mailport"]; 									  // TCP port to connect to
// 	$mail->SMTPAuth = $mailvars["mailsmtpauth"];                               // Enable SMTP authentication
// 	$mail->Username = $mailvars["mailusername"];                // SMTP username
// 	$mail->Password = $mailvars["mailpassword"];                        // SMTP password
// 	$mail->SMTPSecure = $mailvars["mailsmtpecure"];
//
// 	$mail->setFrom($mailvars["mailfromaddress"], $sendername);
//
// 	foreach($sendto as $email){
// 	   $mail->AddAddress($email);
// 	}
//
// 	if (!empty($sendcc)){
// 		//foreach($sendcc as $emailcc){
// 		$mail->AddCC($sendcc);
// 		//}
// 	}
//
// 	$mail->addReplyTo($replyto, $replytoname);
//
// 	$mail->isHTML(true);                                  // Set email format to HTML
//
// 	$mail->Subject = $subject;
// 	$mail->Body    = nl2br($message);
//
// 	if(!$mail->send()) {
// 		echo 'Contact message could not be sent.';
// 		echo 'Mailer Error: ' . $mail->ErrorInfo;
// 		//echo json_encode(['success' => false, 'jsontext' => $mail->ErrorInfo]);
// 	} else {
// 		echo 'Contact message has been sent';
// 	}
// }
//

?>
