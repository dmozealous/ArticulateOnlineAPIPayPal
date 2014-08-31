<?php
/*
D I S C L A I M E R                                                                                          
WARNING: ANY USE BY YOU OF THE SAMPLE CODE PROVIDED IS AT YOUR OWN RISK.                                                                                   
mozealous.com provides this code "as is" without warranty of any kind, either express or implied, including but not limited to the implied warranties of merchantability and/or fitness for a particular purpose.   
This code was enhanced by Dave Mozealous (www.mozealous.com), this code was originally written by paypal
*/
require('createuser.php');
require('addToGroup.php');
require('findUser.php');
require('config.php');
require_once($soapLoc);

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-synch';

$tx_token = $_GET['tx'];

$req .= "&tx=$tx_token&at=$auth_token";

// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
// If possible, securely post back to paypal using HTTPS
// Your PHP server will need to be SSL enabled
// $fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

if (!$fp) {
//error
} else {
	fputs ($fp, $header . $req);
	// read the body data
	$res = '';
	$headerdone = false;
	while (!feof($fp)) {
		$line = fgets ($fp, 1024);
		if (strcmp($line, "\r\n") == 0) {
		// read the header
		$headerdone = true;
		}
		else if ($headerdone)
		{
		// header has been read. now read the contents
		$res .= $line;
		}
	}

// parse the data
$lines = explode("\n", $res);
$keyarray = array();
if (strcmp ($lines[0], "SUCCESS") == 0) {
	for ($i=1; $i<count($lines);$i++){
	list($key,$val) = explode("=", $lines[$i]);
	$keyarray[urldecode($key)] = urldecode($val);
} 
// check the payment_status is Completed
// check that txn_id has not been previously processed
// check that receiver_email is your Primary PayPal email
// check that payment_amount/payment_currency are correct
// process payment
$firstname = $keyarray['first_name'];
$lastname = $keyarray['last_name'];
$itemname = $keyarray['item_name'];
$amount = $keyarray['mc_gross'];
$email = $keyarray['payer_email'];

//This is the comment that goes in the email.  Make it more personal.
$comment = "Hi " . $firstname . ",\n" . $comment; 

//findUser checks if the user already exists on the account.  
//This will handle if a user purchases multiple courses on the same account.
//it will return false if no user exists, if the user exists, it will return the UserID of the user
$userFound = findUser ($AOAccountURL, $AOemail, $AOpassword, $AOCustomerID, $email);

//If user is not already created, create them.
//If the user is found, add them to specified group.
if ($userFound == "false"){
	$userCreatedSuccess = createNewUser ($AOAccountURL, $AOemail, $AOpassword, $AOCustomerID, $email, $comment, $AOGroupID);
}else{
	addUserToGroup ($AOAccountURL, $AOemail, $AOpassword, $AOCustomerID, $userFound, $AOGroupID);
}

//Let user know either everything worked, or something didn't.
if($userCreatedSuccess == 'true' || $userFound == "true" ){
	echo ("<p><h3>$firstname, thank you for your purchase!</h3></p>");
	echo ("<b>Payment Details</b><br>\n");
	echo ("<li>Item: $itemname</li>\n");
	echo ("<li>Amount: $amount</li>\n");
	echo ("<li>Email: $email</li>\n");
	echo ("");
	echo ("<br>Your transaction has been completed, and a receipt for your purchase has been emailed to you.");
	echo ("<br>Also, an email has been sent to <a href='mailto:". $email . "'> $email</a> with login details.<br>");
}else{
	echo ("<p><h3>Ooops $firstname, there was an error creating your account.</h3></p>");
	echo ("<br>Your payment went through, but we were unable to create your account.");
	echo ("<Br>Contact <a href='mailto:". $AOemail . "'>$AOemail</a> for more assitance.<br>");
}

}
else if (strcmp ($lines[0], "FAIL") == 0) {
	echo ("Ooops, looks like this transaction is invalid.  Contact <a href='mailto:". $AOemail . "'>$AOemail</a> for assistance.");
}

}

fclose ($fp);

?>

