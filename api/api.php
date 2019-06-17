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



/**
 * Create Slim and set settings
 **/
\Slim\Slim::registerAutoLoader();
$app = new \Slim\Slim();
$app->response->headers->set('Content-Type', 'application/json');
$app->response->headers->set('Access-Control-Allow-Origin', 'http://localhost/talismanneke');
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
    if (!isset($_SESSION["login"])) {
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
		global $DBH;
        $STH = $DBH->query("SELECT * FROM " . TableService::getTable(TableEnum::BIKES)  . " ORDER BY Number");
        echo json_encode($STH->fetchAll());
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
		global $DBH;
        $STH = $DBH->query("SELECT * FROM " . TableService::getTable(TableEnum::KIDS)  . " ORDER BY BirthDate");
        echo json_encode($STH->fetchAll());
    });

	/* DOES NOT WORK
	$app->get('/', function() use ($app) {
		echo json_encode(MembersService::getKids());
    });
	*/
});

$app->group('/members', function() use ($app) {

	$app->get('/', function() use ($app) {
		global $DBH;
        $STH = $DBH->query("SELECT * FROM " . TableService::getTable(TableEnum::KIDS)  . " ORDER BY BirthDate");
		$kids = $STH->fetchAll();
		$STHP = $DBH->query("SELECT * FROM " . TableService::getTable(TableEnum::PARENTS)  . " ORDER BY Name");
		$parents = $STHP->fetchAll();
        echo json_encode(["status" => 0, "kids" => $kids, "parents" => $parents]);
    });
	
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
			

			// delete any removed lines
			/*
			foreach ($GLOBALS["data"]->deleteorderitems as $orderitemnr) {
				$remol = OrdersService::deleteOrderLine($orderitemnr);
				if ($remol["status"] == -1) {
					throw new Exception($remol["error"]);
				}	
			}
			*/
			
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
});


$app->group('/transactions', function() use ($app) {

    $app->get('/', function() use ($app) {
		global $DBH;
        $STH = $DBH->query("SELECT * FROM " . TableService::getTable(TableEnum::TRANSACTIONS)  . " ORDER BY Date");
        echo json_encode($STH->fetchAll());
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

			/*
			if ($GLOBALS["data"]->updateKidFin != 0) {
				$ukf = MembersService::updateKidFinances($GLOBALS["data"]->kidFinances);
				if ($ukf["status"] == -1) {
					throw new Exception($ukf["error"]);
				}
			}	
			*/

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
	
	$app->get('/prices', function() use ($app) {
		global $DBH;
        $STH = $DBH->query("SELECT * FROM " . TableService::getTable(TableEnum::PRICES));
        echo json_encode($STH->fetchAll());
    });
	
	$app->post('/prices', function() use ($app) {
        global $DBH;
		try {
			$DBH->beginTransaction();
			foreach ($GLOBALS["data"]->updateData as $item) {
				$updp = SettingsService::updatePrices($item);
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
			if ($GLOBALS["data"]->emailid == 0) {
				$newe = SettingsService::newEmail($GLOBALS["data"]);
				if ($newe["status"] == -1) {
					throw new Exception($newe["error"]);
				} 
			} else {
				$upde = SettingsService::updateEmail($GLOBALS["data"]);
				if ($upde["status"] == -1) {
					throw new Exception($upde["error"]);
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
