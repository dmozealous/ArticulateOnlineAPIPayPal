<?php
/*
D I S C L A I M E R                                                                                          
WARNING: ANY USE BY YOU OF THE SAMPLE CODE PROVIDED IS AT YOUR OWN RISK.                                                                                   
mozealous.com provides this code "as is" without warranty of any kind, either express or implied, including but not limited to the implied warranties of merchantability and/or fitness for a particular purpose.   
This code was written by Dave Mozealous (www.mozealous.com) 
*/
$useCURL = isset($_POST['usecurl']) ? $_POST['usecurl'] : '0';

function createNewUser ($accountURL, $emailaddress, $password, $customerID, $newEmail, $comment, $groupID) {

$params = "<CreateUsersRequest xmlns='http://www.articulate-online.com/services/api/1.0/'>
      <Credentials>
        <EmailAddress>$emailaddress</EmailAddress>
        <Password>$password</Password>
        <CustomerID>$customerID</CustomerID>
      </Credentials>
      <Emails>
        <string>$newEmail</string>
      </Emails>
      <AutoGeneratePassword>true</AutoGeneratePassword>
      <Password></Password>
      <SendLoginEmail>true</SendLoginEmail>
      <PersonalComment>$comment</PersonalComment>
	  <MemberOfGroupIDs>
        <string>$groupID</string>
      </MemberOfGroupIDs>

    </CreateUsersRequest>";

$client = new soapclient("$accountURL/services/api/1.0/ArticulateOnline.asmx?wsdl",array('encoding'=>'UTF-8'));
  ini_set("soap.wsdl_cache_enabled", "0"); 
 
$err = $client->getError();
echo $err;

$client->setUseCurl($useCURL);
$client->loadWSDL();

$userCreated = $client->call('CreateUsers',$params);

//find out if it worked
$userCreatedStatus = $userCreated['Success'];

return $userCreatedStatus;
}
?>