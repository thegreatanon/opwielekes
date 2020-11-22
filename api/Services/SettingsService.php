<?php

require_once(__DIR__ . "/TableService.php");
require_once(__DIR__ . "/TableEnum.php");

class SettingsService
{

	public static function updateMemberships($data) {

		global $DBH;
        if (isset($data->ID) && isset($data->MembershipName) && isset($data->YearsValid) && isset($data->MonthsValid) && isset($data->DaysValid) && isset($data->MembershipK1) && isset($data->MembershipK2) && isset($data->MembershipK3) && isset($data->MembershipK4) && isset($data->CautionK1) && isset($data->CautionK2) && isset($data->CautionK3) && isset($data->CautionK4)) {
						try {
				        $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::MEMBERSHIPS) . " SET MembershipName = :MembershipName, YearsValid = :YearsValid, MonthsValid = :MonthsValid, DaysValid = :DaysValid, MembershipK1 = :MembershipK1, MembershipK2 = :MembershipK2, MembershipK3 = :MembershipK3, MembershipK4 = :MembershipK4, CautionK1 = :CautionK1, CautionK2 = :CautionK2, CautionK3 = :CautionK3, CautionK4 = :CautionK4 WHERE ID = :ID");
								$STH->bindParam(':ID', $data->ID);
								$STH->bindParam(':MembershipName', $data->MembershipName);
								$STH->bindParam(':YearsValid', $data->YearsValid);
								$STH->bindParam(':MonthsValid', $data->MonthsValid);
								$STH->bindParam(':DaysValid', $data->DaysValid);
								$STH->bindParam(':MembershipK1', $data->MembershipK1);
								$STH->bindParam(':MembershipK2', $data->MembershipK2);
								$STH->bindParam(':MembershipK3', $data->MembershipK3);
								$STH->bindParam(':MembershipK4', $data->MembershipK4);
								$STH->bindParam(':CautionK1', $data->CautionK1);
								$STH->bindParam(':CautionK2', $data->CautionK2);
								$STH->bindParam(':CautionK3', $data->CautionK3);
								$STH->bindParam(':CautionK4', $data->CautionK4);
                $STH->execute();
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in update memberships data..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in update memberships data..."];

        }

    }

		public static function updatePaymentMethods($data) {
					global $DBH;
	        if (isset($data->PaymentMethodID) && isset($data->PaymentMethodName) && isset($data->PaymentMethodActive) && isset($data->PaymentMethodImmediate) && isset($data->PaymentMethodDonation) ) {
							try {
					        $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::PAYMENTMETHODS) . " SET PaymentMethodName = :PaymentMethodName, PaymentMethodActive = :PaymentMethodActive, PaymentMethodImmediate = :PaymentMethodImmediate, PaymentMethodDonation = :PaymentMethodDonation WHERE PaymentMethodID = :PaymentMethodID");
									$STH->bindParam(':PaymentMethodID', $data->PaymentMethodID);
									$STH->bindParam(':PaymentMethodName', $data->PaymentMethodName);
									$STH->bindParam(':PaymentMethodActive', $data->PaymentMethodActive);
									$STH->bindParam(':PaymentMethodImmediate', $data->PaymentMethodImmediate);
									$STH->bindParam(':PaymentMethodDonation', $data->PaymentMethodDonation);
	                $STH->execute();
	            } catch (Exception $e) {
	               return ["status" => -1, "error" => "Er is iets fout gelopen in update paymentmethods data..."];
	            }
	        } else {
	           return ["status" => -1, "error" => "Onvoldoende parameters in update paymentmethods data..."];

	        }
	    }

			public static function updateDefaultPaymentInfo($data) {
					global $DBH;
					if (isset($data->DefaultPaymentMethod) && isset($data->DefaultIBAN)) {
							try {
									$STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::PREFERENCES) . " SET DefaultPaymentMethod = :DefaultPaymentMethod, DefaultIBAN = :DefaultIBAN WHERE ID=1");
									$STH->bindParam(':DefaultPaymentMethod', $data->DefaultPaymentMethod);
									$STH->bindParam(':DefaultIBAN', $data->DefaultIBAN);
									$STH->execute();
									return ["status" => 0];
							} catch (Exception $e) {
									return ["status" => -1, "error" => "Er is iets fout gelopen in update default payment..."];
							}
					} else {
							return ["status" => -1, "error" => "Onvoldoende parameters in update default payment..."];
					}
			}

		public static function getAccounts() {
			global $DBH;
			$STH = $DBH->query("SELECT * FROM " . 'accounts');
			return $STH->fetchAll();
		}

		public static function deleteEmail($id) {
	        global $DBH;
	        try {
	            $STH = $DBH->prepare("DELETE FROM " . TableService::getTable(TableEnum::EMAILS) . " WHERE ID = :ID");
					$STH->bindParam(':ID', $id);
					$STH->execute();
					return ["status" => 0];
	        } catch (Exception $e) {
	            return ["status" => -1, "error" => $e];
	        }
	   }

		 public static function newEmail($data) {
			 global $DBH;
			if (isset($data->Name) && isset($data->Subject) && isset($data->Text) ) {
					try {
							 $STH = $DBH->prepare("INSERT INTO " . TableService::getTable(TableEnum::EMAILS) . " (Name, Subject, Text) VALUES (:Name, :Subject, :Text)");
							 $STH->bindParam(':Name', $data->Name);
							 $STH->bindParam(':Subject', $data->Subject);
							 $STH->bindParam(':Text', $data->Text);
							 $STH->execute();
							 $last_id = $DBH->lastInsertId();
							 return ["status" => 0, "lastid" => $last_id];
						} catch (Exception $e) {
							 return ["status" => -1, "error" => "Er is iets fout gelopen in nieuwe email..."];
						}
			} else {
				 return ["status" => -1, "error" => "Onvoldoende parameters in nieuwe email..."];
			}
		 }

		 public static function updateEmail($data) {
				 global $DBH;
				 if (isset($data->ID) && isset($data->Subject) && isset($data->Text)) {
						 try {
								 $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::EMAILS) . " SET Subject = :Subject, Text = :Text WHERE ID=:ID");
								 $STH->bindParam(':Subject', $data->Subject);
								 $STH->bindParam(':Text', $data->Text);
								 $STH->bindParam(':ID', $data->ID);
								 $STH->execute();
								 return ["status" => 0];
						 } catch (Exception $e) {
								 return ["status" => -1, "error" => "Er is iets fout gelopen in update email template..."];
						 }
				 } else {
						 return ["status" => -1, "error" => "Onvoldoende parameters in update email template..."];
				 }
		 }

		 public static function updateEmailSettings($data) {
				 global $DBH;
				 if (isset($data->Action) && isset($data->Send) && isset($data->Template)) {
						 try {
								 $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::ACTIONS) . " SET EmailSend = :EmailSend, EmailID = :EmailID WHERE ID=:ID");
								 $STH->bindParam(':EmailSend', $data->Send);
								 $STH->bindParam(':EmailID', $data->Template);
								 $STH->bindParam(':ID', $data->Action);
								 $STH->execute();
								 return ["status" => 0];
						 } catch (Exception $e) {
								 return ["status" => -1, "error" => "Er is iets fout gelopen in update email settings..."];
						 }
				 } else {
						 return ["status" => -1, "error" => "Onvoldoende parameters in update email settings..."];
				 }
		 }

		 public static function updateBikeStatuses($data) {
				 global $DBH;
				 if (isset($data->ID) && isset($data->Name) && isset($data->Available)&& isset($data->Active)) {
						 try {
								 $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::BIKESTATUS) . " SET Name = :Name, Available = :Available, Active = :Active WHERE ID=:ID");
								 $STH->bindParam(':Name', $data->Name);
								 $STH->bindParam(':Available', $data->Available);
								 $STH->bindParam(':Active', $data->Active);
								 $STH->bindParam(':ID', $data->ID);
								 $STH->execute();
								 return ["status" => 0];
						 } catch (Exception $e) {
								 return ["status" => -1, "error" => "Er is iets fout gelopen in update bike status..."];
						 }
				 } else {
						 return ["status" => -1, "error" => "Onvoldoende parameters in update bike status..."];
				 }
		 }


	  public static function getPreferences() {
		 		global $DBH;
		    $STH = $DBH->query("SELECT * FROM " . TableService::getTable(TableEnum::PREFERENCES));
		 		return $STH->fetch();
 		}

		 public static function updateEmailPreferences($data) {
				 global $DBH;
				 if (isset($data->replytoemail) && isset($data->replytoname) && isset($data->ccemail) && isset($data->sendername)) {
						 try {
								 $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::PREFERENCES) . " SET EmailReplyTo = :EmailReplyTo, EmailReplyToName = :EmailReplyToName, EmailCC = :EmailCC, SenderName = :SenderName WHERE ID=1");
								 $STH->bindParam(':EmailReplyTo', $data->replytoemail);
								 $STH->bindParam(':EmailReplyToName', $data->replytoname);
								 $STH->bindParam(':EmailCC', $data->ccemail);
								 $STH->bindParam(':SenderName', $data->sendername);
								 $STH->execute();
								 return ["status" => 0];
						 } catch (Exception $e) {
								 return ["status" => -1, "error" => "Er is iets fout gelopen in update email preferences reply to..."];
						 }
				 } else {
						 return ["status" => -1, "error" => "Onvoldoende parameters in update email preferences replyto..."];
				 }
		 }

		 public static function updateEmailReminders($data) {
				 global $DBH;
				 if ( isset($data->signupsend) && isset($data->signupsubject) && isset($data->signupmessage) &&
					 		isset($data->reminder1send) && isset($data->reminder1days) && isset($data->reminder1subject) && isset($data->reminder1message) &&
			 		  	isset($data->reminder2send) && isset($data->reminder2days) && isset($data->reminder2subject) && isset($data->reminder2message) &&
				    	isset($data->reminder3send) && isset($data->reminder3days) && isset($data->reminder3subject) && isset($data->reminder3message)  ) {
						 try {
								 $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::PREFERENCES) . " SET SignupSend=:signupsend, SignupSubject=:signupsubject, SignupMessage=:signupmessage, Reminder1Send=:reminder1send, Reminder1Days=:reminder1days, Reminder1Subject=:reminder1subject, Reminder1Message=:reminder1message, Reminder2Send=:reminder2send, Reminder2Days=:reminder2days, Reminder2Subject=:reminder2subject, Reminder2Message=:reminder2message, Reminder3Send=:reminder3send, Reminder3Days=:reminder3days, Reminder3Subject=:reminder3subject, Reminder3Message=:reminder3message WHERE ID=1");
								 $STH->bindParam(':signupsend', $data->signupsend);
								 $STH->bindParam(':signupsubject', $data->signupsubject);
								 $STH->bindParam(':signupmessage', $data->signupmessage);
								 $STH->bindParam(':reminder1send', $data->reminder1send);
								 $STH->bindParam(':reminder1days', $data->reminder1days);
								 $STH->bindParam(':reminder1subject', $data->reminder1subject);
								 $STH->bindParam(':reminder1message', $data->reminder1message);
								 $STH->bindParam(':reminder2send', $data->reminder2send);
								 $STH->bindParam(':reminder2days', $data->reminder2days);
								 $STH->bindParam(':reminder2subject', $data->reminder2subject);
								 $STH->bindParam(':reminder2message', $data->reminder2message);
								 $STH->bindParam(':reminder3send', $data->reminder3send);
								 $STH->bindParam(':reminder3days', $data->reminder3days);
								 $STH->bindParam(':reminder3subject', $data->reminder3subject);
								 $STH->bindParam(':reminder3message', $data->reminder3message);
								 $STH->execute();
								 return ["status" => 0];
						 } catch (Exception $e) {
								 return ["status" => -1, "error" => "Er is iets fout gelopen in update email reminders..."];
						 }
				 } else {
						 return ["status" => -1, "error" => "Onvoldoende parameters in update email reminders..."];
				 }
		 }


}
