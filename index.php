<?php
    session_start();

    require_once(__DIR__ . "/api/pdoconnect.php");
  	require_once(__DIR__ . "/api/Services/SettingsService.php");

  	// load database info
  	$accounts = SettingsService::getAccounts();
  	$_SESSION['accounts'] = $accounts;
    $_SESSION['urlaccount'] = '';
    $_SESSION['mode'] = 'signin';

    // set paths
  	if (substr($_SERVER['REMOTE_ADDR'], 0, 4) == '127.' || $_SERVER['REMOTE_ADDR'] == '::1') {
      $locbase =  "/opwielekes/";
  	} else {
      $locbase =  "/";
  	}

    // check if a location has been selected in account.php
    if (isset($_POST["selectaccountID"])) {
      $accid = $_POST["selectaccountID"];
      //unset($_POST["selectaccountID"]));
      $key = array_search($accid, array_column($accounts, 'AccountID'));
      if (false !== $key) {
        $account = $accounts[$key];
        $aclink = $account['AccountLink'];
        $newurl = 'Location: ' . $aclink;
        unset($_SESSION["account"]);
        header($newurl);
        exit();
      }
    }

    // detect location request
    $request = $_SERVER['REQUEST_URI'];
    $request2  = str_replace($locbase, "", $request);
  	$request3 = explode("/",$request2);
    $nrrequests = count($request3);
    $locationrequest = $request3[0];

    // verify whether location request is valid
    $key = array_search($locationrequest, array_column($accounts, 'AccountLink'));
		if (false !== $key) {
      $account = $accounts[$key];
      $_SESSION['urlaccount'] = $account;
    } else {
      require_once('account.php');
      exit();
		}

    // verify whether it is a signup
    if ($nrrequests == 2) {
      $siterequest = $request3[1];
      if ($siterequest == 'signup') {
            $_SESSION["mode"] ='signup';
      }
    }

    // upon submitting this form
    if (isset($_POST["password"])) {
        if (!isset($_POST["loginID"])) {
            $_SESSION["error"] = "Geen omgeving geselecteerd.";
            $_SESSION["dbcode"] = '';
        } else {
            $envID = $_POST["loginID"];
            foreach ($accounts as $account) {
               if ($account['AccountID'] == $envID) {
                  $acname = $account['AccountName'];
                  $acpswd = $account['AccountPassword'];
                  $accode = $account['AccountCode'];
                  $aclink = $account['AccountLink'];
                  $loginaccount = $account;
                }
            }
            if ( $_POST["password"] == $acpswd ) {
              $_SESSION["account"] = $loginaccount;
              $_SESSION["dbcode"] = $accode;
            } else {
              $_SESSION["error"] = "Ongeldig wachtwoord.";
              $_SESSION["dbcode"] = '';
            }
        }
    }

    // if we get sent here by logout
    if (isset($_POST["logout"])) {
        unset($_SESSION["account"]);
        unset($_SESSION["dbcode"]);
    }


    if ($_SESSION['mode'] == 'signup') {
        require_once('signup.php');
    } else {
        if (!isset($_SESSION["account"])) {
            require_once('login.php');
        } else {
            if ($_SESSION["account"]["AccountLink"] == $_SESSION["urlaccount"]["AccountLink"]) {
              require_once('main.php');
            } else {
              unset($_SESSION["account"]);
              require_once('login.php');
            }
        }
    }
