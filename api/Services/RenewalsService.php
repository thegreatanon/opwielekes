<?php

require_once(__DIR__ . "/TableService.php");
require_once(__DIR__ . "/TableEnum.php");
require_once(__DIR__ . "/FinancesService.php");

class RenewalsService
{

	public static function getRenewals($accountcode=null) {
		$mysqldateformat = $GLOBALS['mysqldateformat'];
		$phpdateformat = $GLOBALS['phpdateformat'];
    global $DBH;
		$STH = $DBH->prepare("SELECT r.RenwalID, r.KidID, r.FinanceID, DATE_FORMAT(r.CreationDate, '" . $mysqldateformat . "') CreationDate,
		DATE_FORMAT(r.CreationDate, '" . $phpdateformat . "') PHPCreationDate, f.MembershipReceived RenewalPaid
			 FROM " . TableService::getTable(TableEnum::RENEWALS, $accountcode) . " r
			 LEFT JOIN " . TableService::getTable(TableEnum::FINANCES, $accountcode) . " f
			 ON r.FinanceID = f.ID");
		$STH->execute();
		return $STH->fetchAll();
  }

	public static function openRenewalsByKiD($kidID, $accountcode=null) {
		$mysqldateformat = $GLOBALS['mysqldateformat'];
		$phpdateformat = $GLOBALS['phpdateformat'];
		global $DBH;
		$STH = $DBH->prepare("SELECT r.RenewalID, r.KidID, r.FinanceID, DATE_FORMAT(r.CreationDate, '" . $mysqldateformat . "') CreationDate,
		DATE_FORMAT(r.CreationDate, '" . $phpdateformat . "') PHPCreationDate, f.MembershipReceived RenewalPaid
			 FROM " . TableService::getTable(TableEnum::RENEWALS, $accountcode) . " r
			 LEFT JOIN " . TableService::getTable(TableEnum::FINANCES, $accountcode) . " f
			 ON r.FinanceID = f.ID
			 WHERE r.KidID = :KidID AND f.MembershipReceived = '0'");
		$STH->bindParam(':KidID', $kidID);
		$STH->execute();
		return $STH->fetchAll();
	}

	public static function newRenewal($data, $financeid, $accountcode=null) {
		global $DBH;
        if (isset($data->KidID) && isset($financeid) && isset($data->CreationDate) ) {
            try {
                $STH = $DBH->prepare("INSERT INTO " . TableService::getTable(TableEnum::RENEWALS, $accountcode) . "
								(KidID, FinanceID, CreationDate) VALUES (:KidID, :FinanceID, :CreationDate)");
								$STH->bindParam(':KidID', $data->KidID);
								$STH->bindParam(':FinanceID', $financeid);
								$STH->bindParam(':CreationDate', $data->CreationDate);
                $STH->execute();
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in nieuwe hernieuwing..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in nieuwe hernieuwing..."];
        }
	}

	public static function newRenewalLog($data, $accountcode=null) {
		global $DBH;
		try {
			$DBH->beginTransaction();
			$newf = FinancesService::newTransaction($data, 0, $accountcode);
			if ($newf["status"] == -1) {
				throw new Exception($newf["error"]);
			}
			$financeid = $newf["lastid"];
			echo $financeid . '<br>';
			$newr = RenewalsService::newRenewal($data, $financeid, $accountcode);
			if ($newr["status"] == -1) {
				throw new Exception($newr["error"]);
			}
			$DBH->commit();
		} catch (Exception $e) {
			$DBH->rollBack();
		 	return ["status" => -1, "error" =>  $e->getMessage()];
		}
	}

	// TO EDIT!!!
	public static function renewalPaid($data, $accountcode=null) {
		// make a new transaction with the change in expiry date
		// set the transaction ID in finance Table
		global $DBH;
		try {
			$DBH->beginTransaction();
			$newf = FinancesService::newTransaction($data, 0, $accountcode);
			if ($newf["status"] == -1) {
				throw new Exception($newf["error"]);
			}
			$financeid = $newf["lastid"];
			$newr = RenewalsService::newRenewal($data, $financeid, $accountcode);
			if ($newr["status"] == -1) {
				throw new Exception($newr["error"]);
			}
			$DBH->commit();
		} catch (Exception $e) {
			$DBH->rollBack();
			return ["status" => -1, "error" =>  $e->getMessage()];
		}
	}

	// NOT NECESSARY
	// public static function updateRenewal($transactionid, $renewalid, $accountcode) {
	// 		global $DBH;
	// 		if (isset($transactionid)) {
	// 				try {
	// 						$STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::RENEWALS, $accountcode) . " SET TransactionID = :TransactionID WHERE RenewalID=:RenewalID");
	// 						$STH->bindParam(':TransactionID', $transactionid);
	// 						$STH->bindParam(':RenewalID', $renewalid);
	// 						$STH->execute();
	// 						return ["status" => 0];
	// 				} catch (Exception $e) {
	// 						return ["status" => -1, "error" => "Er is iets fout gelopen in update hernieuwing..."];
	// 				}
	// 		} else {
	// 				return ["status" => -1, "error" => "Onvoldoende parameters in update hernieuwing..."];
	// 		}
	// }


}
