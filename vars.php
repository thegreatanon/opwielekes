<?php
// variables for PDO
$pdohost = '';
$pdouser = '';
$pdopass = '';
$pdodbname = '';
// variables for emails
$mailhost = 'smtp.gmail.com';  						  // Specify main and backup SMTP servers
$mailport = 465; 									  // TCP port to connect to
$mailsmtpauth = true;                               // Enable SMTP authentication
$mailusername = '';              
$mailpassword = '';                      
$mailsmtpecure = 'ssl';
$mailfromaddress = '';
$mailfromname = '';
// for date conversion
$GLOBALS['mysqldateformat'] = '%d-%m-%Y';
?>