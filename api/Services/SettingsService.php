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
			if (isset($data->Name) && isset($data->Subject) && isset($data->Text) && isset($data->CC) ) {
					try {
							 $STH = $DBH->prepare("INSERT INTO " . TableService::getTable(TableEnum::EMAILS) . " (Name, Subject, Text, CC) VALUES (:Name, :Subject, :Text, :CC)");
							 $STH->bindParam(':Name', $data->Name);
							 $STH->bindParam(':Subject', $data->Subject);
							 $STH->bindParam(':Text', $data->Text);
							 $STH->bindParam(':CC', $data->CC);
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
				 if (isset($data->ID) && isset($data->Subject) && isset($data->Text)&& isset($data->CC)) {
						 try {
								 $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::EMAILS) . " SET Subject = :Subject, Text = :Text, CC = :CC WHERE ID=:ID");
								 $STH->bindParam(':Subject', $data->Subject);
								 $STH->bindParam(':Text', $data->Text);
								 $STH->bindParam(':CC', $data->CC);
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

}
