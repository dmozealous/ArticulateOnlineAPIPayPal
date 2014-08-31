<?php
///////////////////////////////////////////////
//           user configuration options
//			ideally, this page should only be available on the server to the administrator.
//			in other words, don't put this at the root of your webserver
///////////////////////////////////////////////

//Location of nusoap
$soapLoc = '../../lib/nusoap.php';

//Authorization Token from PayPal
$auth_token = "paypal_auth_token";

//AO Account login details
$AOemail = 'test@test.com'; //your AO email address
$AOpassword = 'apple'; //your AO password
$AOCustomerID = '89134'; //your AO customer ID
$AOGroupID = '9204f95a-a6e4-422c-b9b4-e4ff837a905a'; //the group you want to add them too
$AOAccountURL = 'https://blah.articulate-online.com'; //your account URL

//This comment will be sent to your users in the email that contains their login details.
$comment = 'Thank you for purchasing access to this site.  You can login to view your training with the login details below.';


?>