<?php

require_once(__DIR__ . "/TableService.php");
require_once(__DIR__ . "/TableEnum.php");

class TransactionsService
{
	
	public static function newTransaction($data) {
		global $DBH;
        if (isset($data->KidID) && isset($data->Action) && isset($data->BikeOutID) && isset($data->BikeInID) && isset($data->Date) ) {
            try {
                $STH = $DBH->prepare("INSERT INTO " . TableService::getTable(TableEnum::TRANSACTIONS) . " (KidID, Action, BikeOutID, BikeInID, Date) VALUES (:KidID, :Action, :BikeOutID, :BikeInID, :Date)");
				$STH->bindParam(':KidID', $data->KidID);
				$STH->bindParam(':Action', $data->Action);
				$STH->bindParam(':BikeOutID', $data->BikeOutID);
				$STH->bindParam(':BikeInID', $data->BikeInID);
				$STH->bindParam(':Date', $data->Date);
                $STH->execute();
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in nieuwe transactie..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in nieuwe transaction..."];
        
        }
	}

}

