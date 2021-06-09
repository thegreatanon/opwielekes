<?php

require_once(__DIR__ . "/TableService.php");
require_once(__DIR__ . "/TableEnum.php");

class TransactionsService
{

	public static function newTransaction($data) {
		global $DBH;
        if ( isset($data->KidID) && isset($data->ParentID) && isset($data->ActionID) && isset($data->BikeOutID) && isset($data->BikeInID) && isset($data->BikeDonatedID) && isset($data->MembershipID) && isset($data->ExpiryDate) && isset($data->Date) && isset($data->Note) ) {
            try {
                $STH = $DBH->prepare("INSERT INTO " . TableService::getTable(TableEnum::TRANSACTIONS) . " (KidID, ParentID, ActionID, BikeOutID, BikeInID, BikeDonatedID, MembershipID, ExpiryDate, Date, Note) VALUES (:KidID, :ParentID, :ActionID, :BikeOutID, :BikeInID, :BikeDonatedID, :MembershipID, :ExpiryDate, :Date, :Note)");

								$STH->bindParam(':KidID', $data->KidID);
								$STH->bindParam(':ParentID', $data->ParentID);
								$STH->bindParam(':ActionID', $data->ActionID);
								$STH->bindParam(':BikeOutID', $data->BikeOutID);
								$STH->bindParam(':BikeInID', $data->BikeInID);
								$STH->bindParam(':BikeDonatedID', $data->BikeDonatedID);
								$STH->bindParam(':MembershipID', $data->MembershipID);
								$STH->bindParam(':ExpiryDate', $data->ExpiryDate);
								$STH->bindParam(':Date', $data->Date);
								$STH->bindParam(':Note', $data->Note);
                $STH->execute();
								$last_id = $DBH->lastInsertId();
								return ["status" => 0, "lastid" => $last_id];
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in nieuwe transactie..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in nieuwe transaction..."];

        }
	}

	public static function getTransactions() {
			$mysqldateformat = $GLOBALS['mysqldateformat'];
			global $DBH;
			$STH = $DBH->prepare("SELECT ID,	KidID,	ParentID,	ActionID,	BikeOutID,	BikeInID,	 DATE_FORMAT(Date, '" . $mysqldateformat . "') Date FROM " . TableService::getTable(TableEnum::TRANSACTIONS) . " ORDER BY Date");
			$STH->execute();
			return $STH->fetchAll();
	}

}
