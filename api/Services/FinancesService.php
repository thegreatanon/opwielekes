<?php

require_once(__DIR__ . "/TableService.php");
require_once(__DIR__ . "/TableEnum.php");

class FinancesService
{

	public static function getTransactions() {
        global $DBH;
		$STH = $DBH->prepare("SELECT f.ID, f.TransactionDate, f.ParentID, f.KidID, f.Amount, f.Membership, f.Caution, f.Received, p.Name ParentName, p.Surname ParentSurname, k.Name KidName, k.Surname KidSurname
			FROM " . TableService::getTable(TableEnum::FINANCES) . " f 
			LEFT JOIN " . TableService::getTable(TableEnum::PARENTS) . " p 
			ON f.ParentID = p.ID 
			LEFT JOIN " . TableService::getTable(TableEnum::KIDS) . " k 
			ON f.KidID = k.ID
			ORDER BY f.Received");
		$STH->execute();
		return $STH->fetchAll();

    }
	
	public static function newTransaction($data) {
		global $DBH;
        if (isset($data->TransactionDate) && isset($data->ParentID) && isset($data->KidID) && isset($data->Amount) && isset($data->Membership) && isset($data->Caution) && isset($data->Received)) {
            try {
                $STH = $DBH->prepare("INSERT INTO " . TableService::getTable(TableEnum::FINANCES) . " (TransactionDate, ParentID, KidID, Amount, Membership, Caution, Received) VALUES (:TransactionDate, :ParentID, :KidID, :Amount, :Membership, :Caution, :Received)");
				$STH->bindParam(':TransactionDate', $data->TransactionDate);
				$STH->bindParam(':ParentID', $data->ParentID);
				$STH->bindParam(':KidID', $data->KidID);
				$STH->bindParam(':Amount', $data->Amount);
				$STH->bindParam(':Membership', $data->Membership);
				$STH->bindParam(':Caution', $data->Caution);
				$STH->bindParam(':Received', $data->Received);
                $STH->execute();
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in nieuwe financiele transactie..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in nieuwe financiele transactie..."];
        
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

