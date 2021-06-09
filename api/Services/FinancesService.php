<?php

require_once(__DIR__ . "/TableService.php");
require_once(__DIR__ . "/TableEnum.php");
require_once(__DIR__ . "/FinancesService.php");
require_once(__DIR__ . "/MembersService.php");
require_once(__DIR__ . "/TransactionsService.php");

class FinancesService
{

	public static function getTransactions() {
		$mysqldateformat = $GLOBALS['mysqldateformat'];
    global $DBH;
		$STH = $DBH->prepare("SELECT f.ID, DATE_FORMAT(f.TransactionDate, '" . $mysqldateformat . "') TransactionDate,
		f.ParentID, f.KidID, f.Amount, f.Membership, f.MembershipReceived, f.MembershipMethod, f.TransactionID,
		m.PaymentMethodName MembershipMethodName, f.Caution, f.CautionReceived, f.CautionMethod, c.PaymentMethodName CautionMethodName,
		p.Name ParentName, p.Surname ParentSurname, p.Street, p.StreetNumber, p.Postal, p.Town, p.Email, p.Phone,
		k.Name KidName, k.Surname KidSurname, t.Note, t.ActionID, a.Name ActionName, f.AutoRenewal
			FROM " . TableService::getTable(TableEnum::FINANCES) . " f
			LEFT JOIN " . TableService::getTable(TableEnum::PARENTS) . " p
			ON f.ParentID = p.ID
			LEFT JOIN " . TableService::getTable(TableEnum::KIDS) . " k
			ON f.KidID = k.ID
			LEFT JOIN " . TableService::getTable(TableEnum::PAYMENTMETHODS) . " m
			ON f.MembershipMethod = m.PaymentMethodID
			LEFT JOIN " . TableService::getTable(TableEnum::PAYMENTMETHODS) . " c
			ON f.CautionMethod = c.PaymentMethodID
			LEFT JOIN " . TableService::getTable(TableEnum::TRANSACTIONS) . " t
			ON f.TransactionID = t.ID
			LEFT JOIN " . TableService::getTable(TableEnum::ACTIONS) . " a
			ON t.ActionID = a.ID
			ORDER BY f.TransactionDate");
		$STH->execute();
		return $STH->fetchAll();

  }

	public static function getTransactionByID($id) {
		$mysqldateformat = $GLOBALS['mysqldateformat'];
		global $DBH;
		$STH = $DBH->prepare("SELECT f.ID, DATE_FORMAT(f.TransactionDate, '" . $mysqldateformat . "') TransactionDate,
		f.ParentID, f.KidID, f.Amount, f.Membership, f.MembershipReceived, f.MembershipMethod, f.TransactionID,
		m.PaymentMethodName MembershipMethodName, f.Caution, f.CautionReceived, f.CautionMethod, c.PaymentMethodName CautionMethodName,
		p.Name ParentName, p.Surname ParentSurname, p.Street, p.StreetNumber, p.Postal, p.Town, p.Email, p.Phone,
		k.Name KidName, k.Surname KidSurname, f.AutoRenewal
			FROM " . TableService::getTable(TableEnum::FINANCES) . " f
			LEFT JOIN " . TableService::getTable(TableEnum::PARENTS) . " p
			ON f.ParentID = p.ID
			LEFT JOIN " . TableService::getTable(TableEnum::KIDS) . " k
			ON f.KidID = k.ID
			LEFT JOIN " . TableService::getTable(TableEnum::PAYMENTMETHODS) . " m
			ON f.MembershipMethod = m.PaymentMethodID
			LEFT JOIN " . TableService::getTable(TableEnum::PAYMENTMETHODS) . " c
			ON f.CautionMethod = c.PaymentMethodID
			WHERE f.ID = :ID");
		$STH->bindParam(':ID', $id);
		$STH->execute();
		return $STH->fetch();

	}

	public static function newTransaction($data, $transactionID, $accountcode=null) {
		global $DBH;
        if (isset($transactionID) && isset($data->TransactionDate) && isset($data->ParentID) && isset($data->KidID) && isset($data->Amount) && isset($data->Membership) && isset($data->MembershipReceived) && isset($data->MembershipMethod) && isset($data->Caution) && isset($data->CautionReceived) && isset($data->CautionMethod) && isset($data->AutoRenewal)) {
            try {
                $STH = $DBH->prepare("INSERT INTO " . TableService::getTable(TableEnum::FINANCES, $accountcode) . " (TransactionID, TransactionDate, ParentID, KidID, Amount, Membership, MembershipReceived, MembershipMethod, Caution, CautionReceived, CautionMethod, AutoRenewal) VALUES (:TransactionID, :TransactionDate, :ParentID, :KidID, :Amount, :Membership, :MembershipReceived, :MembershipMethod, :Caution, :CautionReceived, :CautionMethod, :AutoRenewal)");
								$STH->bindParam(':TransactionID', $transactionID);
								$STH->bindParam(':TransactionDate', $data->TransactionDate);
								$STH->bindParam(':ParentID', $data->ParentID);
								$STH->bindParam(':KidID', $data->KidID);
								$STH->bindParam(':Amount', $data->Amount);
								$STH->bindParam(':Membership', $data->Membership);
								$STH->bindParam(':MembershipReceived', $data->MembershipReceived);
								$STH->bindParam(':MembershipMethod', $data->MembershipMethod);
								$STH->bindParam(':Caution', $data->Caution);
								$STH->bindParam(':CautionReceived', $data->CautionReceived);
								$STH->bindParam(':CautionMethod', $data->CautionMethod);
								$STH->bindParam(':AutoRenewal', $data->AutoRenewal);
                $STH->execute();
								$last_id = $DBH->lastInsertId();
								return ["status" => 0, "lastid" => $last_id];
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in nieuwe financiele transactie..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in nieuwe financiele transactie..."];

        }
	}


	public static function processPayment($id, $data) {
		if (isset($id)  && isset($data->MembershipReceived) && isset($data->CautionReceived) && isset($data->TransactionDate) ) {
			global $DBH;
			try {
			   $fintrans = FinancesService::getTransactionByID($id);
				 $kid = MembersService::getKidByID($fintrans['KidID']);
				 $DBH->beginTransaction();
					// if this was an automatic renewal of which the money was received
				 if ($fintrans['AutoRenewal'] == '1' && $fintrans['TransactionID'] == '0' && $fintrans['MembershipReceived'] =='0' && $data->MembershipReceived == '1' ) {
			 	   // prolong epxiry
					 $expirydate = new DateTime($kid['PHPExpiryDate']);
					 $expirydate->modify('+1 year');
					 $updke = MembersService::updateKidExpiryDate($kid['ID'], $expirydate->format('Y-m-d'));
					 if ($updke["status"] == -1) {
						 throw new Exception($updke["error"]);
					 }
					 // make a new transaction
					 $transdate = new DateTime('today');
					 $tdata = new stdClass();
					 $tdata->KidID = $kid['ID'];
					 $tdata->ParentID = $kid['ParentID'];
					 $tdata->ActionID = "5";
					 $tdata->BikeOutID = "0";
					 $tdata->BikeInID = "0";
					 $tdata->BikeDonatedID = "0";
					 $tdata->MembershipID = $kid['MembershipID'];
					 $tdata->ExpiryDate = $expirydate->format('Y-m-d');
					 $tdata->Date = $transdate->format('Y-m-d');
					 $tdata->Note = "";
					 $newt = TransactionsService::newTransaction($tdata);
					 if ($newt["status"] == -1) {
						 throw new Exception($newt["error"]);
					 }
					 $transid = $newt["lastid"];
					 // add this transaction to the finance id
					 $updft = FinancesService::updateTransactionID($id, $transid, $transdate->format('Y-m-d'));
					 if ($updft["status"] == -1) {
						 throw new Exception($updft["error"]);
					 }
				 }
				 // update financial transaction
				 $updf = FinancesService::updateTransaction($id, $data);
				 if ($updf["status"] == -1) {
					 throw new Exception($updf["error"]);
				 }
			 	 $DBH->commit();
			 } catch (Exception $e) {
				 $DBH->rollBack();
				 return ["status" => -1, "error" =>  $e->getMessage()];
			 }
		} else {
				return ["status" => -1, "error" => "Onvoldoende parameters in verwerk financiele transactie..."];
		}
	}

	// in a financial transaction, set the action transaction date and id
	public static function updateTransactionID($id, $transID, $transDate) {
			global $DBH;
			if (isset($id) && isset($transID) && isset($transDate)) {
					try {
							$STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::FINANCES) . " SET TransactionID = :TransactionID, TransactionDate = :TransactionDate WHERE ID=:ID");
							$STH->bindParam(':TransactionID', $transID);
							$STH->bindParam(':TransactionDate', $transDate);
							$STH->bindParam(':ID', $id);
							$STH->execute();
							return ["status" => 0];
					} catch (Exception $e) {
							return ["status" => -1, "error" => "Er is iets fout gelopen in update financieel transactieID..."];
					}
			} else {
					return ["status" => -1, "error" => "Onvoldoende parameters in update financieel transactieID..."];
			}
	}

	public static function updateTransaction($id, $data) {
			global $DBH;
			if (isset($data->TransactionDate) && isset($data->MembershipReceived) && isset($data->CautionReceived) && isset($id)) {
					try {
							$STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::FINANCES) . " SET TransactionDate = :TransactionDate, MembershipReceived = :MembershipReceived, CautionReceived = :CautionReceived WHERE ID=:ID");
							$STH->bindParam(':TransactionDate', $data->TransactionDate);
							$STH->bindParam(':MembershipReceived', $data->MembershipReceived);
							$STH->bindParam(':CautionReceived', $data->CautionReceived);
							$STH->bindParam(':ID', $id);
							$STH->execute();
							return ["status" => 0];
					} catch (Exception $e) {
							return ["status" => -1, "error" => "Er is iets fout gelopen in update financiele transactie..."];
					}
			} else {
					return ["status" => -1, "error" => "Onvoldoende parameters in update financiele transactie..."];
			}
	}

	public static function deleteTransaction($id) {
    global $DBH;
		if (isset($id)) {
			try {
				$STH = $DBH->prepare("DELETE FROM " . TableService::getTable(TableEnum::FINANCES) . " WHERE ID = :ID");
				$STH->bindParam(':ID', $id);
				$STH->execute();
				return ["status" => 0];
			} catch (Exception $e) {
				return ["status" => -1, "error" => "Er is iets fout gelopen in DELETE transaction..."];
			}
		} else {
           return ["status" => -1, "error" => "Onvoldoende parameters in delete transaction..."];
        }
    }

}
