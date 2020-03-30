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
require_once(__DIR__ . "/Services/FinancesService.php");
require_once(__DIR__ . "/Services/MembersService.php");
require_once(__DIR__ . "/Services/SettingsService.php");
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
$app->response->headers->set('Access-Control-Allow-Origin', 'http://localhost/opwielekes');
$app->response->headers->set('Access-Control-Allow-Methods', 'GET');
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
        $GLOBALS["error"] = "Je bent niet aangemeld.";
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



$app->group('/bikes', function() use ($app) {

  $app->get('/', function() use ($app) {
      echo json_encode(BikesService::getBikes());
  });

	$app->post('/', function() use ($app) {
        global $DBH;
		try {
			$DBH->beginTransaction();
			if ($GLOBALS["data"]->ID == 0) {
				$newi = BikesService::newBike($GLOBALS["data"]);
				if ($newi["status"] == -1) {
					throw new Exception($newi["error"]);
				}
			} else {
				$updi = BikesService::updateBIke($GLOBALS["data"]);
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
      echo json_encode(MembersService::getJoinedMembers());
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
      foreach ($GLOBALS["data"]->deleteKids as $kidnr) {
        $delk = MembersService::deleteKid($kidnr);
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
    				$upm = MembersService::updateKidExpiryDate($GLOBALS["data"]->membershipData);
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
        foreach ($GLOBALS["data"]->kidsid as $kid) {
          $delk = MembersService::deleteKid($kid->ID);
          if ($delk["status"] == -1) {
            throw new Exception($delk["error"]);
          }
        }
        $delp = MembersService::deleteParent($GLOBALS["data"]->parentid);
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

	$app->post('/', function() use ($app) {
        global $DBH;
		try {
			$DBH->beginTransaction();
			$newt = TransactionsService::newTransaction($GLOBALS["data"]->transactionData);
			if ($newt["status"] == -1) {
				throw new Exception($newt["error"]);
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

			if ($GLOBALS["data"]->updateFin != 0) {
				foreach ($GLOBALS["data"]->finTransactions as $item) {
					$newf = FinancesService::newTransaction($item);
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
				$newf = FinancesService::newTransaction($item);
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
          generateResponse(FinancesService::updateTransaction($id, $GLOBALS["data"]));
  });

	$app->post('/receive/:id', function($id) use ($app) {
        generateResponse(FinancesService::receiveTransaction($id));
    });

	$app->post('/delete/:id', function($id) use ($app) {
        generateResponse(FinancesService::deleteTransaction($id));
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

  	$app->get('/memberships', function() use ($app) {
  		    global $DBH;
          $STH = $DBH->query("SELECT * FROM " . TableService::getTable(TableEnum::MEMBERSHIPS));
          echo json_encode($STH->fetchAll());
    });

	   $app->post('/memberships', function() use ($app) {
        global $DBH;
    		try {
    			$DBH->beginTransaction();
    			foreach ($GLOBALS["data"]->updateData as $item) {
    				$updp = SettingsService::updateMemberships($item);
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

/*

$app->group('/prefs', function() use ($app) {

    $app->get('/', function() use ($app) {
		global $DBH;
        $STH = $DBH->query("SELECT * FROM " . TableService::getTable(TableEnum::PREFERENCES)  . " WHERE prefID = 1");
        echo json_encode($STH->fetchAll());
    });

	$app->get('/vars', function() use ($app) {
		global $DBH;
        $STH = $DBH->query("SELECT * FROM " . TableService::getTable(TableEnum::VARIABLES));
        echo json_encode($STH->fetchAll());
    });

	$app->post('/', function() use ($app) {
        generateResponse(PreferencesService::updateColumns($GLOBALS["data"]));
    });
});
*/

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
