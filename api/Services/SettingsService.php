<?php

require_once(__DIR__ . "/TableService.php");
require_once(__DIR__ . "/TableEnum.php");

class SettingsService
{

	public static function updatePrices($data) {

		global $DBH;
        if (isset($data->ID) && isset($data->Kid1) && isset($data->Kid2) && isset($data->Kid3) && isset($data->Kid4)) {
			try {
                $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::PRICES) . " SET Kid1 = :Kid1, Kid2 = :Kid2, Kid3 = :Kid3, Kid4 = :Kid4 WHERE ID = :ID");
				$STH->bindParam(':ID', $data->ID);
				$STH->bindParam(':Kid1', $data->Kid1);
				$STH->bindParam(':Kid2', $data->Kid2);
				$STH->bindParam(':Kid3', $data->Kid3);
				$STH->bindParam(':Kid4', $data->Kid4);
                $STH->execute();
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in update prices data..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in update prices data..."];

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
								 return ["status" => -1, "error" => "Er is iets fout gelopen in update email..."];
						 }
				 } else {
						 return ["status" => -1, "error" => "Onvoldoende parameters in update email..."];
				 }
		 }


}
