<?php

require_once(__DIR__ . "/TableService.php");
require_once(__DIR__ . "/TableEnum.php");

class MembersService
{

	public static function updateParentData($data) {

		global $DBH;
        if (isset($data->ID) && isset($data->Name) && isset($data->Surname) && isset($data->Street) && isset($data->StreetNumber) && isset($data->Postal) && isset($data->Town) && isset($data->Email) && isset($data->Phone) && isset($data->InitDate) && isset($data->MembershipID) && isset($data->Notes) ) {
						try {
	              $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::PARENTS) . " SET Name = :Name, Surname = :Surname, Street = :Street, StreetNumber = :StreetNumber, Postal = :Postal, Town = :Town, Email = :Email, Phone = :Phone, InitDate = :InitDate, MembershipID = :MembershipID, Notes = :Notes WHERE ID = :ID");
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
								$STH->bindParam(':MembershipID', $data->MembershipID);
								$STH->bindParam(':Notes', $data->Notes);
                $STH->execute();
            } catch (Exception $e) {
               	return ["status" => -1, "error" => "Er is iets fout gelopen in update ouder data..."];
            }
        } else {
           	return ["status" => -1, "error" => "Onvoldoende parameters in update ouder data..."];
        }

    }

	public static function updateParentCaution($data) {
		global $DBH;
        if (isset($data->ID) && isset($data->CautionAmount)) {
					try {
				        $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::PARENTS) . " SET CautionAmount = :CautionAmount WHERE ID = :ID");
								$STH->bindParam(':ID', $data->ID);
								$STH->bindParam(':CautionAmount', $data->CautionAmount);
				        $STH->execute();
				    } catch (Exception $e) {
				       return ["status" => -1, "error" => "Er is iets fout gelopen in update ouder waarborg..."];
				    }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in update ouder waarborg..."];

        }
    }

	public static function newParent($data) {
			global $DBH;
        if (isset($data->Name) && isset($data->Surname) && isset($data->Street) && isset($data->StreetNumber) && isset($data->Postal) && isset($data->Town) && isset($data->Email) && isset($data->Phone) && isset($data->InitDate) &&
				isset($data->CautionAmount) && isset($data->MembershipID) && isset($data->Notes) ) {
            try {
                $STH = $DBH->prepare("INSERT INTO " . TableService::getTable(TableEnum::PARENTS) . " (Name, Surname, Street, StreetNumber, Postal, Town, Email, Phone, InitDate, CautionAmount, MembershipID, Notes) VALUES (:Name, :Surname, :Street, :StreetNumber, :Postal, :Town, :Email, :Phone, :InitDate, :CautionAmount, :MembershipID, :Notes)");
								$STH->bindParam(':Name', $data->Name);
								$STH->bindParam(':Surname', $data->Surname);
								$STH->bindParam(':Street', $data->Street);
								$STH->bindParam(':StreetNumber', $data->StreetNumber);
								$STH->bindParam(':Postal', $data->Postal);
								$STH->bindParam(':Town', $data->Town);
								$STH->bindParam(':Email', $data->Email);
								$STH->bindParam(':Phone', $data->Phone);
								$STH->bindParam(':InitDate', $data->InitDate);
								$STH->bindParam(':CautionAmount', $data->CautionAmount);
								$STH->bindParam(':MembershipID', $data->MembershipID);
								$STH->bindParam(':Notes', $data->Notes);
				        $STH->execute();
								$last_id = $DBH->lastInsertId();
								return ["status" => 0, "lastid" => $last_id];
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
        if (isset($parentID) && isset($data->Name) && isset($data->Surname) &&  isset($data->BirthDate) && isset($data->Caution) && isset($data->ExpiryDate) && isset($data->Active) && isset($data->BikeID) && isset($data->KidNr)) {
            try {
                $STH = $DBH->prepare("INSERT INTO " . TableService::getTable(TableEnum::KIDS) . " (ParentID, Name, Surname, BirthDate, Caution, ExpiryDate, Active, BikeID, KidNr) VALUES (:ParentID, :Name, :Surname, :BirthDate, :Caution, :ExpiryDate, :Active, :BikeID, :KidNr)");
				$STH->bindParam(':ParentID', $parentID);
				$STH->bindParam(':Name', $data->Name);
				$STH->bindParam(':Surname', $data->Surname);
				$STH->bindParam(':BirthDate', $data->BirthDate);
				$STH->bindParam(':Caution', $data->Caution);
				$STH->bindParam(':ExpiryDate', $data->ExpiryDate);
				$STH->bindParam(':Active', $data->Active);
				$STH->bindParam(':BikeID', $data->BikeID);
				$STH->bindParam(':KidNr', $data->KidNr);
        $STH->execute();
				$last_id = $DBH->lastInsertId();
				return ["status" => 0, "lastid" => $last_id];
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in nieuw kind..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in nieuw kind..."];

        }
	}

	public static function updateKidStatus($data) {

		global $DBH;
        if (isset($data->ID) && isset($data->Active) && isset($data->BikeID) && isset($data->KidNr) && isset($data->ExpiryDate)) {
			try {
                $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::KIDS) . " SET Active = :Active, BikeID = :BikeID, KidNr = :KidNr, ExpiryDate = :ExpiryDate WHERE ID = :ID");
				$STH->bindParam(':ID', $data->ID);
				$STH->bindParam(':Active', $data->Active);
				$STH->bindParam(':BikeID', $data->BikeID);
				$STH->bindParam(':KidNr', $data->KidNr);
				$STH->bindParam(':ExpiryDate', $data->ExpiryDate);
                $STH->execute();
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in update kind status..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in update kind status..."];
        }
	}

	public static function updateKidExpiryDate($data) {

		global $DBH;
        if (isset($data->ID) && isset($data->ExpiryDate) ) {
			try {
                $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::KIDS) . " SET ExpiryDate = :ExpiryDate WHERE ID = :ID");
				$STH->bindParam(':ID', $data->ID);
				$STH->bindParam(':ExpiryDate', $data->ExpiryDate);
                $STH->execute();
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in update kind vervaldag..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in update kind vervaldag..."];
        }
	}
	public static function updateKidFinances($data) {

		global $DBH;
        if (isset($data->ID) && isset($data->CautionPresent) && isset($data->CautionAmount) && isset($data->ExpiryDate)) {
			try {
                $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::KIDS) . " SET CautionPresent = :CautionPresent, CautionAmount = :CautionAmount, ExpiryDate = :ExpiryDate WHERE ID = :ID");
				$STH->bindParam(':ID', $data->ID);
				$STH->bindParam(':CautionPresent', $data->CautionPresent);
				$STH->bindParam(':CautionAmount', $data->CautionAmount);
				$STH->bindParam(':ExpiryDate', $data->ExpiryDate);
                $STH->execute();
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in update kind finances..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in update kind finances..."];
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

	public static function deleteKid($id) {
        global $DBH;
        try {
            $STH = $DBH->prepare("DELETE FROM " . TableService::getTable(TableEnum::KIDS) . " WHERE ID=:id ");
    				$STH->bindParam(':id', $id);
            $STH->execute();
            return ["status" => 0];
        } catch (Exception $e) {
            return ["status" => -1, "error" => $e];
        }
    }

	public static function getKids() {
			$mysqldateformat = $GLOBALS['mysqldateformat'];
      global $DBH;
			$STH = $DBH->prepare("SELECT ID, ParentID, Name, Surname, DATE_FORMAT(BirthDate, '" . $mysqldateformat . "') BirthDate, Caution, DATE_FORMAT(ExpiryDate, '" . $mysqldateformat . "') ExpiryDate, Active, BikeID, KidNr FROM " . TableService::getTable(TableEnum::KIDS) );
			$STH->execute();
			return $STH->fetchAll();
    }

	/*
	// DOES NOT WORK, MISSING FIELDS FOR KIDS WITH USED PARENTID
	public static function getJoinedMembers2() {
        global $DBH;
		$STH = $DBH->prepare("SELECT k.ID KidID, k.Name KidName, k.Surname KidSurname, k.BirthDate KidBirthDate, k.CautionPresent KidCautionPresent, k.CautionAmount KidCautionAmount, k.ExpiryDate KidExpiryDate, k.Active KidActive, k.BikeID KidBikeID, k.KidNr KidNr, p.ID ParentID, p.Name ParentName, p.Surname ParentSurname, p.InitDate ParentInitDate, p.CautionAmount ParentCautionAmount, COUNT(k.ParentID) ParentNrKids, SUM(CASE WHEN k.Active THEN 1 ELSE 0 END) ParentActiveKids, (SELECT SUM(CASE WHEN t.Action = 'Donatie' THEN 1 ELSE 0 END) FROM " . TableService::getTable(TableEnum::TRANSACTIONS) . " t WHERE t.ParentID = p.ID GROUP BY t.ParentID) ParentDonations
			FROM " . TableService::getTable(TableEnum::KIDS) . " k
			LEFT JOIN " . TableService::getTable(TableEnum::PARENTS) . " p
			ON k.ParentID = p.ID
			GROUP BY ParentID");
		$STH->execute();
		return $STH->fetchAll();

    }
	*/

	public static function logRegistration($data, $parentID) {
		global $DBH;
        if (isset($parentID) && isset($data->Datetime) && isset($data->SignPhrase) &&  isset($data->Phrase)
					&& isset($data->SignRules) && isset($data->RulesDoc) ) {
            try {
                $STH = $DBH->prepare("INSERT INTO " . TableService::getTable(TableEnum::REGISTRATIONS) . " (ParentID, Datetime, SignPhrase, Phrase,	SignRules, RulesDoc) VALUES (:ParentID, :Datetime, :SignPhrase, :Phrase, :SignRules, :RulesDoc)");
								$STH->bindParam(':ParentID', $parentID);
								$STH->bindParam(':Datetime', $data->Datetime);
								$STH->bindParam(':SignPhrase', $data->SignPhrase);
								$STH->bindParam(':Phrase', $data->Phrase);
								$STH->bindParam(':SignRules', $data->SignRules);
								$STH->bindParam(':RulesDoc', $data->RulesDoc);
				        $STH->execute();
								return ["status" => 0];
				            } catch (Exception $e) {
				               return ["status" => -1, "error" => "Er is iets fout gelopen in nieuw log registratie..."];
				            }
		        } else {
		           return ["status" => -1, "error" => "Onvoldoende parameters in nieuw log registratie..."];

		        }
	}

	public static function getJoinedMembers() {
		$mysqldateformat = $GLOBALS['mysqldateformat'];
        global $DBH;
		$STH = $DBH->prepare("SELECT k.ID KidID, k.Name KidName, k.Surname KidSurname, DATE_FORMAT(k.BirthDate, '" . $mysqldateformat . "') KidBirthDate, DATE_FORMAT(k.ExpiryDate, '" . $mysqldateformat . "') KidExpiryDate, k.Active KidActive, k.BikeID KidBikeID, k.KidNr KidNr, p.ID ParentID, p.Name ParentName, p.Surname ParentSurname,
		DATE_FORMAT(p.InitDate, '" . $mysqldateformat . "') ParentInitDate, p.CautionAmount ParentCautionAmount, p.MembershipID ParentMembershipID, m.MembershipName ParentMembershipName
			FROM " . TableService::getTable(TableEnum::KIDS) . " k
			LEFT JOIN " . TableService::getTable(TableEnum::PARENTS) . " p
			ON k.ParentID = p.ID
			LEFT JOIN " . TableService::getTable(TableEnum::MEMBERSHIPS) . " m
			ON p.MembershipID = m.ID");
		$STH->execute();
		return $STH->fetchAll();

  }

	public static function getParents() {
        global $DBH;
				$mysqldateformat = $GLOBALS['mysqldateformat'];
		$STH = $DBH->prepare("SELECT p.ID, p.Name, p.Surname, p.Street, p.StreetNumber, p.Postal, p.Town, p.Email, p.Phone, DATE_FORMAT(p.InitDate, '" . $mysqldateformat . "') InitDate, p.CautionAmount, p.Notes, COUNT(k.ParentID) NrKids, SUM(CASE WHEN k.Active THEN 1 ELSE 0 END) ActiveKids, (SELECT SUM(CASE WHEN t.ActionID = '4' THEN 1 ELSE 0 END) FROM " . TableService::getTable(TableEnum::TRANSACTIONS) . " t WHERE t.ParentID = p.ID GROUP BY t.ParentID) Donations, p.MembershipID, m.MembershipName
			FROM " . TableService::getTable(TableEnum::PARENTS) . " p
			LEFT JOIN " . TableService::getTable(TableEnum::KIDS) . " k
			ON p.ID = k.ParentID
			LEFT JOIN " . TableService::getTable(TableEnum::MEMBERSHIPS) . " m
			ON p.MembershipID = m.ID
			GROUP BY ID
			ORDER BY Name");
		$STH->execute();
		return $STH->fetchAll();

    }


}
