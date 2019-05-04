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
require_once(__DIR__ . "/Services/MembersService.php");
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
				$newi = BikeService::newBike($GLOBALS["data"]);
				if ($newi["status"] == -1) {
					throw new Exception($newi["error"]);
				}	
			} else {
				$updi = BikeService::updateBIke($GLOBALS["data"]);
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
		global $DBH;
        $STH = $DBH->query("SELECT * FROM " . TableService::getTable(TableEnum::PARENTS)  . " ORDER BY Name");
        echo json_encode($STH->fetchAll());
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

});

$app->group('/members', function() use ($app) {

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
	

});







/*

$app->group('/settings', function() use ($app) {

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
	
	$app->get('/doctypes', function() use ($app) {
		global $DBH;
        $STH = $DBH->query("SELECT * FROM " . TableService::getTable(TableEnum::DOCUMENTEN));
        echo json_encode($STH->fetchAll());
    });
});		
	

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
