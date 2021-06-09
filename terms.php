<?php

// get database access
/*
require_once(__DIR__ . "/api/pdoconnect.php");
require_once(__DIR__ . "/api/Services/OrdersService.php");
require_once(__DIR__ . "/api/Services/PreferencesService.php");
$orders = OrdersService::getOrderFactuur($orderid);
$orderdata = $orders[0];
$orderdoc1 = OrdersService::getOrderDoc($orderid);
$orderdoc = $orderdoc1[0];
$orderdocs = OrdersService::getOrderPdf($orderid);

// get filename
$docnr = getDocNr($orderdocs, $orderdata, $type);
$filename = getFileName($docnr, $type);
*/

  //$filename = "http://localhost/opwielekes/pdf/ReglementOpwielekes.pdf";
  //$filename = $_SESSION["pdfbase"] . "ReglementOpwielekes.pdf";
  $filename = "https://admin.opwielekes.be/pdf/ReglementOpwielekes.pdf";
  header("Content-type: application/pdf");
  header("Content-disposition: inline; filename=" . $filename );
	echo file_get_contents($filename);
	exit();


?>
