<?php

require_once(__DIR__ . "/TableService.php");
require_once(__DIR__ . "/TableEnum.php");

class MembersService
{

	public static function updateParentData($data) {

		global $DBH;
        if (isset($data->ID) && isset($data->Name) && isset($data->Surname) && isset($data->Street) && isset($data->StreetNumber) && isset($data->Postal) && isset($data->Town) && isset($data->Email) && isset($data->Phone)  && isset($data->IBAN) && isset($data->InitDate) && isset($data->MembershipID) && isset($data->Notes) ) {
						try {
	              $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::PARENTS) . " SET Name = :Name, Surname = :Surname, Street = :Street, StreetNumber = :StreetNumber, Postal = :Postal, Town = :Town, Email = :Email, Phone = :Phone, IBAN = :IBAN, InitDate = :InitDate, MembershipID = :MembershipID, Notes = :Notes WHERE ID = :ID");
								$STH->bindParam(':ID', $data->ID);
								$STH->bindParam(':Name', $data->Name);
								$STH->bindParam(':Surname', $data->Surname);
								$STH->bindParam(':Street', $data->Street);
								$STH->bindParam(':StreetNumber', $data->StreetNumber);
								$STH->bindParam(':Postal', $data->Postal);
								$STH->bindParam(':Town', $data->Town);
								$STH->bindParam(':Email', $data->Email);
								$STH->bindParam(':Phone', $data->Phone);
								$STH->bindParam(':IBAN', $data->IBAN);
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
        if (isset($data->Name) && isset($data->Surname) && isset($data->Street) && isset($data->StreetNumber) && isset($data->Postal) && isset($data->Town) && isset($data->Email) && isset($data->Phone) && isset($data->IBAN) && isset($data->InitDate) &&
				isset($data->CautionAmount) && isset($data->MembershipID) && isset($data->Notes) ) {
            try {
                $STH = $DBH->prepare("INSERT INTO " . TableService::getTable(TableEnum::PARENTS) . " (Name, Surname, Street, StreetNumber, Postal, Town, Email, Phone, IBAN, InitDate, CautionAmount, MembershipID, Notes) VALUES (:Name, :Surname, :Street, :StreetNumber, :Postal, :Town, :Email, :Phone, :IBAN, :InitDate, :CautionAmount, :MembershipID, :Notes)");
								$STH->bindParam(':Name', $data->Name);
								$STH->bindParam(':Surname', $data->Surname);
								$STH->bindParam(':Street', $data->Street);
								$STH->bindParam(':StreetNumber', $data->StreetNumber);
								$STH->bindParam(':Postal', $data->Postal);
								$STH->bindParam(':Town', $data->Town);
								$STH->bindParam(':Email', $data->Email);
								$STH->bindParam(':Phone', $data->Phone);
								$STH->bindParam(':IBAN', $data->IBAN);
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
        if (isset($data->ID) && isset($data->Active) && isset($data->BikeID) && isset($data->KidNr) ) {
			try {
                $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::KIDS) . " SET Active = :Active, BikeID = :BikeID, KidNr = :KidNr WHERE ID = :ID");
				$STH->bindParam(':ID', $data->ID);
				$STH->bindParam(':Active', $data->Active);
				$STH->bindParam(':BikeID', $data->BikeID);
				$STH->bindParam(':KidNr', $data->KidNr);
                $STH->execute();
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in update kind status..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in update kind status..."];
        }
	}

	public static function updateKidExpiryDate($id, $expirydate) {

		global $DBH;
    if (isset($id) && isset($expirydate) ) {
			try {
        $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::KIDS) . " SET ExpiryDate = :ExpiryDate WHERE ID = :ID");
				$STH->bindParam(':ID', $id);
				$STH->bindParam(':ExpiryDate', $expirydate);
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
		$STH = $DBH->prepare("SELECT k.ID, k.ParentID, k.Name, k.Surname, DATE_FORMAT(k.BirthDate, '" . $mysqldateformat . "') BirthDate, k.Caution, DATE_FORMAT(k.ExpiryDate, '" . $mysqldateformat . "') ExpiryDate, k.Active, k.BikeID, k.KidNr, b.Number BikeNr
			FROM " . TableService::getTable(TableEnum::KIDS) . " k
			LEFT JOIN " . TableService::getTable(TableEnum::BIKES) . " b
			ON k.BikeID = b.ID");
		$STH->execute();
		return $STH->fetchAll();
  }

	public static function getKidByID($id) {
		$mysqldateformat = $GLOBALS['mysqldateformat'];
		$phpdateformat = $GLOBALS['phpdateformat'];
		global $DBH;
		if (isset($id)) {
			$STH = $DBH->prepare("SELECT k.ID, k.ParentID, k.Name, k.Surname, DATE_FORMAT(k.BirthDate, '" . $mysqldateformat . "') BirthDate, k.Caution,
			DATE_FORMAT(k.ExpiryDate, '" . $mysqldateformat . "') ExpiryDate, DATE_FORMAT(k.ExpiryDate, '" . $phpdateformat . "') PHPExpiryDate, k.Active,
			k.BikeID, k.KidNr, p.MembershipID
				FROM " . TableService::getTable(TableEnum::KIDS) . " k
				LEFT JOIN " . TableService::getTable(TableEnum::PARENTS) . " p
				ON k.ParentID = p.ID
				WHERE k.ID = :KidID");
			$STH->bindParam(':KidID', $id);
			$STH->execute();
			return $STH->fetch();
		} else {
			 return ["status" => -1, "error" => "Onvoldoende parameters in get kid by ID..."];
		}
	}

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

	public static function getAllMembers($accountcode=null) {
		$mysqldateformat = $GLOBALS['mysqldateformat'];
		$phpdateformat = $GLOBALS['phpdateformat'];
    global $DBH;
		$STH = $DBH->prepare("SELECT k.ID KidID, k.Name KidName, k.Surname KidSurname, DATE_FORMAT(k.BirthDate, '" . $mysqldateformat . "') KidBirthDate,
		DATE_FORMAT(k.ExpiryDate, '" . $mysqldateformat . "') KidExpiryDate, DATE_FORMAT(k.ExpiryDate, '" . $phpdateformat . "') KidExpiryDatePHP, k.Active KidActive,
		k.BikeID KidBikeID, k.KidNr KidNr, p.ID ParentID, p.Name ParentName, p.Surname ParentSurname, DATE_FORMAT(p.InitDate, '" . $mysqldateformat . "') ParentInitDate,
		p.CautionAmount ParentCautionAmount, p.MembershipID ParentMembershipID, m.MembershipName ParentMembershipName, p.Email ParentEmail,
		 (SELECT COUNT(*) FROM " . TableService::getTable(TableEnum::KIDS, $accountcode) . " l WHERE l.ParentID = k.ParentID AND l.Active = 1) ParentActiveKids,
		 (SELECT COUNT(*) FROM " . TableService::getTable(TableEnum::RENEWALS, $accountcode) . " r WHERE r.KidID = k.ID) NrRenewals
			FROM " . TableService::getTable(TableEnum::KIDS, $accountcode) . " k
			LEFT JOIN " . TableService::getTable(TableEnum::PARENTS, $accountcode) . " p
			ON k.ParentID = p.ID
			LEFT JOIN " . TableService::getTable(TableEnum::MEMBERSHIPS, $accountcode) . " m
			ON p.MembershipID = m.ID
			ORDER BY DATE(k.ExpiryDate) ASC, k.ID ASC");
		$STH->execute();
		return $STH->fetchAll();
  }

	public static function getParents() {
        global $DBH;
				$mysqldateformat = $GLOBALS['mysqldateformat'];
		$STH = $DBH->prepare("SELECT p.ID, p.Name, p.Surname, p.Street, p.StreetNumber, p.Postal, p.Town, p.Email,
			p.Phone, p.IBAN, DATE_FORMAT(p.InitDate, '" . $mysqldateformat . "') InitDate, p.CautionAmount, p.Notes,
			COUNT(k.ParentID) NrKids, SUM(CASE WHEN k.Active THEN 1 ELSE 0 END) ActiveKids,
			(SELECT COUNT(*) FROM " . TableService::getTable(TableEnum::BIKES) . " WHERE Donor IN (k.ID)) Donations,
			p.MembershipID, m.MembershipName
			FROM " . TableService::getTable(TableEnum::PARENTS) . " p
			LEFT JOIN " . TableService::getTable(TableEnum::KIDS) . " k
			ON p.ID = k.ParentID
			LEFT JOIN " . TableService::getTable(TableEnum::MEMBERSHIPS) . " m
			ON p.MembershipID = m.ID
			GROUP BY ID
			ORDER BY Surname");
		$STH->execute();
		return $STH->fetchAll();

    }

}
