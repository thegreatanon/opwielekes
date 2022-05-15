<?php

require_once(__DIR__ . "/TableService.php");
require_once(__DIR__ . "/TableEnum.php");

class BikesService
{

	public static function updateBike($data) {

		global $DBH;
        if (isset($data->ID) && isset($data->Number) && isset($data->Name) && isset($data->Frame) && isset($data->Wheel) && isset($data->Tyre)
				  && isset($data->Brand) && isset($data->Gender) && isset($data->Colour) && isset($data->Gears) && isset($data->Location)
				  && isset($data->Source) && isset($data->Date) && isset($data->Notes) ) {
					try {
		          $STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::BIKES) . " SET Number = :Number, Name = :Name, Frame = :Frame, Wheel = :Wheel, Tyre = :Tyre, Brand = :Brand, Gender = :Gender, Colour = :Colour, Gears = :Gears, Location = :Location, InitDate = :InitDate, Source = :Source, Notes = :Notes  WHERE ID = :ID");
							$STH->bindParam(':ID', $data->ID);
							$STH->bindParam(':Number', $data->Number);
							$STH->bindParam(':Name', $data->Name);
							$STH->bindParam(':Frame', $data->Frame);
							$STH->bindParam(':Wheel', $data->Wheel);
							$STH->bindParam(':Tyre', $data->Tyre);
							$STH->bindParam(':Brand', $data->Brand);
							$STH->bindParam(':Gender', $data->Gender);
							$STH->bindParam(':Colour', $data->Colour);
							$STH->bindParam(':Gears', $data->Gears);
							$STH->bindParam(':Location', $data->Location);
							$STH->bindParam(':InitDate', $data->Date);
							$STH->bindParam(':Source', $data->Source);
							$STH->bindParam(':Notes', $data->Notes);
			        $STH->execute();
            } catch (Exception $e) {
               return ["status" => -1, "error" => "Er is iets fout gelopen in update fiets..."];
            }
        } else {
           return ["status" => -1, "error" => "Onvoldoende parameters in update fiets..."];

        }

    }

	public static function updateBikeStatus($data) {
			global $DBH;
	  	if (isset($data->ID) && isset($data->Status) ) {
					try {
	        		$STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::BIKES) . " SET Status = :Status WHERE ID = :ID");
							$STH->bindParam(':ID', $data->ID);
							$STH->bindParam(':Status', $data->Status);
	            $STH->execute();
	        } catch (Exception $e) {
	           	return ["status" => -1, "error" => "Er is iets fout gelopen in update fietsstatus..."];
	        }
      } else {
         	return ["status" => -1, "error" => "Onvoldoende parameters in update fietsstatus..."];
      }
    }

		public static function updateDonor($data) {
				global $DBH;
				if (isset($data->ID) && isset($data->Donated)  && isset($data->Donor)  && isset($data->DonationDate)) {
						try {
								$STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::BIKES) . " SET Donated = :Donated, Donor = :Donor, DonationDate = :DonationDate WHERE ID = :ID");
								$STH->bindParam(':ID', $data->ID);
								$STH->bindParam(':Donated', $data->Donated);
								$STH->bindParam(':Donor', $data->Donor);
								$STH->bindParam(':DonationDate', $data->DonationDate);
								$STH->execute();
						} catch (Exception $e) {
								return ["status" => -1, "error" => "Er is iets fout gelopen in update fietsdonor..."];
						}
				} else {
						return ["status" => -1, "error" => "Onvoldoende parameters in update fietsdonor..."];
				}
			}

	public static function newBike($data) {
		global $DBH;
        if (isset($data->Number) && isset($data->Name) && isset($data->Status) && isset($data->Frame) && isset($data->Wheel) && isset($data->Tyre)
				 		&& isset($data->Brand) && isset($data->Gender) && isset($data->Colour) && isset($data->Gears) && isset($data->Location)
						&& isset($data->Source) && isset($data->Date) && isset($data->Notes) ) {
            try {
                $STH = $DBH->prepare("INSERT INTO " . TableService::getTable(TableEnum::BIKES) . " (Number, Name, Frame, Wheel, Tyre, Brand, Gender, Colour, Gears, Location, InitDate, Status, Source, Notes) VALUES (:Number, :Name, :Frame, :Wheel, :Tyre, :Brand, :Gender, :Colour, :Gears, :Location, :InitDate, :Status, :Source, :Notes)");
								$STH->bindParam(':Number', $data->Number);
								$STH->bindParam(':Name', $data->Name);
								$STH->bindParam(':Frame', $data->Frame);
								$STH->bindParam(':Wheel', $data->Wheel);
								$STH->bindParam(':Tyre', $data->Tyre);
								$STH->bindParam(':Brand', $data->Brand);
								$STH->bindParam(':Gender', $data->Gender);
								$STH->bindParam(':Colour', $data->Colour);
								$STH->bindParam(':Gears', $data->Gears);
								$STH->bindParam(':Location', $data->Location);
								$STH->bindParam(':InitDate', $data->Date);
								$STH->bindParam(':Status', $data->Status);
								$STH->bindParam(':Source', $data->Source);
								$STH->bindParam(':Notes', $data->Notes);
                $STH->execute();
								$last_id = $DBH->lastInsertId();
								return ["status" => 0, "lastid" => $last_id];
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

		public static function getBikes() {
				$mysqldateformat = $GLOBALS['mysqldateformat'];
				global $DBH;
				$STH = $DBH->prepare("SELECT b.ID, b.Number, b.Name, b.Frame, b.Wheel, b.Tyre, b.Brand, b.Gender, b.Colour,
					b.Gears, b.Location, DATE_FORMAT(b.InitDate, '" . $mysqldateformat . "') InitDate, b.Status StatusNr,
					b.Donated, b.Donor, b.Source, b.Notes, s.Name StatusName, s.OnLoan StatusOnLoan, s.Available StatusAvailable,
					IFNULL(k.ID,0) KidID, IFNULL(k.ParentID,0) ParentID, IFNULL(CONCAT(k.Name, ' ', k.Surname), '') AS KidName,
					(SELECT COUNT(*) FROM " . TableService::getTable(TableEnum::BIKESTATUSLOGS) . " WHERE BikeID = b.ID AND NewStatusNr = 1) NrLoans,
					DATE_FORMAT((SELECT Date FROM " . TableService::getTable(TableEnum::BIKESTATUSLOGS) . " WHERE BikeID = b.ID AND NewStatusNr = 1 ORDER BY Date DESC LIMIT 1), '" . $mysqldateformat . "') LoanDate,
				  (SELECT ImageFile FROM " . TableService::getTable(TableEnum::BIKEIMAGES) . " WHERE BikeID = b.ID AND Active = 1 ORDER BY UploadDatetime DESC LIMIT 1) ImageFile,
					(SELECT ID FROM " . TableService::getTable(TableEnum::BIKEIMAGES) . " WHERE BikeID = b.ID AND Active = 1 ORDER BY UploadDatetime DESC LIMIT 1) ImageID
				FROM " . TableService::getTable(TableEnum::BIKES) . " b
				LEFT JOIN " . TableService::getTable(TableEnum::BIKESTATUS) . " s
				ON b.Status = s.ID
				LEFT JOIN " . TableService::getTable(TableEnum::KIDS) . " k
				ON k.BikeID = b.ID
				ORDER BY Number");
				$STH->execute();
				return $STH->fetchAll();
		}

		public static function getBikeStatusLogs() {
				$mysqldateformat = $GLOBALS['mysqldateformat'];
				global $DBH;
				$STH = $DBH->prepare("SELECT l.ID, l.BikeID, l.NewStatusNr StatusNr, s.Name StatusName, l.KidID, DATE_FORMAT(l.Date, '" . $mysqldateformat . "') Date
				FROM " . TableService::getTable(TableEnum::BIKESTATUSLOGS) . " l
				LEFT JOIN " . TableService::getTable(TableEnum::BIKESTATUS) . " s
				ON l.NewStatusNr = s.ID
				ORDER BY Date");
				$STH->execute();
				return $STH->fetchAll();
		}

		public static function getBikeStatusLogsForID($id) {
				$mysqldateformat = $GLOBALS['mysqldateformat'];
				global $DBH;
				$STH = $DBH->prepare("SELECT l.ID, l.BikeID, l.NewStatusNr StatusNr, s.Name StatusName, l.KidID, DATE_FORMAT(l.Date, '" . $mysqldateformat . "') Date, k.ParentID
				FROM " . TableService::getTable(TableEnum::BIKESTATUSLOGS) . " l
				LEFT JOIN " . TableService::getTable(TableEnum::BIKESTATUS) . " s
				ON l.NewStatusNr = s.ID
				LEFT JOIN " . TableService::getTable(TableEnum::KIDS) . " k
				ON l.kidID = k.ID
				WHERE l.BikeID=:id
				ORDER BY ID DESC, Date DESC");
				$STH->bindParam(':id', $id);
				$STH->execute();
				return $STH->fetchAll();
		}

		public static function newBikeStatusLog($data,$bikeid) {
				global $DBH;
				if (isset($bikeid) && isset($data->Status) && isset($data->KidID) && isset($data->Date) ) {
						try {
								$STH = $DBH->prepare("INSERT INTO " . TableService::getTable(TableEnum::BIKESTATUSLOGS) . " (BikeID, NewStatusNr, KidID, Date) VALUES (:BikeID, :NewStatusNr, :KidID, :Date)");
								$STH->bindParam(':BikeID', $bikeid);
								$STH->bindParam(':NewStatusNr', $data->Status);
								$STH->bindParam(':KidID', $data->KidID);
								$STH->bindParam(':Date', $data->Date);
								$STH->execute();
						} catch (Exception $e) {
							 return ["status" => -1, "error" => "Er is iets fout gelopen in nieuwe bike status log..."];
						}
				} else {
					 return ["status" => -1, "error" => "Onvoldoende parameters in nieuwe bike status log..."];

				}
		}

		public static function newBikeImage($data) {
			global $DBH;
			if (isset($data->BikeID) && isset($data->ImageFile) && isset($data->UploadDatetime) && isset($data->Active) ) {
					try {
							$STH = $DBH->prepare("INSERT INTO " . TableService::getTable(TableEnum::BIKEIMAGES) . " (BikeID, ImageFile, UploadDatetime, Active) VALUES (:BikeID, :ImageFile, :UploadDatetime, :Active)");
							$STH->bindParam(':BikeID', $data->BikeID);
							$STH->bindParam(':ImageFile', $data->ImageFile);
							$STH->bindParam(':UploadDatetime', $data->UploadDatetime);
							$STH->bindParam(':Active', $data->Active);
							$STH->execute();
					} catch (Exception $e) {
						 return ["status" => -1, "error" => "Er is iets fout gelopen in nieuwe bike image..."];
					}
			} else {
				 return ["status" => -1, "error" => "Onvoldoende parameters in nieuwe bike image..."];

			}
		}

		public static function deactivateBikeImage($data) {
				global $DBH;
				if (isset($data->ID) && isset($data->Active)) {
						try {
								$STH = $DBH->prepare("UPDATE " . TableService::getTable(TableEnum::BIKEIMAGES) . " SET Active = :Active WHERE ID = :ID");
								$STH->bindParam(':ID', $data->ID);
								$STH->bindParam(':Active', $data->Active);
								$STH->execute();
						} catch (Exception $e) {
								return ["status" => -1, "error" => "Er is iets fout gelopen in verwijder fietsfoto..."];
						}
				} else {
						return ["status" => -1, "error" => "Onvoldoende parameters in verwijder fietsfoto..."];
				}
		}
}
