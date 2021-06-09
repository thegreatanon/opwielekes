<?php

    // SESSION TIMINGS
    // see https://stackoverflow.com/questions/8311320/how-to-change-the-session-timeout-in-php
    // server should keep session data for AT LEAST 1 hour
    ini_set('session.gc_maxlifetime', 3600);
    // each client should remember their session id for EXACTLY 1 hour
    // see https://stackoverflow.com/questions/39750906/php-setcookie-samesite-strict
    //for php < 7.3
    session_set_cookie_params (3600);
    //session_set_cookie_params(3600 ,'/; samesite=lax', null , false , false);
    //setcookie('cookie-name', '1', 0, '/; samesite=strict');
    //header("Set-Cookie: admin.opwielekes.be; path=/; SameSite=Lax");
    //for php > 7.3
    // session_set_cookie_params([
    //   'lifetime' => 3600,
    //   //'path' => '/',
    //   //'domain' => $cookie_domain,
    //   'secure' => false,
    //   //'httponly' => $cookie_httponly,
    //   'samesite' => 'None'
    // ]);
    session_start();
    $now = time();
    if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
        // this session has worn out its welcome; kill it and start a brand new one
        session_unset();
        session_destroy();
        session_start();
    }
    // either new or old, it should live at most for another hour
    $_SESSION['discard_after'] = $now + 3600;

    // CONNECTION TO DATABASE TO SEE VALID ACCOUNTS
    require_once(__DIR__ . "/api/pdoconnect.php");
    require_once(__DIR__ . "/api/Services/SettingsService.php");
  	$accounts = SettingsService::getAccounts();

    // variables for authentication management
    // account is the account we are logged in
    // urlaccount is the account corresponding to the url
  	$_SESSION['accounts'] = $accounts; // all possible accounts
    $_SESSION['mode'] = 'signin';      // sign in or sign up

    // set paths
  	if (substr($_SERVER['REMOTE_ADDR'], 0, 4) == '127.' || $_SERVER['REMOTE_ADDR'] == '::1') {
      $locbase =  "/opwielekes/";
      $folbase = "";
      $_SESSION["pdfbase"] = "http://localhost/opwielekes/pdf/";
  	} else {
      $locbase =  "/";
      $folbase = "";
      $_SESSION["pdfbase"] = "http://admin.opwielekes.be/pdf/";
  	}

    // detect location request
    $request = $_SERVER['REQUEST_URI'];
    $pos = strpos($request, $locbase);
    if ($pos !== false) {
        $request2 = substr_replace($request, "", $pos, strlen($locbase));
    }
  	$request3 = explode("/",$request2);
    $nrrequests = count($request3);
    $depot = $request3[0];

    // verify whether location request is valid
    $key = array_search($depot, array_column($accounts, 'AccountLink'));
		if (false !== $key) {
      $account = $accounts[$key];
      $_SESSION["urlaccount"] = $account;
    } else {
      // not a valid location
      require_once('account.php');
      exit();
		}

    // verify whether it is a signup
    if ($nrrequests > 1) {
      $siterequest = $request3[1];
      if ($siterequest == 'signup') {
            $_SESSION["mode"] ='signup';
      }
    }

    // verify if we are logged in
    if (isset($_POST["password"])) {
        if ( $_POST["password"] == $_SESSION['urlaccount']['AccountPassword'] ) {
          $_SESSION["account"] = $_SESSION['urlaccount'];
        } else {
          unset($_SESSION["account"]);
          $_SESSION["error"] = "Ongeldig wachtwoord.";
        }
    }

    // if we get sent here by logout
    if (isset($_POST["logout"])) {
        unset($_SESSION["account"]);
    }

    // require the right php file
    if ($_SESSION['mode'] == 'signup') {
        require_once('signup.php');
    } else {
        if (!isset($_SESSION["account"])) {
            require_once('login.php');
        } else {
            // set path
            if (substr($_SERVER['REMOTE_ADDR'], 0, 4) == '127.' || $_SERVER['REMOTE_ADDR'] == '::1') {
              $_SESSION["baseurl"] =  "//localhost/opwielekes/" . $_SESSION["account"]["AccountLink"];
            } else {
              $_SESSION["baseurl"] =  "http://" . $_SESSION["account"]["AccountLink"] . ".opwielekes.be";
            }
            // load main
            if ($_SESSION["account"]["AccountLink"] == $_SESSION["urlaccount"]["AccountLink"]) {
              require_once('main.php');
            } else {
              unset($_SESSION["account"]);
              require_once('login.php');
            }
        }
    }

?>
