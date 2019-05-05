<?php

require_once(__DIR__ . "/TableService.php");
require_once(__DIR__ . "/TableEnum.php");

class MembersService
{
	
	public static function updateParentData($data) {
		
		global $DBH;
        if (isset($data->ID) && isset($data->Name) && isset($data->Surname) && isset($data->Street) && isset($data->StreetNumber) && isset($data->Postal) && isset($data->Town) && isset($data->Email) && isset($data->Phone) && isset($data->InitDate)) {
			try {
                $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::PARENTS) . " SET Name = :Name, Surname = :Surname, Street = :Street, StreetNumber = :StreetNumber, Postal = :Postal, Town = :Town, Email = :Email, Phone = :Phone, InitDate = :InitDate WHERE ID = :ID");
				$STH->bindParam(':ID', $data->ID);
				$STH->bindParam(':Name', $data->Name);
				$STH->bindParam(':Surname', $data->Surname);
				$STH->bindParam(':Street', $data->Street);
				$STH->bindParam(':StreetNumber', $data->StreetNumber);
				$STH->bindParam(':Postal', $data->Postal);
				$STH->bindParam(':Town', $data->Town);
				$STH->bindParam(':Email', $data->Email);
				$STH->bindParam(':Phone', $data->Phone);
				$STH->bindParam(':InitDate', $data->InitDate);
                $STH->execute();
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in update ouder data..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in update ouder data..."];
        
        }
		
    }
	
	public static function updateParentStatus($data) {
		
		global $DBH;
        if (isset($data->ID) && isset($data->Active)) {
			try {
                $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::PARENTS) . " SET Active = :Active WHERE ID = :ID");
				$STH->bindParam(':ID', $data->ID);
				$STH->bindParam(':Active', $data->Active);
                $STH->execute();
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in update ouder status..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in update ouder status..."];
        
        }
		
    }

	public static function newParent($data) {
		global $DBH;
        if (isset($data->Name) && isset($data->Surname) && isset($data->Street) && isset($data->StreetNumber) && isset($data->Postal) && isset($data->Town) && isset($data->Email) && isset($data->Phone) && isset($data->InitDate) && isset($data->Active)) {
            try {
                $STH = $DBH->prepare("INSERT INTO " . TableService::getTable(TableEnum::PARENTS) . " (Name, Surname, Street, StreetNumber, Postal, Town, Email, Phone, InitDate, Active) VALUES (:Name, :Surname, :Street, :StreetNumber, :Postal, :Town, :Email, :Phone, :InitDate, :Active)");
				$STH->bindParam(':Name', $data->Name);
				$STH->bindParam(':Surname', $data->Surname);
				$STH->bindParam(':Street', $data->Street);
				$STH->bindParam(':StreetNumber', $data->StreetNumber);
				$STH->bindParam(':Postal', $data->Postal);
				$STH->bindParam(':Town', $data->Town);
				$STH->bindParam(':Email', $data->Email);
				$STH->bindParam(':Phone', $data->Phone);
				$STH->bindParam(':InitDate', $data->InitDate);
				$STH->bindParam(':Active', $data->Active);
                $STH->execute();
				$last_id = $DBH->lastInsertId();
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in nieuwe ouder..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in nieuwe ouder..."];
        
        }
	}
	
	public static function deleteParent($id) {
        global $DBH;
        try {
            $STH = $DBH->prepare("DELETE FROM " . TableService::getTable(TableEnum::PARENTS) . " WHERE ID=:id ");
    		$STH->bindParam(':id', $id);
            $STH->execute();
            return ["status" => 0];
        } catch (Exception $e) {
            return ["status" => -1, "error" => $e];
        }
    }
	
	public static function newKid($data,$parentID) {
		global $DBH;
        if (isset($parentID) && isset($data->Name) && isset($data->Surname) &&  isset($data->BirthDate) && isset($data->Caution) && isset($data->ExpiryDate) && isset($data->Active) && isset($data->BikeID)) {
            try {
                $STH = $DBH->prepare("INSERT INTO " . TableService::getTable(TableEnum::KIDS) . " (ParentID, Name, Surname, BirthDate, Caution, ExpiryDate, Active, BikeID) VALUES (:ParentID, :Name, :Surname, :BirthDate, :Caution, :ExpiryDate, :Active, :BikeID)");
				$STH->bindParam(':ParentID', $parentID);
				$STH->bindParam(':Name', $data->Name);
				$STH->bindParam(':Surname', $data->Surname);
				$STH->bindParam(':BirthDate', $data->BirthDate);
				$STH->bindParam(':Caution', $data->Caution);
				$STH->bindParam(':ExpiryDate', $data->ExpiryDate);
				$STH->bindParam(':Active', $data->Active);
				$STH->bindParam(':BikeID', $data->BikeID);
                $STH->execute();
				$last_id = $DBH->lastInsertId();
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in nieuw kind..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in nieuw kind..."];
        
        }
	}
	
	public static function updateKidStatus($data) {
		
		global $DBH;
        if (isset($data->ID) && isset($data->Caution) && isset($data->ExpiryDate) && isset($data->Active) && isset($data->BikeID)) {
			try {
                $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::KIDS) . " SET Caution = :Caution, ExpiryDate = :ExpiryDate, Active = :Active, BikeID = :BikeID WHERE ID = :ID");
				$STH->bindParam(':ID', $data->ID);
				$STH->bindParam(':Caution', $data->Caution);
				$STH->bindParam(':ExpiryDate', $data->ExpiryDate);
				$STH->bindParam(':Active', $data->Active);
				$STH->bindParam(':BikeID', $data->BikeID);
                $STH->execute();
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in update kind status..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in update kind status..."];
        }
	}
		
	public static function updateKidData($data) {
		
		global $DBH;
        if (isset($data->ID) && isset($data->Name) && isset($data->Surname) &&  isset($data->BirthDate)) {
			try {
                $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::KIDS) . " SET Name = :Name, Surname = :Surname, BirthDate = :BirthDate WHERE ID = :ID");
				$STH->bindParam(':ID', $data->ID);
				$STH->bindParam(':Name', $data->Name);
				$STH->bindParam(':Surname', $data->Surname);
				$STH->bindParam(':BirthDate', $data->BirthDate);
                $STH->execute();
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in update kind..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in update kind..."];
        }
	}
	
	public static function getJoinedMembers() {
        global $DBH;
		$STH = $DBH->prepare("SELECT k.ID KidID, k.Name KidName, k.Surname KidSurname, k.BirthDate KidBirthDate, k.Caution KidCaution, k.ExpiryDate KidExpiryDate, k.Active KidActive, k.BikeID KidBikeID, p.ID ParentID, p.Name ParentName, p.Surname ParentSurname, p.InitDate ParentInitDate, p.Active ParentActive 
			FROM " . TableService::getTable(TableEnum::KIDS) . " k 
			LEFT JOIN " . TableService::getTable(TableEnum::PARENTS) . " p 
			ON k.ParentID = p.ID");
		$STH->execute();
		return $STH->fetchAll();

    }
}

