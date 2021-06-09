<?php
// variables for PDO
$pdohost = '';
$pdouser = '';
$pdopass = '';
$pdodbname = '';
// variables for emails
$mailhost = '';  						  // Specify main and backup SMTP servers
$mailport = 465; 									  // TCP port to connect to
$mailsmtpauth = true;                               // Enable SMTP authentication
$mailusername = '';                // SMTP username
$mailpassword = '';                        // SMTP password
$mailsmtpecure = 'ssl';
$mailfromaddress = '';
$mailvars = [
  "mailhost" => $mailhost,
  "mailport" => $mailport,
  "mailsmtpauth" => $mailsmtpauth,
  "mailusername" => $mailusername,
  "mailpassword" => $mailpassword,
  "mailsmtpecure" => $mailsmtpecure,
  "mailfromaddress" => $mailfromaddress,
];
// for date conversion
$GLOBALS['mysqldateformat'] = '%d-%m-%Y';
$GLOBALS['phpdateformat'] = '%Y-%m-%d';
?>
