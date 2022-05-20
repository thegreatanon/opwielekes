<?php
/**
 * Call session_start before any other output
 **/
session_start();

/**
 * Require the slim framework
 **/
require_once(__DIR__ . "/Slim/Slim.php");
require_once(__DIR__ . "/pdoconnect.php");

require_once(__DIR__ . "/Services/BikesService.php");
require_once(__DIR__ . "/Services/EmailsService.php");
require_once(__DIR__ . "/Services/FinancesService.php");
require_once(__DIR__ . "/Services/MembersService.php");
require_once(__DIR__ . "/Services/SettingsService.php");
require_once(__DIR__ . "/Services/RenewalsService.php");
require_once(__DIR__ . "/Services/TransactionsService.php");
require_once(__DIR__ . "/Services/TableService.php");
require_once(__DIR__ . "/Services/TableEnum.php");

 ini_set('display_errors', 1); error_reporting('E_ALL');

/**
 * Create Slim and set settings
 **/
\Slim\Slim::registerAutoLoader();
$app = new \Slim\Slim();
$app->response->headers->set('Content-Type', 'application/json');
//$app->response->headers->set('Access-Control-Allow-Origin', 'http://localhost/opwielekes');
//$app->response->headers->set('Access-Control-Allow-Origin', 'http://demo.opwielekes.be');
//$app->response->headers->set('Access-Control-Allow-Methods', 'GET');
$app->error(function() use ($app) {
    if (isset($GLOBALS["error"])) {
        echo json_encode(["errorMessage" => $GLOBALS["error"]]);
    } else {
        echo json_encode(["errorMessage" => "Er is een algemene fout opgetreden."]);
    }
});

/**
 * Hook (called before every dispatch of the event)
 **/
$app->hook("slim.before.dispatch", function() use ($app) {
    if ( !( ($app->request->getResourceUri() == '/members/register')
        || $app->request->getResourceUri() == '/settings/postalcodes'
        || isset($_SESSION["account"]) )) {

    //if ($app->request->getResourceUri() != '/members' && $app->request->getResourceUri() != '/settings/postalcodes' && !isset($_SESSION["account"])) {
        /**
         * If a user is not logged in, stop here! Otherwise all functions below need a check
         * unless the call is to /items, since this is used for a the public as well
         **/
        $app->response->setStatus(401);
        $GLOBALS["error"] = "Je bent niet aangemeld. De uri is " . $app->request->getResourceUri() . "<br>De account is " . $_SESSION["account"];
        $app->error();
    }

    if ($app->request->isPost()) {
        /**
         * Parse body of POST requests to php vars
         **/
        $GLOBALS["data"] = json_decode($app->request->getBody());
    }
});

$app->get('/', function() use($app) {
	$app->response->setStatus(401);
	$GLOBALS["error"] = "Hello world.";
	$app->error();
});

$app->get('/test', function() use($app) {
  var_dump($_SESSION);
});


$app->group('/bikes', function() use ($app) {

  $app->get('/', function() use ($app) {
      echo json_encode(BikesService::getBikes());
  });

	$app->post('/', function() use ($app) {
    global $DBH;
		try {
			$DBH->beginTransaction();
			if ($GLOBALS["data"]->newBike == 1) {
				$newi = BikesService::newBike($GLOBALS["data"]->bikeData);
				if ($newi["status"] == -1) {
					throw new Exception($newi["error"]);
				}
        $bikeid = $newi["lastid"];
        $updb = BikesService::newBikeStatusLog($GLOBALS["data"]->bikeData,$bikeid);
        if ($updb["status"] == -1) {
          throw new Exception($updb["error"]);
        }
			} else {
				$updi = BikesService::updateBike($GLOBALS["data"]->bikeData);
				if ($updi["status"] == -1) {
					throw new Exception($updi["error"]);
				}
			}
			$DBH->commit();
		} catch (Exception $e) {
			$DBH->rollBack();
			$GLOBALS["error"] = $e->getMessage();
			$app->error();
		}
		echo json_encode(null);
    });

    $app->post('/delete/:id', function($id) use ($app) {
      generateResponse(BikesService::deleteBike($id));
    });

    $app->get('/statuslogs', function() use ($app) {
        echo json_encode(BikesService::getBikeStatusLogs());
    });

    $app->get('/statuslogs/:id', function($id) use ($app) {
        echo json_encode(BikesService::getBikeStatusLogsForID($id));
    });

    $app->post('/bikestatus', function() use ($app) {
      global $DBH;
      try {
          $DBH->beginTransaction();
					$ubsi = BikesService::updateBikeStatus($GLOBALS["data"]);
					if ($ubsi["status"] == -1) {
						throw new Exception($ubsi["error"]);
					}
          $updb = BikesService::newBikeStatusLog($GLOBALS["data"],$GLOBALS["data"]->ID);
          if ($updb["status"] == -1) {
            throw new Exception($updb["error"]);
          }
          $DBH->commit();
        } catch (Exception $e) {
          $DBH->rollBack();
          $GLOBALS["error"] = $e->getMessage();
          $app->error();
        }
        echo json_encode(null);
     });

     $app->post('/image', function() use ($app) {
       global $DBH;
       try {
          $DBH->beginTransaction();
 					$nbim = BikesService::newBikeImage($GLOBALS["data"]);
 					if ($nbim["status"] == -1) {
 						throw new Exception($nbim["error"]);
 					}
           $DBH->commit();
         } catch (Exception $e) {
           $DBH->rollBack();
           $GLOBALS["error"] = $e->getMessage();
           $app->error();
         }
         echo json_encode(null);
      });

      $app->post('/deleteimage', function() use ($app) {
        global $DBH;
        try {
           $DBH->beginTransaction();
           $dbim = BikesService::deactivateBikeImage($GLOBALS["data"]);
           if ($dbim["status"] == -1) {
             throw new Exception($dbim["error"]);
           }
            $DBH->commit();
          } catch (Exception $e) {
            $DBH->rollBack();
            $GLOBALS["error"] = $e->getMessage();
            $app->error();
          }
          echo json_encode(null);
       });

       $app->post('/archive', function() use ($app) {
         global $DBH;
         try {
            $DBH->beginTransaction();
            $arbi = BikesService::archiveBike($GLOBALS["data"]);
            if ($arbi["status"] == -1) {
              throw new Exception($arbi["error"]);
            }
             $DBH->commit();
           } catch (Exception $e) {
             $DBH->rollBack();
             $GLOBALS["error"] = $e->getMessage();
             $app->error();
           }
           echo json_encode(null);
        });
});

$app->group('/parents', function() use ($app) {

	$app->get('/', function() use ($app) {
      echo json_encode(MembersService::getParents());
    });

	$app->post('/', function() use ($app) {
    global $DBH;
		try {
			$DBH->beginTransaction();
			if ($GLOBALS["data"]->ID == 0) {
				$newk = MembersService::newParent($GLOBALS["data"]);
				if ($newk["status"] == -1) {
					throw new Exception($newk["error"]);
				}
			} else {
				$updk = MembersService::updateParentData($GLOBALS["data"]);
				if ($updk["status"] == -1) {
					throw new Exception($updk["error"]);
				}
	    }
	    $DBH->commit();
		} catch (Exception $e) {
			$DBH->rollBack();
			$GLOBALS["error"] = $e->getMessage();
			$app->error();
		}
		echo json_encode(null);
    });
});

$app->group('/kids', function() use ($app) {

	  $app->get('/', function() use ($app) {
		    echo json_encode(MembersService::getKids());
    });

});

$app->group('/members', function() use ($app) {

  /*
  // not in use anymore?
	$app->get('/', function() use ($app) {
		global $DBH;
        $STH = $DBH->query("SELECT * FROM " . TableService::getTable(TableEnum::KIDS)  . " ORDER BY BirthDate");
		$kids = $STH->fetchAll();
		$STHP = $DBH->query("SELECT * FROM " . TableService::getTable(TableEnum::PARENTS)  . " ORDER BY Name");
		$parents = $STHP->fetchAll();
        echo json_encode(["status" => 0, "kids" => $kids, "parents" => $parents]);
    });
    */
	$app->get('/all', function() use ($app) {
      echo json_encode(MembersService::getAllMembers());
    });

  $app->post('/', function() use ($app) {
    global $DBH;
		try {
			$DBH->beginTransaction();
			if ($GLOBALS["data"]->parentID == 0) {
				$newp = MembersService::newParent($GLOBALS["data"]->parentdata);
				if ($newp["status"] == -1) {
					throw new Exception($newp["error"]);
				}
				$parentid = $newp["lastid"];
			} else {
				$updp = MembersService::updateParentData($GLOBALS["data"]->parentdata);
				if ($updp["status"] == -1) {
					throw new Exception($updp["error"]);
				}
				$parentid = $GLOBALS["data"]->parentID;
			}
			foreach ($GLOBALS["data"]->kidsdata as $kid) {
				if ($kid->ID == 0) {
					$newk = MembersService::newKid($kid,$parentid);
					if ($newk["status"] == -1) {
						throw new Exception($newk["error"]);
					}
				} else {
					$updk = MembersService::updateKidData($kid,$parentid);
					if ($updk["status"] == -1) {
						throw new Exception($updk["error"]);
					}
				}
			}
      foreach ($GLOBALS["data"]->deleteKids as $kidsdata) {
        $delk = MembersService::archiveKid($kidsdata);
        if ($delk["status"] == -1) {
          throw new Exception($delk["error"]);
        }
      }
			$DBH->commit();
		} catch (Exception $e) {
			$DBH->rollBack();
			$GLOBALS["error"] = $e->getMessage();
			$app->error();
		}
		echo json_encode(null);
    });

    $app->post('/register', function() use ($app) {
      global $DBH;
  		try {
  			$DBH->beginTransaction();
				$newp = MembersService::newParent($GLOBALS["data"]->parentdata);
				if ($newp["status"] == -1) {
					throw new Exception($newp["error"]);
				}
				$parentid = $newp["lastid"];
  			foreach ($GLOBALS["data"]->kidsdata as $kid) {
					$newk = MembersService::newKid($kid,$parentid);
					if ($newk["status"] == -1) {
						throw new Exception($newk["error"]);
					}
  			}
        $newl = MembersService::logRegistration($GLOBALS["data"]->logdata, $parentid);
        if ($newl["status"] == -1) {
          throw new Exception($newl["error"]);
        }
  			$DBH->commit();
  		} catch (Exception $e) {
  			$DBH->rollBack();
  			$GLOBALS["error"] = $e->getMessage();
  			$app->error();
  		}
  		echo json_encode(null);
      });

	$app->post('/payments', function() use ($app) {
        global $DBH;
    		try {
    			$DBH->beginTransaction();

    			$pr = FinancesService::receiveTransaction($GLOBALS["data"]->finTransID);
    			if ($pr["status"] == -1) {
    				throw new Exception($pr["error"]);
    			}

    			if ($GLOBALS["data"]->updateCaution != 0) {
    				$upc = MembersService::updateParentCaution($GLOBALS["data"]->cautionData);
    				if ($upc["status"] == -1) {
    					throw new Exception($upc["error"]);
    				}
    			}

    			if ($GLOBALS["data"]->updateMembership != 0) {
    				$upm = MembersService::updateKidExpiryDate($GLOBALS["data"]->membershipData->ID, $GLOBALS["data"]->membershipData->ExpiryDate);
    				if ($upm["status"] == -1) {
    					throw new Exception($upm["error"]);
    				}
    			}
    			$DBH->commit();
    		} catch (Exception $e) {
    			$DBH->rollBack();
    			$GLOBALS["error"] = $e->getMessage();
    			$app->error();
    		}
    		echo json_encode(null);
    });

    $app->post('/delete', function() use ($app) {
      global $DBH;
      try {
        $DBH->beginTransaction();
        foreach ($GLOBALS["data"]->kidsdata as $kid) {
          $delk = MembersService::archiveKid($kid);
          if ($delk["status"] == -1) {
            throw new Exception($delk["error"]);
          }
        }
        $delp = MembersService::archiveParent($GLOBALS["data"]->parentdata);
        if ($delp["status"] == -1) {
          throw new Exception($delp["error"]);
        }
        $DBH->commit();
      } catch (Exception $e) {
        $DBH->rollBack();
        $GLOBALS["error"] = $e->getMessage();
        $app->error();
      }
      echo json_encode(null);
    });

    $app->post('/deletekid/:id', function($id) use ($app) {
          generateResponse(MembersService::deleteKid($id));
      });
});


$app->group('/transactions', function() use ($app) {

    $app->get('/', function() use ($app) {
      echo json_encode(TransactionsService::getTransactions());
    });

    $app->get('/parent/:id', function($id) use ($app) {
      echo json_encode(TransactionsService::getTransactionsByParentID($id));
    });

	$app->post('/', function() use ($app) {
    global $DBH;
		try {
			$DBH->beginTransaction();
			$newt = TransactionsService::newTransaction($GLOBALS["data"]->transactionData);
			if ($newt["status"] == -1) {
				throw new Exception($newt["error"]);
			}
      $transid = $newt["lastid"];

      $uex = MembersService::updateKidExpiryDate($GLOBALS["data"]->expiryData->ID, $GLOBALS["data"]->expiryData->ExpiryDate );
      if ($uex["status"] == -1) {
        throw new Exception($uex["error"]);
      }

			if ($GLOBALS["data"]->updateKid != 0) {
				$uks = MembersService::updateKidStatus($GLOBALS["data"]->kidStatus);
				if ($uks["status"] == -1) {
					throw new Exception($uks["error"]);
				}
			}

      if ($GLOBALS["data"]->updateCaution != 0) {
				$upc = MembersService::updateParentCaution($GLOBALS["data"]->cautionData);
				if ($upc["status"] == -1) {
					throw new Exception($upc["error"]);
				}
			}

      if ($GLOBALS["data"]->updateDonor != 0) {
				$upc = BikesService::updateDonor($GLOBALS["data"]->donorData);
				if ($upd["status"] == -1) {
					throw new Exception($upd["error"]);
				}
			}

			if ($GLOBALS["data"]->updateFin != 0) {
				foreach ($GLOBALS["data"]->finTransactions as $item) {
					$newf = FinancesService::newTransaction($item, $transid);
					if ($newf["status"] == -1) {
						throw new Exception($newf["error"]);
					}
				}
			}

			if ($GLOBALS["data"]->updateBike != 0) {
				foreach ($GLOBALS["data"]->bikeStatus as $item) {
					if ($item->ID != 0) {
						$ubsi = BikesService::updateBikeStatus($item);
						if ($ubsi["status"] == -1) {
							throw new Exception($ubsi["error"]);
						}
            $updbl = BikesService::newBikeStatusLog($item,$item->ID);
            if ($updbl["status"] == -1) {
              throw new Exception($updbl["error"]);
            }
					}
				}
			}

			$DBH->commit();
		} catch (Exception $e) {
			$DBH->rollBack();
			$GLOBALS["error"] = $e->getMessage();
			$app->error();
		}
		echo json_encode(null);
    });

});


$app->group('/finances', function() use ($app) {

	$app->get('/', function() use ($app) {
      echo json_encode(FinancesService::getTransactions());
    });

	$app->post('/', function() use ($app) {
        global $DBH;
		try {
			$DBH->beginTransaction();
			foreach ($GLOBALS["data"]->finTransactions as $item) {
				$newf = FinancesService::newTransaction($item,0);
				if ($newf["status"] == -1) {
					throw new Exception($newf["error"]);
				}
			}
			$DBH->commit();
		} catch (Exception $e) {
			$DBH->rollBack();
			$GLOBALS["error"] = $e->getMessage();
			$app->error();
		}
		echo json_encode(null);
    });

  $app->post('/update/:id', function($id) use ($app) {
        generateResponse(FinancesService::processPayment($id, $GLOBALS["data"]));
  });

	$app->post('/delete/:id', function($id) use ($app) {
        generateResponse(FinancesService::deleteTransaction($id));
    });

});

$app->group('/email', function() use ($app) {
  $app->post('/', function() use ($app) {
     global $DBH;
     try {
       $DBH->beginTransaction();
       $upde = EmailsService::newEmail($GLOBALS["data"]);
       if ($upde["status"] == -1) {
         throw new Exception($upde["error"]);
       }
       $DBH->commit();
     } catch (Exception $e) {
       $DBH->rollBack();
       $GLOBALS["error"] = $e->getMessage();
       $app->error();
     }
     echo json_encode(null);
  });
});

$app->group('/settings', function() use ($app) {

	 $app->get('/actions', function() use ($app) {
		    global $DBH;
        $STH = $DBH->query("SELECT * FROM " . TableService::getTable(TableEnum::ACTIONS));
        echo json_encode($STH->fetchAll());
    });

    $app->get('/bikestatuses', function() use ($app) {
         global $DBH;
         $STH = $DBH->query("SELECT * FROM " . TableService::getTable(TableEnum::BIKESTATUS));
         echo json_encode($STH->fetchAll());
     });

     $app->post('/bikestatuses', function() use ($app) {
       global $DBH;
       try {
           $DBH->beginTransaction();
           foreach ($GLOBALS["data"]->statusData as $item) {
             $updb = SettingsService::updateBikeStatuses($item);
             if ($updb["status"] == -1) {
               throw new Exception($updb["error"]);
             }
           }
           $DBH->commit();
         } catch (Exception $e) {
           $DBH->rollBack();
           $GLOBALS["error"] = $e->getMessage();
           $app->error();
         }
         echo json_encode(null);
      });

     $app->get('/postalcodes', function() use ($app) {
          global $DBH;
          $STH = $DBH->query("SELECT * FROM " . TableService::getTable(TableEnum::POSTALCODES) . " order by City");
          echo json_encode($STH->fetchAll());
     });

     $app->get('/preferences', function() use ($app) {
 		    	echo json_encode(SettingsService::getPreferences());
     });

     $app->post('/preferences/emails', function() use ($app) {
        global $DBH;
        try {
          $DBH->beginTransaction();
          $upde = SettingsService::updateEmailPreferences($GLOBALS["data"]);
          if ($upde["status"] == -1) {
            throw new Exception($upde["error"]);
          }
          $DBH->commit();
        } catch (Exception $e) {
          $DBH->rollBack();
          $GLOBALS["error"] = $e->getMessage();
          $app->error();
        }
        echo json_encode(null);
     });

     $app->post('/preferences/reminders', function() use ($app) {
        global $DBH;
        try {
          $DBH->beginTransaction();
          $upde = SettingsService::updateEmailReminders($GLOBALS["data"]);
          if ($upde["status"] == -1) {
            throw new Exception($upde["error"]);
          }
          $DBH->commit();
        } catch (Exception $e) {
          $DBH->rollBack();
          $GLOBALS["error"] = $e->getMessage();
          $app->error();
        }
        echo json_encode(null);
     });

     $app->post('/preferences/defaultpayment', function() use ($app) {
        global $DBH;
        try {
          $DBH->beginTransaction();
          $upde = SettingsService::updateDefaultPaymentInfo($GLOBALS["data"]);
          if ($upde["status"] == -1) {
            throw new Exception($upde["error"]);
          }
          $DBH->commit();
        } catch (Exception $e) {
          $DBH->rollBack();
          $GLOBALS["error"] = $e->getMessage();
          $app->error();
        }
        echo json_encode(null);
     });

     $app->get('/paymentmethods', function() use ($app) {
   		    global $DBH;
           $STH = $DBH->query("SELECT * FROM " . TableService::getTable(TableEnum::PAYMENTMETHODS));
           echo json_encode($STH->fetchAll());
     });

     $app->post('/paymentmethods', function() use ($app) {
         global $DBH;
        try {
          $DBH->beginTransaction();
          foreach ($GLOBALS["data"]->updateData as $item) {
            $updpm = SettingsService::updatePaymentMethods($item);
            if ($updpm["status"] == -1) {
              throw new Exception($updpm["error"]);
            }
          }
        $DBH->commit();
        } catch (Exception $e) {
          $DBH->rollBack();
          $GLOBALS["error"] = $e->getMessage();
          $app->error();
        }
        echo json_encode(null);
     });

     $app->get('/properties', function() use ($app) {
           echo json_encode(SettingsService::getProperties());
     });

     $app->post('/properties', function() use ($app) {
        global $DBH;
        try {
          $DBH->beginTransaction();
          foreach ($GLOBALS["data"]->updateData as $item) {
            $updp = SettingsService::updateProperty($item);
            if ($updp["status"] == -1) {
              throw new Exception($updp["error"]);
            }
          }
        $DBH->commit();
        } catch (Exception $e) {
          $DBH->rollBack();
          $GLOBALS["error"] = $e->getMessage();
          $app->error();
        }
        echo json_encode(null);
     });

  	$app->get('/memberships', function() use ($app) {
  		    echo json_encode(SettingsService::getMemberships());
    });

    $app->post('/memberships', function() use ($app) {
       global $DBH;
       try {
         $DBH->beginTransaction();
         foreach ($GLOBALS["data"]->updateData as $item) {
           if ($item->ID == 0) {
             $nems = SettingsService::newMembership($item);
             if ($nems["status"] == -1) {
               throw new Exception($nems["error"]);
             }
           } else {
             $updp = SettingsService::updateMembership($item);
             if ($updp["status"] == -1) {
               throw new Exception($updp["error"]);
             }
           }
         }
         $DBH->commit();
       } catch (Exception $e) {
         $DBH->rollBack();
         $GLOBALS["error"] = $e->getMessage();
         $app->error();
       }
       echo json_encode(null);
   });

    $app->get('/emails', function() use ($app) {
		    global $DBH;
        $STH = $DBH->query("SELECT * FROM " . TableService::getTable(TableEnum::EMAILS));
        echo json_encode($STH->fetchAll());
    });

	   $app->post('/email', function() use ($app) {
        global $DBH;
    		try {
      			$DBH->beginTransaction();
      			if ($GLOBALS["data"]->ID == 0) {
      				$newe = SettingsService::newEmail($GLOBALS["data"]);
      				if ($newe["status"] == -1) {
      					throw new Exception($newe["error"]);
      				}
              $emailid = $newe["lastid"];
      			} else {
      				$upde = SettingsService::updateEmail($GLOBALS["data"]);
      				if ($upde["status"] == -1) {
      					throw new Exception($upde["error"]);
      				}
              $emailid = $GLOBALS["data"]->ID;
      			}
    			  $DBH->commit();
      		} catch (Exception $e) {
      			$DBH->rollBack();
      			$GLOBALS["error"] = $e->getMessage();
      			$app->error();
      		}
    		    echo json_encode($emailid);
      });

      $app->post('/deleteemail/:id', function($id) use ($app) {
            generateResponse(SettingsService::deleteEmail($id));
      });

      $app->post('/emailsettings', function() use ($app) {
        global $DBH;
     		try {
       			$DBH->beginTransaction();
     				$newe = SettingsService::updateEmailSettings($GLOBALS["data"]);
     				if ($newe["status"] == -1) {
     					throw new Exception($newe["error"]);
     				}
     			  $DBH->commit();
       		} catch (Exception $e) {
       			$DBH->rollBack();
       			$GLOBALS["error"] = $e->getMessage();
       			$app->error();
       		}
     		  echo json_encode(null);
       });
});

function generateResponse($response) {
    global $app;
    if ($response["status"] == 0) {
        if (isset($response["data"])) {
            echo json_encode($response["data"]);
		} else {
			echo json_encode(null);
		}
    } else {
        if (!isset($response["error"])) {
            $response["error"] = "Er is iets fout gelopen...";
        }
        $GLOBALS["error"] = $response["error"];
        $app->error();
    }
}

$app->run();

exit();
