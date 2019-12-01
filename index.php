<?php
    session_start();

    require_once(__DIR__ . "/api/pdoconnect.php");
  	require_once(__DIR__ . "/api/Services/SettingsService.php");

  	// load database info
  	$accounts = SettingsService::getAccounts();
  	$_SESSION['accounts'] = $accounts;
    $_SESSION['dbcode'] = '';
    $_SESSION['account'] = '';

    // set paths
  	if (substr($_SERVER['REMOTE_ADDR'], 0, 4) == '127.' || $_SERVER['REMOTE_ADDR'] == '::1') {
      $locbase =  "/opwielekes/";
  	} else {
      $locbase =  "/";
  	}

    // detect location request
    $request = $_SERVER['REQUEST_URI'];
    $request2  = str_replace($locbase, "", $request);
  	$request3 = explode("/",$request2);
    $locationrequest = $request3[0];

    // verify whether location request is valid
    $key = array_search($locationrequest, array_column($accounts, 'AccountLink'));
		if (false !== $key) {
      $account = $accounts[$key];
      $_SESSION['account'] = $account;
    } else {
			unset($_SESSION["account"]);
		}

    /**
     * De login zou ook via de REST api kunnen gedaan worden, maar ik doe
     * het even hier ter illustratie dat alles ook puur in php kan uiteraard.
     * Het is ook basic gehouden, kwestie dat de principes maar wat getoond
     * worden.
     **/
    if (isset($_POST["password"])) {
        if (!isset($_POST["environmentID"])) {
            $_SESSION["error"] = "Geen omgeving geselecteerd.";
            $_SESSION["dbcode"] = '';
        } else {
            $envID = $_POST["environmentID"];
            foreach ($accounts as $account) {
               if ($account['AccountID'] == $envID) {
                  $acname = $account['AccountName'];
                  $acpswd = $account['AccountPassword'];
                  $accode = $account['AccountCode'];
                }
            }
            if ( $_POST["password"] == $acpswd ) {
              $_SESSION["login"] = $acname;
              $_SESSION["dbcode"] = $accode;
            } else {
              $_SESSION["error"] = "Ongeldig wachtwoord.";
              $_SESSION["dbcode"] = '';
            }
        }
    }

    if (isset($_POST["logout"])) {
        unset($_SESSION["login"]);
        unset($_SESSION["dbcode"]);
    }

    if (!isset($_SESSION["login"])) {
        // User is not logged in, show login-page
        require_once('login.php');
    } else {
        // User is logged in, show main page
        require_once('main.php');
    }
