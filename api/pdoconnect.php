<?php
	include(__DIR__ . '/../vars.php');
	$host = $pdohost;
	$user = $pdouser;
	$pass = $pdopass;
	$dbname = $pdodbname;
	try {
		$DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4", PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
		$DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e) {
		echo "not connected";
		echo("Failed to connect to database: ".$e->getMessage());
	}
?>
