<?php

require_once(__DIR__ . "/TableService.php");
require_once(__DIR__ . "/TableEnum.php");

class BikeService
{
	
	public static function updateBike($data) {
		
		global $DBH;
        if (isset($data->ID) && isset($data->Number) && isset($data->Name) && isset($data->Status) && isset($data->Frame) && isset($data->Wheel) && isset($data->Source) && isset($data->InitDate) ) {
			try {
                $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::BIKES) . " SET Number = :Number, Name = :Name, Frame = :Frame, Wheel = :Wheel, InitDate = :InitDate, Status = :Status, Source = :Source  WHERE ID = :ID");
				$STH->bindParam(':ID', $data->ID);
				$STH->bindParam(':Number', $data->Number);
				$STH->bindParam(':Name', $data->Name);
				$STH->bindParam(':Frame', $data->Frame);
				$STH->bindParam(':Wheel', $data->Wheel);
				$STH->bindParam(':InitDate', $data->InitDate);
				$STH->bindParam(':Status', $data->Status);
				$STH->bindParam(':Source', $data->Source);
                $STH->execute();
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in update fiets..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in update fiets..."];
        
        }
		
    }

	public static function newBike($data) {
		global $DBH;
        if (isset($data->Number) && isset($data->Name) && isset($data->Status) && isset($data->Frame) && isset($data->Wheel) && isset($data->Source) && isset($data->InitDate) ) {
            try {
                $STH = $DBH->prepare("INSERT INTO " . TableService::getTable(TableEnum::BIKES) . " (Number, Name, Frame, Wheel, InitDate, Status, Source) VALUES (:Number, :Name, :Frame, :Wheel, :InitDate, :Status, :Source)");
				$STH->bindParam(':Number', $data->Number);
				$STH->bindParam(':Name', $data->Name);
				$STH->bindParam(':Frame', $data->Frame);
				$STH->bindParam(':Wheel', $data->Wheel);
				$STH->bindParam(':InitDate', $data->InitDate);
				$STH->bindParam(':Status', $data->Status);
				$STH->bindParam(':Source', $data->Source);
                $STH->execute();
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in nieuwe fiets..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in nieuwe fiets..."];
        
        }
	}
	
	public static function deleteBike($id) {
        global $DBH;
        try {
            $STH = $DBH->prepare("DELETE FROM " . TableService::getTable(TableEnum::BIKES) . " WHERE ID=:id ");
    		$STH->bindParam(':id', $id);
            $STH->execute();
            return ["status" => 0];
        } catch (Exception $e) {
            return ["status" => -1, "error" => $e];
        }
    }
}

