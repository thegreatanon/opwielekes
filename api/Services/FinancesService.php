<?php

require_once(__DIR__ . "/TableService.php");
require_once(__DIR__ . "/TableEnum.php");

class FinancesService
{

	public static function getTransactions() {
		$mysqldateformat = $GLOBALS['mysqldateformat'];
    global $DBH;
		$STH = $DBH->prepare("SELECT f.ID, DATE_FORMAT(f.TransactionDate, '" . $mysqldateformat . "') TransactionDate, f.ParentID, f.KidID, f.Amount, f.Membership, f.MembershipReceived, f.MembershipMethod, m.PaymentMethodName MembershipMethodName,
		f.Caution, f.CautionReceived, f.CautionMethod, c.PaymentMethodName CautionMethodName, p.Name ParentName, p.Surname ParentSurname, p.Street, p.StreetNumber, p.Postal, p.Town, p.Email, p.Phone, k.Name KidName, k.Surname KidSurname, t.Note, t.ActionID, a.Name ActionName
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

	public static function newTransaction($data, $transactionID) {
		global $DBH;
        if (isset($transactionID) && isset($data->TransactionDate) && isset($data->ParentID) && isset($data->KidID) && isset($data->Amount) && isset($data->Membership) && isset($data->MembershipReceived) && isset($data->MembershipMethod) && isset($data->Caution) && isset($data->CautionReceived) && isset($data->CautionMethod)) {
            try {
                $STH = $DBH->prepare("INSERT INTO " . TableService::getTable(TableEnum::FINANCES) . " (TransactionID, TransactionDate, ParentID, KidID, Amount, Membership, MembershipReceived, MembershipMethod, Caution, CautionReceived, CautionMethod) VALUES (:TransactionID, :TransactionDate, :ParentID, :KidID, :Amount, :Membership, :MembershipReceived, :MembershipMethod, :Caution, :CautionReceived, :CautionMethod)");
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
                $STH->execute();
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in nieuwe financiele transactie..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in nieuwe financiele transactie..."];

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

	public static function receiveTransaction($id) {
			global $DBH;
	    if (isset($id)) {
					try {
		        $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::FINANCES) . " SET received = 1 WHERE ID = :ID");
						$STH->bindParam(':ID', $id);
		        $STH->execute();
		      } catch (Exception $e) {
		         return ["status" => -1, "error" => "Er is iets fout gelopen in transaction received..."];
		      }
	    } else {
	       	return ["status" => -1, "error" => "Onvoldoende parameters in transaction received..."];
	    }
  }

	public static function deleteTransaction2($id) {
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

	public static function deleteTransaction($id) {
        global $DBH;
        try {
            $STH = $DBH->prepare("DELETE FROM " . TableService::getTable(TableEnum::FINANCES) . " WHERE ID = :ID");
				$STH->bindParam(':ID', $id);
				$STH->execute();
				return ["status" => 0];
        } catch (Exception $e) {
            return ["status" => -1, "error" => $e];
        }
    }


}
