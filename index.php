<?php
    session_start();

    /**
     * De login zou ook via de REST api kunnen gedaan worden, maar ik doe
     * het even hier ter illustratie dat alles ook puur in php kan uiteraard.
     * Het is ook basic gehouden, kwestie dat de principes maar wat getoond
     * worden.
     **/
    if (isset($_POST["password"])) {
        if (!isset($_POST["environment"])) {
            $_SESSION["error"] = "Geen omgeving geselecteerd.";
        } else if ($_POST["environment"] != "ledeberg" && $_POST["environment"] != "moscou" && $_POST["environment"] != "demo") {
            $_SESSION["error"] = "Omgeving " . $_POST["enviromnent"] . " bestaat niet";
        } else {
            if ($_POST["environment"] == "ledeberg" && $_POST["password"] == "NBV") {
                $_SESSION["login"] = "ledeberg";
			} else if ($_POST["environment"] == "moscou" && $_POST["password"] == "NBV") {
                $_SESSION["login"] = "moscou";
            } else if ($_POST["environment"] == "demo" && $_POST["password"] == "NBV") {
                $_SESSION["login"] = "demo";
            } else {
                $_SESSION["error"] = "Ongeldig wachtwoord.";
            }
        }
    }

    if (isset($_POST["logout"])) {
        unset($_SESSION["login"]);
    }

    if (!isset($_SESSION["login"])) {
        // User is not logged in, show login-page
        require_once('login.php');
    } else {
        // User is logged in, show main page
        require_once('main.php');
    }
